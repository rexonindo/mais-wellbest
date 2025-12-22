<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\WOStatus;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; // for Excel export
use Barryvdh\DomPDF\Facade\Pdf;     // for PDF export

class WOStatusReport extends FBasePageResource implements HasTable
{
    use Tables\Concerns\InteractsWithTable;
    
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'WO Status Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 2;    
    protected static ?string $slug = 'wo-status-report';
    protected static ?string $title = 'Work Order Status Report';
    protected static string $view = 'filament.pages.wo-status-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return WOStatus::query()
                    ->select(
                        'wo_no', 'cust_nm', 'req_dt', 'itm_cd', 'itm_type', 
                        'proc_cd', 'proc_nm', 
                        'end_time', 'plan_qty', 'out_qty', 'os_qty',
                        'mchn_cd', 'emp_nm' 
                    )
                    ->from('wo_status_view')
                    ->orderBy('wo_no');
            })

            ->columns([
                Tables\Columns\TextColumn::make('wo_no')
                    ->label('WO No')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('wo_no', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('cust_nm')
                    ->label('Customer Name')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('cust_nm', 'like', "%{$search}%");
                    }),                    
                Tables\Columns\TextColumn::make('req_dt')->label('Req Date'),    
                Tables\Columns\TextColumn::make('itm_cd')
                    ->label('Part No')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('itm_cd', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('itm_type')
                    ->label('Part Type')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('itm_type', 'like', "%{$search}%");
                    }),                    
                Tables\Columns\TextColumn::make('proc_cd')->label('Proc Code'),
                Tables\Columns\TextColumn::make('proc_nm')
                    ->label('Process Name')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('proc_nm', 'like', "%{$search}%");
                    }),                    
                Tables\Columns\TextColumn::make('end_time')->label('End Time'),  
                Tables\Columns\TextColumn::make('plan_qty')
                    ->label('WO Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),   
                Tables\Columns\TextColumn::make('out_qty')
                    ->label('OUT Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                          
                Tables\Columns\TextColumn::make('os_qty')
                    ->label('O/S Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                       
                Tables\Columns\TextColumn::make('mchn_cd')
                    ->label('Machine')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('mchn_cd', 'like', "%{$search}%");
                    }),                 
                Tables\Columns\TextColumn::make('emp_nm')
                    ->label('Operator')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('emp_nm', 'like', "%{$search}%");
                    }),                     
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
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['wo_no'], fn($q, $value) => $q->where('wo_no', 'like', "%{$value}%"));
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['wo_no'] ? "WO No: {$data['wo_no']}" : null;
                }),                
            Tables\Filters\Filter::make('itm_cd')
                ->form([
                    Forms\Components\TextInput::make('itm_cd')
                        ->label('Part No')
                        ->placeholder('Enter Part No'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['itm_cd'], fn($q, $value) => $q->where('itm_cd', 'like', "%{$value}%"));
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['itm_cd'] ? "Part No: {$data['itm_cd']}" : null;
                }),                  
        ];
    }    

    protected function exportExcel()
    {
        $records = $this->getFilteredTableQuery()->get();
        $filename = 'WOStatusReport_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new \App\Exports\WOStatusReportExport($records), $filename);
    }

    protected function exportPdf()
    {
        $data = $this->getFilteredTableQuery()->get();
        $pdf = Pdf::loadView('pdf.wo-status-report', [
            'data' => $data,
        ])->setPaper('a4', 'landscape');
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'WOStatusReport_' . now()->format('Ymd_His') . '.pdf'
        );
    }
            
}
