<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\NGDetailPivot;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class NGDetailPivotReport extends FBasePageResource implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'NG Detail Pivot By Process';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 10;
    protected static ?string $slug = 'ng-detail-pivot-report';
    protected static ?string $title = 'NG Detail Pivot By Process';        
    protected static string $view = 'filament.pages.ng-detail-pivot-report';

    protected array $dynamicColumns = [];

    protected function loadDynamicColumns(): void
    {
        if (! empty($this->dynamicColumns)) {
            return;
        }

        $rows = DB::select('CALL ng_detail_pivot(NULL, NULL)');

        if (! empty($rows)) {
            $this->dynamicColumns = array_keys((array) $rows[0]);
        }
    }

    /* -------------------------------------------------
       TABLE
    ------------------------------------------------- */
    public function table(Table $table): Table
    {
        $this->loadDynamicColumns();

        return $table
            ->query(fn () => NGDetailPivot::query()->whereRaw('1 = 0'))

            ->columns(
                collect($this->dynamicColumns)->map(function ($col) {

                    if (in_array($col, ['WO NO', 'PART NO', 'TYPE', 'REMARKS NG'])) {
                        return Tables\Columns\TextColumn::make($col)
                            ->label(strtoupper(str_replace('_', ' ', $col)))
                            ->wrap()
                            ->alignLeft()
                            ->toggleable()
                            ->extraAttributes([
                                'class' => 'text-left',
                                'style' => 'min-width:140px; white-space:nowrap;',    
                            ]);
                    }

                    return Tables\Columns\TextColumn::make($col)
                        ->label(strtoupper(str_replace('_', ' ', $col)))
                        ->extraAttributes([
                            'class' => 'text-right',
                            'style' => 'min-width:100px; max-width:320px;',
                        ])

                        ->toggleable();
                })->toArray()
            )
            ->filters($this->getTableFilters())
            ->paginated(false)
            ->actions([])
            ->bulkActions([])
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                    ->button()
                    ->label('Export Excel')
                    ->color('success')
                    ->action(fn () => $this->exportExcel()),
/*
                Tables\Actions\Action::make('Export PDF')
                    ->button()
                    ->label('Export PDF')
                    ->color('danger')
                    ->action(fn () => $this->exportPdf()),
*/
            ]);     
    }

    /* -------------------------------------------------
       STORED PROCEDURE EXECUTION (ONLY HERE)
    ------------------------------------------------- */
    public function getTableRecords(): EloquentCollection
    {
        $filters = $this->getTableFiltersForm()?->getState() ?? [];

        $woNo     = $filters['wo_no']['value'] ?? null;
        $itemCode = $filters['itm_cd']['value'] ?? null;

        $rows = DB::select(
            'CALL ng_detail_pivot(?, ?)',
            [$woNo, $itemCode]
        );

        return new EloquentCollection(
            collect($rows)->map(fn ($row) =>
                new class((array) $row) extends Model {
                    protected $guarded = [];
                    public $timestamps = false;
                }
            )
        );
    }

    /* -------------------------------------------------
       UNIQUE ROW KEY
    ------------------------------------------------- */
    public function getTableRecordKey($record): string
    {
        return spl_object_hash($record);
    }

    /* -------------------------------------------------
       FILTERS
    ------------------------------------------------- */
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('wo_no')
                ->label('Work Order')
                ->options(
                    DB::table('wo_tbl')
                        ->orderBy('wo_no')
                        ->pluck('wo_no', 'wo_no')
                        ->toArray()
                )
                ->searchable(),

            SelectFilter::make('itm_cd')
                ->label('Part No')
                ->options(
                    DB::table('itm_tbl')
                        ->orderBy('itm_cd')
                        ->pluck('itm_cd', 'itm_cd')
                        ->toArray()
                )
                ->searchable(),
        ];
    }

    protected function exportExcel()
    {
        $records = $this->getTableRecords(); // <- call SP with filters
        $filename = 'NGDetailPivotReport_' . now()->format('Ymd_His') . '.xlsx';

        /*
        return Excel::download(
            new \App\Exports\NGDetailPivotReportExcel($records),
            $filename
        );
        */

        return Excel::download(
            new \App\Exports\NGDetailPivotReportExcel($records),
            $filename,
            \Maatwebsite\Excel\Excel::XLSX,
            [
                'charts' => true
            ]
        );


    }

    protected function exportPdf()
    {
        $records = $this->getTableRecords(); // <- call SP with filters
        $pdf = Pdf::loadView('pdf.ng-detail-pivot-report', [
            'data' => $records,
        ])->setPaper('a3', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'NGStatusPivot' . now()->format('Ymd_His') . '.pdf'
        );
    }        
}