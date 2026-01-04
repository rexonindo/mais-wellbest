<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\IVStatus;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class IVStatusReport extends FBasePageResource implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Inventory Status Report';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'iv-status-report';
    protected static ?string $title = 'Inventory Status Report';        
    protected static string $view = 'filament.pages.iv-status-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return IVStatus::query()
                    ->select(
                        'itm_cd', 'wip_cd', 'qty'
                    )
                    ->from('iv_status_view')
                    ->where('qty', '>', 0)
                    ->orderBy('itm_cd')
                    ->orderBy('proc_cd')
                    ->orderBy('wip_cd');
            })
            ->columns([
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
        return $record->itm_cd . '-' . $record->wip_cd;
    }

    protected function getTableFilters(): array
    {
        return [
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
            new \App\Exports\IVStatusReportExcel($records),
            'InventoryStatusReport_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    protected function exportPdf()
    {
        $data = $this->getFilteredTableQuery()->get();

        $pdf = Pdf::loadView('pdf.iv-status-report', [
            'data' => $data,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'InventoryStatusReport_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
