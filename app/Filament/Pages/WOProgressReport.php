<?php

namespace App\Filament\Pages;

use App\Filament\FilamentBasePage;
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
class WOProgressReport extends FilamentBasePage implements HasTable
{
    use Tables\Concerns\InteractsWithTable;
    
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'WO Progress Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';    
    protected static ?string $title = 'WO Progress Report';    
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'wo-progress-report';
    protected static string $view = 'filament.pages.wo-progress-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return WOProgress::query()
                    ->select(
                        'A.wo_no', 'A.itm_cd', 'B.itm_type',
                        'C.seq_no', 'C.proc_cd', 'D.proc_nm',
                        'E.in_qty', 'E.ng_qty', 'E.out_qty', 'E.mchn_cd', 'F.emp_nm'
                    )
                    ->from('wo_tbl as A')
                    ->leftJoin('itm_tbl as B', 'A.itm_cd', '=', 'B.itm_cd')
                    ->leftJoin('prdroute_tbl as C', 'B.itm_type', '=', 'C.itm_type')
                    ->leftJoin('proc_tbl as D', 'C.proc_cd', '=', 'D.proc_cd')
                    ->leftJoin('prdlog_tbl as E', function ($join) {
                        $join->on('A.wo_no', '=', 'E.wo_no')
                            ->on('A.itm_cd', '=', 'E.itm_cd')
                            ->on('C.proc_cd', '=', 'E.proc_cd');
                    })
                    ->leftJoin('empl_tbl as F', 'E.emp_id', '=', 'F.emp_id')
                    ->orderBy('A.wo_no')
                    ->orderBy('C.seq_no');
            })

            ->columns([
                Tables\Columns\TextColumn::make('wo_no')
                    ->label('WO No')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('A.wo_no', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('itm_cd')
                    ->label('Product Code')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('A.itm_cd', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('itm_type')
                    ->label('Product Type')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('B.itm_type', 'like', "%{$search}%");
                    }),                    
                Tables\Columns\TextColumn::make('seq_no')->label('Seq No'),
                Tables\Columns\TextColumn::make('proc_cd')->label('Proc Code'),
                Tables\Columns\TextColumn::make('proc_nm')
                    ->label('Proc Name')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('D.proc_nm', 'like', "%{$search}%");
                    }),                    
                Tables\Columns\TextColumn::make('in_qty')
                    ->label('In Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                       
                Tables\Columns\TextColumn::make('ng_qty')
                    ->label('NG Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                       
                Tables\Columns\TextColumn::make('out_qty')
                    ->label('Out Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('mchn_cd')
                    ->label('Machine')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('E.mchn_cd', 'like', "%{$search}%");
                    }),                 
                Tables\Columns\TextColumn::make('emp_nm')
                    ->label('Employee')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('F.emp_nm', 'like', "%{$search}%");
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
                        ->when($data['wo_no'], fn($q, $value) => $q->where('A.wo_no', 'like', "%{$value}%"));
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['wo_no'] ? "WO No: {$data['wo_no']}" : null;
                }),                
            Tables\Filters\Filter::make('itm_cd')
                ->form([
                    Forms\Components\TextInput::make('itm_cd')
                        ->label('Product Code')
                        ->placeholder('Enter Product Code'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['itm_cd'], fn($q, $value) => $q->where('A.itm_cd', 'like', "%{$value}%"));
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['itm_cd'] ? "Product Code: {$data['itm_cd']}" : null;
                }),                  
        ];
    }    

    protected function exportExcel()
    {
        $data = $this->getTableQuery()->get()->toArray();

        $filename = 'WOProgressReport_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new \App\Exports\ArrayExport($data), $filename);
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
        return DB::table('wo_tbl as A')
            ->leftJoin('itm_tbl as B', 'A.itm_cd', '=', 'B.itm_cd')
            ->leftJoin('prdroute_tbl as C', 'B.itm_type', '=', 'C.itm_type')
            ->leftJoin('proc_tbl as D', 'C.proc_cd', '=', 'D.proc_cd')
            ->leftJoin('prdlog_tbl as E', function ($join) {
                $join->on('A.wo_no', '=', 'E.wo_no')
                    ->on('A.itm_cd', '=', 'E.itm_cd')
                    ->on('C.proc_cd', '=', 'E.proc_cd');
            })
            ->leftJoin('empl_tbl as F', 'E.emp_id', '=', 'F.emp_id')
            ->select([
                'A.wo_no as wo_no',
                'A.itm_cd as itm_cd',
                'B.itm_type as itm_type',
                'C.seq_no as seq_no',
                'C.proc_cd as proc_cd',
                'D.proc_nm as proc_nm',
                'E.in_qty as in_qty',
                'E.ng_qty as ng_qty',
                'E.out_qty as out_qty',
                'E.mchn_cd as mchn_cd',
                'F.emp_nm as emp_nm',
            ])
            ->orderBy('A.wo_no')
            ->orderBy('C.seq_no');
    }    
}
