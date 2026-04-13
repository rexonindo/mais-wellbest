<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\IVStatus;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
            ->headerActions([
/*
                Tables\Actions\Action::make('downloadTemplate')
                    ->label('Download Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(function () {

                        return Excel::download(
                            new \App\Exports\ItemActSpecsTemplate(),
                            'ItemActSpecs_Template.xlsx'
                        );

                    }),
*/
                Tables\Actions\Action::make('uploadExcel')
                    ->label('Upload Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('Excel File')
                            ->disk('public')
                            ->directory('excel_uploads')
                            ->rules(['required', 'file', 'mimes:xlsx,xls'])
                            ->required(),
                    ])
                    ->action(function (array $data) {

                        $path = storage_path('app/public/' . $data['file']);
                        $rows = Excel::toArray([], $path)[0];
//                        $header = array_map('strtoupper', $rows[0]);
                        $header = array_map(fn($h) => strtoupper(trim($h)), $rows[0]);
                        $requiredHeaders = ['DATE', 'ITEMCODE', 'PROCESSCODE', 'QTY'];
                        foreach ($requiredHeaders as $col) {
                            if (!in_array($col, $header)) {
                                throw new \Exception("Missing column: $col");
                            }
                        }     
                        // Remove header row
                        unset($rows[0]);
                        try {
                            DB::transaction(function () use ($rows, $header) {
                                foreach ($rows as $row) {
                                    if (empty(array_filter($row))) continue;
                                    $record = array_combine($header, $row);
                                    $TransDt = $record['DATE'] ?? null;
                                    if (is_numeric($TransDt)) {
                                        $TransDt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($TransDt)->format('Y-m-d');
                                    }
                                    DB::statement('CALL adjust_stock_opname(?, ?, ?, ?, ?)', [
                                        $TransDt,
                                        trim($record['ITEMCODE'] ?? ''),
                                        trim($record['PROCESSCODE'] ?? ''),
                                        floatval($record['QTY'] ?? 0),
                                        auth()->user()->name ?? 'System'
                                    ]);
                                }
                            });
                            Notification::make()
                                ->title('Upload Completed')
                                ->success()
                                ->send();

                        } catch (\Throwable $e) {

                            Notification::make()
                                ->title('Upload Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),                   

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


            ])        
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
            ->bulkActions([]);

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
