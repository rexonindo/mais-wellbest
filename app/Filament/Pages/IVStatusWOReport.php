<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\IVStatusWO;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class IVStatusWOReport extends FBasePageResource implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Inventory Status WO Report';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'iv-status-wo-report';
    protected static ?string $title = 'Inventory Status WO Report';        
    protected static string $view = 'filament.pages.iv-status-wo-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return IVStatusWO::query()
                    ->select(
                        'wo_no', 'itm_cd', 'wip_cd', 'qty'
                    )
                    ->from('iv_status_wo_view')
                    ->where('qty', '>', 0)
                    ->orderBy('wo_no')
                    ->orderBy('itm_cd')
                    ->orderBy('proc_cd')
                    ->orderBy('wip_cd');
            })
            ->columns([
                Tables\Columns\TextColumn::make('wo_no')
                    ->label('WO No')
                    ->searchable(),                
                Tables\Columns\TextColumn::make('itm_cd')
                    ->label('Part No')
                    ->searchable(),
                Tables\Columns\TextColumn::make('proc_cd')
                    ->label('Process Code')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('wip_cd')
                    ->label('WIP Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
            ])

            ->filters([
                Tables\Filters\Filter::make('wo_no')
                    ->form([
                        Forms\Components\TextInput::make('wo_no')
                            ->label('WO No'),
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
                            ->label('Part No'),
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

                Tables\Filters\Filter::make('wip_cd')
                    ->form([
                        Forms\Components\TextInput::make('wip_cd')
                            ->label('WIP Code'),
                    ])
                    ->query(fn ($query, array $data) =>
                        $query->when(
                            $data['wip_cd'],
                            fn ($q, $value) => $q->where('wip_cd', 'like', "%{$value}%")
                        )
                    )
                    ->indicateUsing(fn (array $data) =>
                        $data['wip_cd'] ? "WIP Code: {$data['wip_cd']}" : null
                    ),
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

    public function getTableRecordKey($record): string
    {
        return $record->wo_no . '-' . $record->itm_cd . '-' . $record->wip_cd;
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\Filter::make('wo_no')
                ->form([
                    Forms\Components\TextInput::make('wo_no')
                        ->label('WO No'),
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
                        ->label('Part No'),
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

            Tables\Filters\Filter::make('wip_cd')
                ->form([
                    Forms\Components\TextInput::make('wip_cd')
                        ->label('WIP Code'),
                ])
                ->query(fn ($query, array $data) =>
                    $query->when(
                        $data['wip_cd'],
                        fn ($q, $value) => $q->where('wip_cd', 'like', "%{$value}%")
                    )
                )
                ->indicateUsing(fn (array $data) =>
                    $data['wip_cd'] ? "WIP Code: {$data['wip_cd']}" : null
                ),

        ];
    }

    protected function exportExcel()
    {
        $records = $this->getFilteredTableQuery()->get();

        return Excel::download(
            new \App\Exports\IVStatusWOReportExcel($records),
            'InventoryStatusWOReport_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    protected function exportPdf()
    {
        $data = $this->getFilteredTableQuery()->get();

        $pdf = Pdf::loadView('pdf.iv-status-wo-report', [
            'data' => $data,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'InventoryStatusWOReport_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
