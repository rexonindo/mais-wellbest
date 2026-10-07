<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\ProductionLogResource\Pages;
use App\Filament\Resources\ProductionLogResource\RelationManagers;
use App\Models\ProductionLog;
use App\Models\WorkOrder; 
use App\Models\WorkOrderProcess; 
use App\Models\Item; 
use App\Models\Machine; 
use App\Models\Employee; 
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;

class ProductionLogResource extends BaseResource
{
    protected static ?string $model = ProductionLog::class;
    protected static ?string $navigationGroup = 'Actual Production';
    protected static ?string $navigationLabel = 'Production Log';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $title = 'Production Log';    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1) 
            ->schema([

                Forms\Components\Section::make(null)
                    ->columns(2)
                    ->schema([
                        // 1) PROCESS FIRST
                        Forms\Components\Select::make('proc_cd')
                            ->label('Process')
                            ->required()
                            ->columnSpanFull()
                            ->extraAttributes([
                                'class' => 'fi-input-wrp bg-yellow-100',
                                'x-on:move-focus-proc-cd.window' => "
                                    const input = \$el.querySelector('input');
                                    if (input) input.focus();
                                ",
                            ])
                            ->disabled(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                            ->options(function ($livewire) {
                                // Edit mode: only the saved process, so the field still shows its label
                                if ($livewire instanceof \Filament\Resources\Pages\EditRecord) {
                                    $savedProcCd = $livewire->getRecord()?->proc_cd;
                                    if (!$savedProcCd) return [];

                                    return \DB::table('proc_tbl')
                                        ->where('proc_cd', $savedProcCd)
                                        ->get()
                                        ->mapWithKeys(fn ($r) => [$r->proc_cd => "{$r->proc_cd} - {$r->proc_nm}"])
                                        ->toArray();
                                }

                                // Create mode: every process that is used by at least one WO
                                return \DB::table('wo_proc_tbl')
                                    ->join('proc_tbl', 'wo_proc_tbl.proc_cd', '=', 'proc_tbl.proc_cd')
                                    ->select('proc_tbl.proc_cd', 'proc_tbl.proc_nm')
                                    ->distinct()
                                    ->orderBy('proc_tbl.proc_cd')
                                    ->get()
                                    ->mapWithKeys(fn ($r) => [$r->proc_cd => "{$r->proc_cd} - {$r->proc_nm}"])
                                    ->toArray();
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $livewire) {
                                // Process changed -> the previously chosen WO may not be valid anymore
                                if ($livewire instanceof \Filament\Resources\Pages\CreateRecord) {
                                    $set('work_order_lookup', null);
                                    $set('wo_no', null);
                                    $set('itm_cd', null);
                                    $set('itm_nm', null);
                                    $set('seq_no', null);
                                    $set('mchn_cd', null);
                                    $set('cav', null);
                                    $set('avail_qty', null);
                                    $set('in_qty', null);
                                    $set('out_qty', null);
                                }
                            }),

                        // 2) WO NUMBER SECOND (filtered by selected process)
                        Forms\Components\Select::make('work_order_lookup')
                            ->label('Work Order / Customer P/N')
                            ->columnSpanFull()
                            ->searchable()
                            ->live()
                            ->dehydrated(false)
                            ->placeholder(fn (callable $get) => $get('proc_cd')
                                ? 'Type WO No / Part No to search'
                                : 'Select Process first')
                            ->disabled(fn ($livewire, callable $get) =>
                                $livewire instanceof \Filament\Resources\Pages\EditRecord || blank($get('proc_cd')))
                            ->getSearchResultsUsing(function (string $search, callable $get): array {

                                $procCd = $get('proc_cd');
                                if (!$procCd) {
                                    return [];
                                }

                                // 1) Candidate WOs: has the selected process + matches the search text
                                $candidates = WorkOrder::query()
                                    ->join('itm_tbl', 'wo_tbl.itm_cd', '=', 'itm_tbl.itm_cd')
                                    ->join('wo_proc_tbl', function ($join) use ($procCd) {
                                        $join->on('wo_proc_tbl.wo_no', '=', 'wo_tbl.wo_no')
                                            ->where('wo_proc_tbl.proc_cd', '=', $procCd);
                                    })
                                    ->where(function ($q) use ($search) {
                                        $q->where('wo_tbl.wo_no', 'like', "%{$search}%")
                                            ->orWhere('itm_tbl.itm_cd', 'like', "%{$search}%")
                                            ->orWhere('itm_tbl.itm_type', 'like', "%{$search}%");
                                    })
                                    ->select(
                                        'wo_tbl.wo_no',
                                        'itm_tbl.itm_cd',
                                        'itm_tbl.itm_type',
                                        'wo_proc_tbl.seq_no',
                                        'wo_proc_tbl.shoot_qty'
                                    )
                                    ->orderBy('wo_tbl.wo_no')
                                    ->limit(300) // safety cap on how many WOs get checked per search
                                    ->get();

                                // 2) Keep only WOs that still have available qty for this process
                                $results = [];
                                foreach ($candidates as $row) {

                                    if ((int) $row->seq_no === 1) {
                                        // First process: same rule as your afterStateUpdated (qty comes from shoot_qty)
                                        $availPcs = floatval($row->shoot_qty ?? 0);
                                    } else {
                                        $avail    = \DB::select('CALL get_wo_available_qty(?, ?)', [$row->wo_no, $procCd]);
                                        $availPcs = floatval($avail[0]->avail_qty_pcs ?? 0);
                                    }

                                    if ($availPcs > 0) {
                                        $results[$row->wo_no] = "{$row->wo_no} | {$row->itm_cd} ({$row->itm_type})";

                                        if (count($results) >= 50) {
                                            break;
                                        }
                                    }
                                }

                                return $results;
                            })                            ->getOptionLabelUsing(function ($value) {
                                if (!$value) {
                                    return null;
                                }

                                $row = WorkOrder::query()
                                    ->join('itm_tbl', 'wo_tbl.itm_cd', '=', 'itm_tbl.itm_cd')
                                    ->where('wo_tbl.wo_no', $value)
                                    ->first();

                                return $row ? "{$row->itm_cd} ({$row->itm_type})" : null;
                            })
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $component, $livewire) {

                                if (!$state) {
                                    return;
                                }

                                $wo = WorkOrder::where('wo_no', $state)->first();
                                if (!$wo) {
                                    return;
                                }

                                $item = Item::where('itm_cd', $wo->itm_cd)->first();

                                $set('wo_no', $wo->wo_no);
                                $set('itm_cd', $wo->itm_cd);
                                $set('itm_nm', $item ? "{$item->itm_cd} ({$item->itm_type})" : null);

                                // The process/qty logic now runs here (it used to run on Process change)
                                if (!($livewire instanceof \Filament\Resources\Pages\CreateRecord)) {
                                    return;
                                }

                                $woNo   = $wo->wo_no;
                                $procCd = $get('proc_cd');
                                if (!$procCd) {
                                    $set('in_qty', null);
                                    return;
                                }

                                $checkValidFlg = \DB::select("CALL check_prdlog_proc(?, ?)", [$woNo, $procCd]);
                                $validFlg = floatval($checkValidFlg[0]->valid_flg ?? 0);

                                if ($validFlg != 1) {
                                    Notification::make()
                                        ->danger()
                                        ->title("Process cannot be new input because next process has already exist")
                                        ->send();

                                    $set('work_order_lookup', null);
                                    $set('wo_no', null);
                                    $set('itm_cd', null);
                                    $set('itm_nm', null);
                                    $set('seq_no', null);
                                    $set('mchn_cd', null);
                                    $set('cav', null);
                                    $set('avail_qty', null);
                                    $set('in_qty', null);
                                    $set('out_qty', null);
                                    $set('rwk_qty', null);
                                    $set('ng_qty', null);
                                    $set('ng_qty_pcs', null);
                                    $set('rmks', null);
                                    return;
                                }

                                $woProc = \App\Models\WorkOrderProcess::where('wo_no', $woNo)
                                    ->where('proc_cd', $procCd)
                                    ->first();

                                $seqNo = $woProc?->seq_no;
                                $cav   = $woProc->cav ?? 1;

                                $set('seq_no', $seqNo ?? 0);
                                $set('cav', $cav);

                                if ($seqNo === 1) {
                                    $set('avail_qty', $woProc?->shoot_qty ?? 0);
                                    $set('in_qty', $woProc?->shoot_qty ?? 0);
                                } else {
                                    $availableQty = \DB::select("CALL get_wo_available_qty(?, ?)", [$woNo, $procCd]);
                                    $qty = floatval($availableQty[0]->avail_qty_pcs ?? 0) / $cav;
                                    $set('avail_qty', $qty);
                                    $set('in_qty', $qty);
                                }
                            }),

                        Forms\Components\TextInput::make('wo_no')
                            ->label('WO Number')
                            ->readOnly(),
                        Forms\Components\TextInput::make('itm_nm')
                            ->readOnly(),
                        Forms\Components\Hidden::make('itm_cd'),
                        Forms\Components\Hidden::make('seq_no')
                            ->label('Seq No'),

                        Forms\Components\Select::make('mchn_cd')
                            ->label('Machine')
                            ->extraAttributes([
                                'class' => 'fi-input-wrp bg-yellow-100',
                            ])
                            ->options(function () {
                                return Machine::orderBy('dsc')
                                    ->get()
                                    ->mapWithKeys(fn ($mchn) => [
                                        $mchn->mchn_cd => "{$mchn->mchn_cd} - {$mchn->dsc} - {$mchn->mchn_nm}",
                                    ])
                                    ->toArray();
                            })
                            ->searchable(),
                    ]),

                Forms\Components\Section::make(null) // make('Production Time')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Group::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_time')
                                        ->label('Start Time')
                                        ->extraAttributes([
                                            'class' => 'fi-input-wrp bg-yellow-100',
                                        ]),
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('StartcurrentTime')
                                            ->visible(fn ($livewire) => ! $livewire instanceof \Filament\Resources\Pages\ViewRecord)
                                            ->label('Current Time')
                                            ->color('primary')
                                            ->button()
                                            ->action(function (callable $set) {
                                                $set('start_time', now()->format('Y-m-d H:i:s'));
                                            }),
                                    ]),
                            ]),
                        Forms\Components\Group::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('end_time')
                                    ->label('End Time')
                                    ->required()
                                    ->extraAttributes([
                                        'class' => 'fi-input-wrp bg-yellow-100',
                                    ]),   
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('EndcurrentTime')
                                        ->visible(fn ($livewire) => ! $livewire instanceof \Filament\Resources\Pages\ViewRecord)    
                                        ->label('Current Time')
                                        ->color('primary')
                                        ->button()
                                        ->action(function (callable $set) {
                                            $set('end_time', now()->format('Y-m-d H:i:s'));
                                        }),
                                ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Production Qty')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('cav')
                            ->label('Cavity')
                            ->readOnly(),
                        Forms\Components\TextInput::make('avail_qty')
                            ->label('Qty Available (Panel)')
                            ->visible(false),
                        Forms\Components\TextInput::make('in_qty')
                            ->label('Qty Input (Panel)')                    
                            ->numeric()
                            ->readOnly(),                    
                        Forms\Components\TextInput::make('out_qty')
                            ->label('Qty OK (Panel)')
                            ->required()
                            ->extraAttributes([
                                'class' => 'fi-input-wrp bg-yellow-100',
                                'x-on:move-focus-out-qty.window' => "
                                    const input = \$el.querySelector('input');
                                    if (input) input.focus();
                                ",
                            ])
                            ->numeric()->default(null)->minValue(0)->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $component, $livewire) {
                                $inQty = floatval($get('in_qty') ?? 0);
                                $outQty = floatval($state ?? 0);
                                $rwkkQty = floatval($get('rwk_qty') ?? 0);
                                $ngQty = floatval($get('ng_qty') ?? 0);
                                if (($outQty + $rwkkQty + $ngQty) > $inQty) {                            
                                    Notification::make()
                                        ->danger()
                                        ->title("Out Qty is too large.")
                                        ->send();
                                    $set('out_qty', null);
                                    $component->getLivewire()->dispatch('focus-out-qty');    
                                }
                            }),
                ]),

                Forms\Components\Section::make('Rework and NG Qty')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('rwk_qty')
                            ->label('Qty Rework (Panel)')
                            ->extraAttributes([
                                'class' => 'fi-input-wrp bg-yellow-100',
                                'x-on:move-focus-rwk-qty.window' => "
                                    const input = \$el.querySelector('input');
                                    if (input) input.focus();
                                ",
                            ])                    
                            ->numeric()
                            ->default(0)
                            ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                            ->minValue(0)
                            ->reactive()                    
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $component, $livewire) {
                                $inQty = floatval($get('in_qty') ?? 0);
                                $outQty = floatval($get('out_qty') ?? 0);
                                $rwkkQty = floatval($state ?? 0);
                                $ngQty = floatval($get('ng_qty') ?? 0);

                                if (($outQty + $rwkkQty + $ngQty) > $inQty) {                            
                                    Notification::make()
                                        ->danger()
                                        ->title("Rework Qty is too large.")
                                        ->send();

                                    $set('rwk_qty', 0);    
                                    $component->getLivewire()->dispatch('focus-rwk-qty');
                                }
                            }),                   

                        Forms\Components\TextInput::make('ng_qty')
                            ->label('Qty NG (Panel)')
                            ->extraAttributes([
                                'class' => 'fi-input-wrp bg-yellow-100',
                                'x-on:move-focus-ng-qty.window' => "
                                    const input = \$el.querySelector('input');
                                    if (input) input.focus();
                                ",
                            ])                       
                            ->numeric()
                            ->default(0)
                            ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                            ->minValue(0)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get, $component, $livewire) {
                                $inQty = floatval($get('in_qty') ?? 0);
                                $outQty = floatval($get('out_qty') ?? 0);
                                $rwkkQty = floatval($get('rwk_qty') ?? 0);
                                $ngQty = floatval($state ?? 0);

                                if (($outQty + $rwkkQty + $ngQty) > $inQty) {                            
                                    Notification::make()
                                        ->danger()
                                        ->title("NG Qty is not valid.")
                                        ->send();

                                    $set('ng_qty', 0);
                                    $component->getLivewire()->dispatch('focus-ng-qty');    
                                }
                            }),

                        Forms\Components\TextInput::make('ng_qty_pcs')
                            ->label('Qty NG (Pcs)')
                            ->extraAttributes([
                                'class' => 'fi-input-wrp bg-yellow-100',
                                'x-on:move-focus-ng-qty-pcs.window' => "
                                    const input = \$el.querySelector('input');
                                    if (input) input.focus();
                                ",
                            ])                      
                            ->numeric()
                            ->default(0)
                            ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                            ->minValue(0)
                            ->reactive(),                           
                    ]),

                Forms\Components\Textarea::make('rmks')
                    ->label('Remarks')
                    ->extraAttributes([
                        'class' => 'fi-input-wrp bg-yellow-100',
                    ])                       
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('rmks_rwk')
                    ->label('Remarks or Rework')
                    ->visible(false)
                    ->extraAttributes([
                        'class' => 'fi-input-wrp bg-yellow-100',
                    ])                       
                    ->columnSpanFull(),

            Forms\Components\TextInput::make('emp_id')
                ->label('Employee ID')
                ->readOnly()
                ->required()
                ->default(function () {
                    $user = \Filament\Facades\Filament::auth()->user();
                    if ($user) {
                        $emp = \App\Models\Employee::leftJoin('users', 'empl_tbl.email', '=', 'users.email')
                            ->where('users.email', $user->email)
                            ->select('empl_tbl.emp_id')
                            ->first();    
                        if ($emp)
                        {   return $emp?->emp_id;  }    
                        else
                        {   return null;    }                               
                    }
                    else
                    {
                        return null;
                    }
                })    

        ]);
    
    }

    public static function table(Table $table): Table
    {
        $user = Filament::auth()->user();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wo_no')
                    ->label('WO No')
                    ->searchable(),
                Tables\Columns\TextColumn::make('itm_cd')
                    ->label('Part No')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.itm_nm')
                    ->label('Customer P/N')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proc_cd')
                    ->label('Process Code')
                    ->formatStateUsing(function ($state, $record) {
                        return $state . ' - ' . ($record->process->proc_nm ?? '');
                    })                    
                    ->searchable(),
                Tables\Columns\TextColumn::make('mchn_cd')
                    ->label('Machine Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emp_id')
                    ->label('Employee ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start Time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cav')
                    ->label('Cavity')
                    ->numeric(),                    
                Tables\Columns\TextColumn::make('avail_qty')
                    ->label('Available Qty (Panel)')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                        
                Tables\Columns\TextColumn::make('in_qty')
                    ->label('In Qty (Panel)')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),    
                Tables\Columns\TextColumn::make('out_qty')
                    ->label('Out Qty (Panel)')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),    
                Tables\Columns\TextColumn::make('rwk_qty')
                    ->label('Rework Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('ng_qty')
                    ->label('NG Qty Panel')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn($state) => number_format($state ?? 0, 0)),                       
                Tables\Columns\TextColumn::make('ng_qty_pcs')
                    ->label('NG Qty Pcs')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn($state) => number_format($state ?? 0, 0)),   
                Tables\Columns\TextColumn::make('detail_ng')
                    ->label('Detail NG')
                    ->getStateUsing(function ($record) {
                        if (empty($record->ng_qty) && empty($record->ng_qty_pcs)) {
                            return '';
                        }
                        $sumDetail = DB::table('prdng_tbl')
                            ->where('id_prd', $record->id)
                            ->sum('ng_qty');
                        return ((($record->ng_qty ?? 0) * ($record->cav ?? 0)) + ($record->ng_qty_pcs ?? 0)) == $sumDetail ? 'TRUE' : 'FALSE';
                    })
                    ->color(fn ($state) => $state === 'TRUE' ? 'success' : 'danger')
                    ->sortable(), 
                ])            
            // ->filters(self::getTableFilters())
            ->filters([])   
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible($user->hasRole(['admin','production'])),
            ])                        
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible($user->hasRole(['admin','production'])),
                ]),
            ])
            ->recordClasses(function ($record) {
                if ($record->ng_qty > 0) {
                    return 'bg-red-100 dark:bg-red-900';
                }

                if ($record->rwk_qty > 0) {
                    return 'bg-yellow-100 dark:bg-yellow-900';
                }
                return '';
            });

            //->recordUrl(
            //    fn ($record) =>
            //        ProductionLogResource::getUrl('view', ['record' => $record])
            //);                          
    }    

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->orderBy('updated_at', 'desc');

        // $user = Filament::auth()->user();
        $user = auth()->user();

        // Admin role can see ALL data
        if ($user && $user->hasRole('admin')) {
            return $query;
        }

        // Non-admin: limit to their emp_id
        $emp = \App\Models\Employee::leftJoin('users', 'empl_tbl.email', '=', 'users.email')
            ->where('users.email', $user->email)
            ->select('empl_tbl.emp_id')
            ->first();

        if ($emp) {
            return $query->where('emp_id', $emp->emp_id);
        }

        // If user has no employee record, show nothing
        return $query->whereRaw('1 = 0');
    }


    public static function getTableFilters(): array    
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
                    return $data['itm_cd'] ? "Part No : {$data['itm_cd']}" : null;
                }),                  
        ];
    }    

    public static function getRelations(): array
    {
        return [
            RelationManagers\RwkDetailsRelationManager::class,
            RelationManagers\NgDetailsRelationManager::class,            
        ];  
    }    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductionLogs::route('/'),
            'create' => Pages\CreateProductionLog::route('/create'),
            'edit' => Pages\EditProductionLog::route('/{record}/edit'),
            'view' => Pages\ViewProductionLog::route('/{record}'),
        ];
    }
}
