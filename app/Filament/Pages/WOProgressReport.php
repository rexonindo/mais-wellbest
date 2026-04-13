<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\WOProgress;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class WOProgressReport extends FBasePageResource implements HasTable
{
    use Tables\Concerns\InteractsWithTable;
    
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'WO Progress Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';    
    protected static ?string $title = 'WO Progress Report';    
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'wo-progress-report';
    protected static string $view = 'filament.pages.wo-progress-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => WOProgress::query()->whereRaw('1 = 0'))
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('wo_no')
                    ->label('WO No'),
                Tables\Columns\TextColumn::make('itm_cd')
                    ->label('Part No'),
                Tables\Columns\TextColumn::make('itm_type')
                    ->label('Part Type'),
                Tables\Columns\TextColumn::make('seq_no')
                    ->label('Seq No'),
                Tables\Columns\TextColumn::make('proc_cd')
                    ->label('Process Code'),
                Tables\Columns\TextColumn::make('proc_nm')
                    ->label('Process Name'),
                Tables\Columns\TextColumn::make('wo_qty')
                    ->label('WO Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('cav')
                    ->label('Cavity')
                    ->numeric()
                    ->alignEnd(),  
/*                      
                Tables\Columns\TextColumn::make('in_qty')
                    ->label('OUT Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
*/
                    Tables\Columns\TextColumn::make('rwk_qty')
                    ->label('Rework Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                                                  
                Tables\Columns\TextColumn::make('ng_qty')
                    ->label('NG Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('out_qty')
                    ->label('OK Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('ttl_qty')
                    ->label('Total Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),         
                Tables\Columns\TextColumn::make('ttl_qty_shoot')
                    ->label('Total Qty (Shoot)')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),       
                Tables\Columns\TextColumn::make('onhand_qty')
                    ->label('Onhand Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                                                      
                Tables\Columns\TextColumn::make('mchn_cd')
                    ->label('Machine'),
                Tables\Columns\TextColumn::make('emp_nm')
                    ->label('Operator'),
                Tables\Columns\TextColumn::make('start_time')->label('Start Time'),
                Tables\Columns\TextColumn::make('end_time')->label('End Time'),                                         
            ])

            ->filters($this->getTableFilters())
            ->actions([])
            ->bulkActions([])
            ->headerActions([
                Tables\Actions\Action::make('Export Excel')
                    ->button()
                    ->label('Export Excel')
                    ->color('success')
                    ->action(fn () => $this->exportExcel()),

                Tables\Actions\Action::make('Export PDF')
                    ->button()
                    ->label('Export PDF')
                    ->color('danger')
                    ->action(fn () => $this->exportPdf()),
            ]);


    }

    public function getTableRecords(): \Illuminate\Contracts\Pagination\Paginator
    {
        $filters = $this->getTableFiltersForm()?->getState() ?? [];

        $woNo   = data_get($filters, 'wo_no.wo_no');
        $itemCd = data_get($filters, 'itm_cd.itm_cd');
        $allFlg = data_get($filters, 'all_flg.all_flg', 1);

        $rows = DB::select(
            "CALL wo_progress_report(?, ?, ?)",
            [$woNo, $itemCd, $allFlg]
        );

        $collection = collect($rows)->map(function ($row) {
            $model = new WOProgress();
            $model->forceFill((array) $row);
            $model->exists = true;
            return $model;
        });

        $perPage = $this->getTableRecordsPerPage();
        $page = $this->getTablePage();

        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    public function getTableRecordKey($record): string
    {
        return $record->wo_no . '-' . $record->proc_cd;
    }    

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\Filter::make('wo_no')
                ->form([
                    Forms\Components\TextInput::make('wo_no')
                        ->label('WO No')
                        ->placeholder('Enter WO No'),
                ])
                ->query(fn ($query, array $data) =>
                    $query->when(
                        $data['wo_no'],
                        fn ($q, $value) => $q->where('wo_no', 'like', "%{$value}%")
                    )
                )
                ->indicateUsing(fn (array $data) =>
                    $data['wo_no'] ? "WO No: {$data['wo_no']}" : null
                ),

            Tables\Filters\Filter::make('itm_cd')
                ->form([
                    Forms\Components\TextInput::make('itm_cd')
                        ->label('Part No')
                        ->placeholder('Enter Part No'),
                ])
                ->query(fn ($query, array $data) =>
                    $query->when(
                        $data['itm_cd'],
                        fn ($q, $value) => $q->where('itm_cd', 'like', "%{$value}%")
                    )
                )
                ->indicateUsing(fn (array $data) =>
                    $data['itm_cd'] ? "Part No: {$data['itm_cd']}" : null
                ),

            Tables\Filters\Filter::make('all_flg')
                ->label('Data Scope')
                ->form([
                    Forms\Components\Select::make('all_flg')
                        ->label('Data Scope')
                        ->options([
                            0 => 'Input Data Only',
                            1 => 'Included Blank Data',                            
                        ])
                        ->default(0),
                ])
                ->query(function ($query, array $data) {
                    return $query; // filtering handled by stored procedure
                })
                ->indicateUsing(function (array $data): ?string {
                    return match ($data['all_flg'] ?? 0) {
                        0 => 'Input Data Only',
                        1 => 'Included Blank Data',                        
                        default => 0,
                    };
                }),

        ];
    }
  
    protected function exportExcel()
    {
        $records = collect($this->getTableRecords()->items());

        $filename = 'WOProgressReport_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new \App\Exports\WOProgressReportExcel($records),
            $filename
        );
    }

    protected function exportPdf()
    {
        $data = collect($this->getTableRecords()->items());

        $pdf = Pdf::loadView('pdf.wo-progress-report', [
            'data' => $data,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'WOProgressReport_' . now()->format('Ymd_His') . '.pdf'
        );
    }
           
}
