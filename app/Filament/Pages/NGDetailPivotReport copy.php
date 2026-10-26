<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\NGDetailPivot;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
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

    public string $reportType = 'CUTTING';
    protected array $dynamicColumns = [];

    protected function getStoredProcedureName(): string
    {
        return match ($this->reportType) {
            'TEST'    => 'ng_detail_2nd_pivot',
            'CUTTING' => 'ng_detail_1st_pivot',
            default   => 'ng_detail_1st_pivot',
        };
    }    

    public function updatedReportType(): void
    {
        $this->dynamicColumns = [];

        $this->resetTable();
    }

    protected function loadDynamicColumns(): void
    {
        if (! empty($this->dynamicColumns)) {
            return;
        }

        $procedure = $this->getStoredProcedureName();

        $rows = DB::select(
            "CALL {$procedure}(NULL, NULL, NULL, NULL, NULL)"
        );

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

                    if (in_array($col, ['WO NO', 'PART NO', 'TYPE'])) {
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
                Tables\Actions\Action::make('export_excel')
                    ->button()
                    ->label('Export Excel')
                    ->color('success')
                    ->action(fn () => $this->exportExcel()),
/*
                Tables\Actions\Action::make('export_pdf')
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

        $woNo      = $filters['wo_no']['value'] ?? null;
        $itemCode  = $filters['itm_cd']['value'] ?? null;

        $itmType = $filters['itm_type']['values'] ?? [];
        if (is_array($itmType)) {
            $itmType = !empty($itmType)
                ? implode(',', $itmType)
                : null;
        }        

        $endDate1  = $filters['end_date']['end_date_1'] ?? null;
        $endDate2  = $filters['end_date']['end_date_2'] ?? null;

        // Select stored procedure based on report type
        $procedure = $this->getStoredProcedureName();

        $rows = DB::select(
            "CALL {$procedure}(?, ?, ?, ?, ?)",
            [
                $woNo,
                $itemCode,
                $itmType,
                $endDate1,
                $endDate2,
            ]
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

            SelectFilter::make('itm_type')
                ->label('Item Type')
                ->multiple()
                ->options(
                    DB::table('itm_tbl')
                        ->whereNotNull('itm_type')
                        ->where('itm_type', '<>', '')
                        ->distinct()
                        ->orderBy('itm_type')
                        ->pluck('itm_type', 'itm_type')
                        ->toArray()
                )
                ->searchable(),

            Filter::make('end_date')
                ->form([
                    DatePicker::make('end_date_1')
                        ->label('End Date From'),
                    DatePicker::make('end_date_2')
                        ->label('End Date To'),
                ])
                ->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['end_date_1'] ?? null) {
                        $indicators[] = 'End Date From ' . $data['end_date_1'];
                    }

                    if ($data['end_date_2'] ?? null) {
                        $indicators[] = 'End Date To ' . $data['end_date_2'];
                    }

                    return $indicators;
                }),
        ];
    }



    protected function exportExcel()
    {
        $records = $this->getTableRecords();
        $reportType = strtoupper($this->reportType);
        $reportTitle = 'NG DETAIL PIVOT BY PROCESS - Start From Process : ' . $reportType;
        $filename = 'NGDetailPivotReport_'
            . $reportType . '_'
            . now()->format('Ymd_His')
            . '.xlsx';

        return Excel::download(
            new \App\Exports\NGDetailPivotReportExcel(
                $records,
                $reportTitle
            ),
            $filename,
            \Maatwebsite\Excel\Excel::XLSX,
            [
                'charts' => true,
            ]
        );
    }


    protected function exportPdf()
    {
        $records = $this->getTableRecords();
        $reportType = strtoupper($this->reportType);
        $reportTitle = 'NG DETAIL PIVOT BY PROCESS - Start From Process : ' . $reportType;
        $filename = 'NGDetailPivotReport_'
            . $reportType . '_'
            . now()->format('Ymd_His')
            . '.pdf';

        $pdf = Pdf::loadView('pdf.ng-detail-pivot-report', [
            'data'       => $records,
            'reportType' => $reportType,
        ])->setPaper('a3', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename);
    }
    
}