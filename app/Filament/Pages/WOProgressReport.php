<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\WOProgress;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; // for Excel export
use Barryvdh\DomPDF\Facade\Pdf;     // for PDF export

// class WOProgressReport extends Page implements HasTable
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
            ->query(function () {
                return WOProgress::query()
                    ->select(
                        'wo_no', 'itm_cd', 'itm_type', 'seq_no', 'proc_cd', 'proc_nm',
                        'start_time', 'end_time', 'wo_qty', 'cav',
                        'in_qty', 'rwk_qty', 'ng_qty', 'out_qty', 'mchn_cd', 'emp_nm'
                    )
                    ->from('wo_progress_view')
                    ->orderBy('wo_no')
                    ->orderBy('seq_no')
                    ->orderBy('end_time');
            })

            ->columns([
                Tables\Columns\TextColumn::make('wo_no')
                    ->label('WO No')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('wo_no', 'like', "%{$search}%");
                    }),
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
                Tables\Columns\TextColumn::make('seq_no')
                    ->label('Seq No'),
                Tables\Columns\TextColumn::make('proc_cd')
                    ->label('Process Code'),
                Tables\Columns\TextColumn::make('proc_nm')
                    ->label('Process Name')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('proc_nm', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('wo_qty')
                    ->label('WO Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('cav')
                    ->label('Cavity')
                    ->numeric()
                    ->alignEnd(),    
                Tables\Columns\TextColumn::make('in_qty')
                    ->label('IN Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
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
                    ->label('OUT Qty')
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
        $data = $this->getTableQuery()->get()->toArray();

        $filename = 'WOProgressReport_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new \App\Exports\WOProgressReportExcel($data), $filename);
    }

    protected function exportPdf()
    {
        $data = $this->getTableQuery()->get();

        $pdf = Pdf::loadView('pdf.wo-progress-report', compact('data'))
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'WOProgressReport_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    public function getTableQuery()
    {
        return DB::table('wo_progress_view')
            ->select([
                'wo_no',
                'itm_cd', 
                'itm_type', 
                'seq_no', 
                'proc_cd', 
                'proc_nm',
                'wo_qty', 
                'cav',
                'in_qty', 
                'rwk_qty', 
                'ng_qty', 
                'out_qty', 
                'mchn_cd', 
                'emp_nm',
                'start_time', 
                'end_time',                 
            ])
            ->orderBy('wo_no')
            ->orderBy('seq_no')
            ->orderBy('end_time');
    }    
}
