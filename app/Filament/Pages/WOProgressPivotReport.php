<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\WOProgressPivot;
use App\Models\WorkOrder;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class WOProgressPivotReport extends FBasePageResource implements HasTable, HasForms
{
    use Tables\Concerns\InteractsWithTable;
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'WO Progress Pivot By Process';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'wo-progress-pivot-report';
    protected static ?string $title = 'WO Progress Pivot By Process';
    protected static string $view = 'filament.pages.wo-progress-pivot-report';


    /* ---------------------------------
       FORM STATE (IMPORTANT)
    --------------------------------- */
    public ?string $wo_no = null;
    public ?string $itm_cd = null;

    /* ---------------------------------
       FORM (FILTER UI)
    --------------------------------- */
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)->schema([

                Forms\Components\Select::make('wo_no')
                    ->label('Work Order')
                    ->options(
                        DB::table('wo_tbl')
                            ->orderBy('wo_no')
                            ->pluck('wo_no', 'wo_no')
                    )
                    ->searchable(),

                Forms\Components\Select::make('itm_cd')
                    ->label('Part No')
                    ->options(
                        DB::table('itm_tbl')
                            ->orderBy('itm_cd')
                            ->pluck('itm_cd', 'itm_cd')
                    )
                    ->searchable(),
            ]),
        ];
    }

    /* ---------------------------------
       TABLE
    --------------------------------- */
    public function table(Table $table): Table
    {
        $baseColumns = ['WO NO', 'PART NO', 'TYPE', 'END DATE', 'WO QTY'];
        $dynamicColumns = [];

        if ($this->wo_no || $this->itm_cd) {
            $rows = DB::select(
                'CALL wo_progress_pivot_by_process_allinfo(?, ?)',
                [$this->wo_no, $this->itm_cd]
            );

            if (!empty($rows)) {
                $dynamicColumns = array_diff(
                    array_keys((array) $rows[0]),
                    $baseColumns
                );
            }
        }

        $allColumns = array_merge($baseColumns, $dynamicColumns);

        return $table
            ->headerActions([
                Tables\Actions\Action::make('search')
                    ->label('Search')
                    ->button()
                    ->action(fn () => $this->resetTable()),

                Tables\Actions\Action::make('exportExcel')
                    ->label('Export Excel')
                    ->color('success')
                    ->action('exportExcel'),

                Tables\Actions\Action::make('exportPdf')
                    ->label('Export PDF')
                    ->color('danger')
                    ->action('exportPdf'),
            ])        
            ->query(fn () => WorkOrder::query()->whereRaw('1=0'))
            ->columns(
                collect($allColumns)->map(fn ($col) =>
                    Tables\Columns\TextColumn::make($col)
                        ->label(strtoupper(str_replace('_', ' ', $col)))
                        ->toggleable()
                )->toArray()
            )
            ->paginated(false)
            ->emptyStateHeading('No data')
            ->emptyStateDescription('Please select filter');
    }

    /* ---------------------------------
       DATA
    --------------------------------- */
    public function getTableRecordKey($record): string
    {
        return md5(json_encode($record->getAttributes()));
    }

    public function getTableRecords(): EloquentCollection
    {
        if (!$this->wo_no && !$this->itm_cd) {
            return new EloquentCollection([]);
        }

        $rows = DB::select(
            'CALL wo_progress_pivot_by_process_allinfo(?, ?)',
            [$this->wo_no, $this->itm_cd]
        );

        return new EloquentCollection(
            collect($rows)->map(function ($row) {
                $model = new class extends Model {
                    protected $guarded = [];
                    public $timestamps = false;
                };

                $model->forceFill((array) $row);

                return $model;
            })
        );
    }

    protected function getFormStatePath(): string
    {
        return '';
    }

    /* ---------------------------------
       EXPORT
    --------------------------------- */
    public function exportExcel()
    {
        $records = $this->getTableRecords();

        if ($records->isEmpty()) {
            return;
        }

        return Excel::download(
            new \App\Exports\WOProgressPivotReportExcel($records),
            'WOProgressPivotReport_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        $records = $this->getTableRecords();

        $pdf = Pdf::loadView('pdf.wo-progress-pivot-report', [
            'data' => $records,
        ])->setPaper('a3', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'WOProgressPivot_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}