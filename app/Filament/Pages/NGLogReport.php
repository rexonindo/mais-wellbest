<?php

namespace App\Filament\Pages;

use App\Filament\Resources\FBasePageResource;
use App\Models\NgLogView;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel; // for Excel export
use Barryvdh\DomPDF\Facade\Pdf;     // for PDF export

/*
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\NgLogView;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use Filament\Tables\Actions\Action;
use PDF;
*/

class NGLogReport extends FBasePageResource implements HasTable
{
    use Tables\Concerns\InteractsWithTable;


    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'NG Log Report';
    protected static ?int $navigationSort = 3;    
    // protected static ?string $slug = 'wo-status-report';
    // protected static ?string $title = 'Work Order Status Report';
    protected static string $view = 'filament.pages.ng-log-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return NgLogView::query()
                    ->select(
                        'start_time', 'itm_cd', 'itm_type', 'proc_nm', 'ng_nm', 
                        'ng_qty', 'emp_id', 'mchn_nm'
                    )
                    ->from('nglog_view')
                    ->orderBy('start_time');
            })

            ->columns([
                Tables\Columns\TextColumn::make('start_time')->label('Process Date'),  
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
                Tables\Columns\TextColumn::make('proc_nm')
                    ->label('Process Name')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('proc_nm', 'like', "%{$search}%");
                    }),                    
                Tables\Columns\TextColumn::make('ng_nm')
                    ->label('NG Name')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('ng_nm', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('ng_qty')
                    ->label('NG Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),   
                Tables\Columns\TextColumn::make('emp_id')
                    ->label('Operator')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('emp_id', 'like', "%{$search}%");
                    }),                        
                Tables\Columns\TextColumn::make('mchn_nm')
                    ->label('Machine')
                    ->searchable(query: function ($query, $search) {
                        return $query->where('mchn_nm', 'like', "%{$search}%");
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
        return $record->start_time . '-' . $record->itm_cd;
    }    

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\Filter::make('start_time')
                ->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Process Date')
                        ->placeholder('Select date'),
                ])
                ->query(function ($query, array $data) {
                    if (! $data['start_date']) {
                        return $query;
                    }

                    return $query->whereDate('start_time', $data['start_date']);
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['start_date']
                        ? "Process Date: {$data['start_date']}"
                        : null;
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
            Tables\Filters\Filter::make('ng_nm')
                ->form([
                    Forms\Components\TextInput::make('ng_nm')
                        ->label('NG Name')
                        ->placeholder('Enter NG Name'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['ng_nm'], fn($q, $value) => $q->where('ng_nm', 'like', "%{$value}%"));
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['ng_nm'] ? "NG Name: {$data['ng_nm']}" : null;
                }),                              
        ];
    }    

    protected function exportExcel()
    {
        $data = $this->getTableQuery()->get()->toArray();

        $filename = 'NGLogReport_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new \App\Exports\NGLogReportExcel($data), $filename);
    }

    protected function exportPdf()
    {
        $data = $this->getTableQuery()->get();

        $pdf = Pdf::loadView('pdf.ng-log-report', compact('data'))
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'NGLogReport_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    public function getTableQuery()
    {
        return DB::table('nglog_view')
            ->select([
                'start_time', 
                'itm_cd', 
                'itm_type', 
                'proc_nm', 
                'ng_nm', 
                'ng_qty', 
                'emp_id', 
                'mchn_nm',
            ])
            ->orderBy('start_time');
    }   

    public function getNgSummaryByNGName()
    {
        $query = $this->getFilteredTableQuery();
        $summaryQuery = clone $query;
        if (property_exists($summaryQuery, 'orders')) {
            $summaryQuery->orders = null;
        }
        $summaryQuery->getQuery()->orders = null;
        return $summaryQuery
            ->select('ng_nm', DB::raw('SUM(ng_qty) AS total_qty'))
            ->groupBy('ng_nm')
            ->orderBy('total_qty', 'desc') // optional
            ->get();
    }

    public function getNgSummaryByPartNo()
    {
        $query = $this->getFilteredTableQuery();
        $summaryQuery = clone $query;
        if (property_exists($summaryQuery, 'orders')) {
            $summaryQuery->orders = null;
        }
        $summaryQuery->getQuery()->orders = null;
        return $summaryQuery
            ->select('itm_cd', DB::raw('SUM(ng_qty) AS total_qty'))
            ->groupBy('itm_cd')
            ->orderBy('total_qty', 'desc') // optional
            ->get();
    }

    public function getNgSummaryByProcess()
    {
        $query = $this->getFilteredTableQuery();
        $summaryQuery = clone $query;
        if (property_exists($summaryQuery, 'orders')) {
            $summaryQuery->orders = null;
        }
        $summaryQuery->getQuery()->orders = null;
        return $summaryQuery
            ->select('proc_nm', DB::raw('SUM(ng_qty) AS total_qty'))
            ->groupBy('proc_nm')
            ->orderBy('total_qty', 'desc') // optional
            ->get();
    }    
}
