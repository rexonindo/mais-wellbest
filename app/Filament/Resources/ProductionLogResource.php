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
            ->schema([
                Forms\Components\Select::make('wo_no')
                    ->label('Work Order No')                    
                    ->options(function () {
                        return \App\Models\WorkOrder::query()
                            ->whereIn('wo_no', function ($query) {
                                $query->select('wo_no')
                                      ->from('wo_proc_tbl'); 
                            })
                            ->orderBy('wo_no')
                            ->pluck('wo_no', 'wo_no')
                            ->toArray();
                    })
                    ->searchable()
                    ->reactive()
                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                        // ✅ Run when editing (form loads existing record)
                        $workOrder = \App\Models\WorkOrder::where('wo_no', $state)->first();
                        if ($workOrder) {
                            $item = \App\Models\Item::where('itm_cd', $workOrder->itm_cd)->first();

                            if ($item) {
                                $set('itm_cd', $item->itm_cd);
                                $set('itm_nm', $item->itm_cd . ' (' . $item->itm_type . ')');
                            } else {
                                $set('itm_cd', $workOrder->itm_cd);
                                $set('itm_nm', $workOrder->itm_cd);
                            }
                        }
                        $procCd = $get('proc_cd'); 
                        $query = \App\Models\WorkOrderProcess::where('wo_no', $state);
                        if ($procCd) {
                            $query->where('proc_cd', $procCd);
                        }
                        $workOrderProcess = $query->first();
                        if ($workOrderProcess) {
                            $set('in_qty', $workOrderProcess->shoot_qty ?? null);
                        } else {
                            $set('in_qty', null);
                        }
                    })                    
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $workOrder = \App\Models\WorkOrder::where('wo_no', $state)->first();
                        if ($workOrder) {
                            $item = \App\Models\Item::where('itm_cd', $workOrder->itm_cd)->first();

                            if ($item) {
                                $set('itm_cd', $item->itm_cd);
                                $set('itm_nm', $item->itm_cd . ' (' . $item->itm_type . ')');
                            } else {
                                $set('itm_cd', $workOrder->itm_cd);
                                $set('itm_nm', $workOrder->itm_cd);
                            }
                        } else {
                            $set('itm_cd', null);
                            $set('itm_nm', null);
                            $set('in_qty', null);
                        }
                        $procCd = $get('proc_cd'); 
                        $query = \App\Models\WorkOrderProcess::where('wo_no', $state);
                        if ($procCd) {
                            $query->where('proc_cd', $procCd);
                        }
                        $workOrderProcess = $query->first();
                        if ($workOrderProcess) {
                            $set('in_qty', $workOrderProcess->shoot_qty ?? null);
                        } else {
                            $set('in_qty', null);
                        }

                    })
                    ->required(),

                // Hidden real item code (for saving)
                Forms\Components\Hidden::make('itm_cd')
                    ->label('Part No'),

                // Display item name (readonly)
                Forms\Components\TextInput::make('itm_nm')
                    ->label('Customer P/N')
                    ->readOnly(),

                Forms\Components\Select::make('proc_cd')
                    ->label('Process')
                    ->options(function (callable $get) {
                        $woNo = $get('wo_no');
                        if (!$woNo) return [];

                        $workOrder = \App\Models\WorkOrder::where('wo_no', $woNo)->first();
                        if (!$workOrder) return [];

                        $item = \App\Models\Item::where('itm_cd', $workOrder->itm_cd)->first();
                        if (!$item) return [];

                        return \DB::table('prdroute_tbl')
                            ->join('proc_tbl', 'prdroute_tbl.proc_cd', '=', 'proc_tbl.proc_cd')
                            ->where('prdroute_tbl.itm_type', $item->itm_type)
                            ->orderBy('prdroute_tbl.seq_no')
                            ->get()
                            ->mapWithKeys(fn ($r) => [$r->proc_cd => "{$r->proc_cd} - {$r->proc_nm}"])
                            ->toArray();
                    })
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $woNo = $get('wo_no');
                        $procCd = $state; // selected process code

                        if (! $woNo || ! $procCd) {
                            $set('in_qty', null);
                            return;
                        }
                        
                        $SeqNoTbl = \App\Models\WorkOrderProcess::where('wo_no', $woNo)
                                ->where('proc_cd', $procCd)
                                ->first();
                        $SeqNo = $SeqNoTbl?->seq_no;           
                        
                        if ( $SeqNo === 1 ) {
                            $workOrderProcess = \App\Models\WorkOrderProcess::where('wo_no', $woNo)
                                 ->where('proc_cd', $procCd)
                                 ->first();   
                            $set('avail_qty', $workOrderProcess?->shoot_qty ?? 0); 
                            $set('in_qty', $workOrderProcess?->shoot_qty ?? 0); 
                        }
                        else
                        {
                            $AvailableQty = \DB::select("CALL get_wo_available_qty(?, ?)", [$woNo, $procCd]);
                            $set('avail_qty', $AvailableQty[0]->avail_qty_shoot ?? 0);  
                            $set('in_qty', $AvailableQty[0]->avail_qty_shoot ?? 0);      
                        }                        
                    })
                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                        $woNo = $get('wo_no');
                        $procCd = $state; // selected process code

                        if (! $woNo || ! $procCd) {
                            $set('in_qty', null);
                            return;
                        }
                        
                        $SeqNoTbl = \App\Models\WorkOrderProcess::where('wo_no', $woNo)
                                ->where('proc_cd', $procCd)
                                ->first();
                        $SeqNo = $SeqNoTbl?->seq_no;           
                        
                        if ( $SeqNo === 1 ) {
                            $workOrderProcess = \App\Models\WorkOrderProcess::where('wo_no', $woNo)
                                 ->where('proc_cd', $procCd)
                                 ->first();   
                            $set('avail_qty', $workOrderProcess?->shoot_qty ?? 0); 
                            $set('in_qty', $workOrderProcess?->shoot_qty ?? 0); 
                        }
                        else
                        {
                            $AvailableQty = \DB::select("CALL get_wo_available_qty(?, ?)", [$woNo, $procCd]);
                            $set('avail_qty', $AvailableQty[0]->avail_qty_shoot ?? 0);  
                            $set('in_qty', $AvailableQty[0]->avail_qty_shoot ?? 0);      
                        }                      
                    })
                    ->required(),

                Forms\Components\Select::make('mchn_cd')
                    ->label('Machine')
                   //  ->options(fn () => Machine::orderBy('mchn_nm')->pluck('mchn_nm', 'mchn_cd')->toArray())

                    ->options(function () {
                        return Machine::orderBy('dsc')
                        ->get()
                        ->mapWithKeys(fn ($mchn) => [
                            $mchn->mchn_cd => "{$mchn->dsc} - {$mchn->mchn_nm}",
                        ])
                        ->toArray();
                    })

                    ->searchable(),                    

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->label('Start Time'),
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
                    ])
                    ->columns(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('end_time')
                            ->label('End Time'),
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
                    ])
                    ->columns(2),   

                Forms\Components\TextInput::make('emp_id')
                    ->label('Employee ID')
                    ->default(function () {
                        $user = \Filament\Facades\Filament::auth()->user();
                        if (!$user) {
                            return null;
                        }
                        $emp = \App\Models\Employee::leftJoin('users', 'empl_tbl.email', '=', 'users.email')
                            ->where('users.email', $user->email)
                            ->select('empl_tbl.emp_id')
                            ->first();

                        return $emp?->emp_id;
                    })
                    ->afterStateHydrated(function (callable $set, $state) {
                        if (!$state) {
                            $user = \Filament\Facades\Filament::auth()->user();
                            if (!$user) return;

                            $emp = \App\Models\Employee::leftJoin('users', 'empl_tbl.email', '=', 'users.email')
                                ->where('users.email', $user->email)
                                ->select('empl_tbl.emp_id')
                                ->first();

                            if ($emp) {
                                $set('emp_id', $emp->emp_id);
                            }
                        }
                    })
                    ->readOnly() // optional: prevent editing
                    ->required(),

                Forms\Components\TextInput::make('avail_qty')
                    ->label('Qty Available (Shoot)')
                    ->numeric()
                    ->readOnly(),

                Forms\Components\TextInput::make('in_qty')
                    ->label('Qty Input (Shoot)')
                    ->numeric()->default(0)->minValue(0)->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $component) {
                        $availQty = $get('avail_qty');
                        if ($availQty !== null && $state > $availQty) {
                            Notification::make()
                                ->danger()
                                ->title("In Qty cannot be greater than Available Qty ($availQty)")
                                ->send();                            
                            $set('in_qty', null);
                            $component->getLivewire()->dispatch('focus-in-qty');
                        }
                    })
                    ->extraAttributes([
                        'x-on:move-focus-in-qty.window' => "
                            const input = \$el.querySelector('input');
                            if (input) input.focus();
                        ",
                    ]),
                    
                Forms\Components\TextInput::make('out_qty')
                    ->label('Qty Output (Shoot)')
                    ->numeric()->default(0)->minValue(0)->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $component) {
                        $inQty = $get('in_qty') ?? 0;
                        $outQty = $state ?? 0;
                        $rwkkQty = $get('rwk_qty') ?? 0;
                        $ngQty = $get('ng_qty') ?? 0;
                        if (($outQty + $rwkkQty + $ngQty) > $inQty) {                            
                            Notification::make()
                                ->danger()
                                ->title("Out Qty is too large.")
                                ->send();
                            $set('out_qty', null);
                            $component->getLivewire()->dispatch('focus-out-qty');    
                        }
                    })
                    ->extraAttributes([
                        'x-on:move-focus-out-qty.window' => "
                            const input = \$el.querySelector('input');
                            if (input) input.focus();
                        ",
                    ]),

                Forms\Components\TextInput::make('rwk_qty')
                    ->label('Qty Rework')
                    ->numeric()->default(0)->minValue(0)->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $component) {
                        $inQty = $get('in_qty') ?? 0;
                        $outQty = $get('out_qty') ?? 0;
                        $rwkkQty = $state ?? 0;
                        $ngQty = $get('ng_qty') ?? 0;
                        if (($outQty + $rwkkQty + $ngQty) > $inQty) {                            
                            Notification::make()
                                ->danger()
                                ->title("Rework Qty is too large.")
                                ->send();
                            $set('rwk_qty', null);    
                            $component->getLivewire()->dispatch('focus-rwk-qty');
                        }
                    })
                    ->extraAttributes([
                        'x-on:move-focus-rwk-qty.window' => "
                            const input = \$el.querySelector('input');
                            if (input) input.focus();
                        ",
                    ]),                    

                Forms\Components\TextInput::make('ng_qty')
                    ->label('Qty NG (Shoot)')
                    ->numeric()->default(0)->minValue(0)->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $component) {
                        $inQty = $get('in_qty') ?? 0;
                        $outQty = $get('out_qty') ?? 0;
                        $rwkkQty = $get('rwk_qty') ?? 0;
                        $ngQty = $state ?? 0;
                        if (($outQty + $rwkkQty + $ngQty) > $inQty) {                            
                            Notification::make()
                                ->danger()
                                ->title("NG Qty is too large.")
                                ->send();
                            $set('ng_qty', null);
                            $component->getLivewire()->dispatch('focus-ng-qty');    
                        }
                    })
                    ->extraAttributes([
                        'x-on:move-focus-ng-qty.window' => "
                            const input = \$el.querySelector('input');
                            if (input) input.focus();
                        ",
                    ]),                        

                Forms\Components\Textarea::make('rmks')
                    ->label('Remarks')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('in_qty')
                    ->label('In Qty (Shoot)')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),    
                Tables\Columns\TextColumn::make('out_qty')
                    ->label('Out Qty (Shoot)')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),    
                Tables\Columns\TextColumn::make('rwk_qty')
                    ->label('Rework Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('ng_qty')
                    ->label('NG Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn($state) => number_format($state ?? 0, 0)),   
                ])                
            ->filters(self::getTableFilters())
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible($user->hasRole('admin')),
            ])                        
            ->bulkActions([
                            Tables\Actions\BulkActionGroup::make([
                                Tables\Actions\DeleteBulkAction::make(),
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
                    })
            ->recordUrl(
                fn ($record) =>
                    ProductionLogResource::getUrl('view', ['record' => $record])
            );                          
    }    

    public static function getEloquentQuery(): Builder
    {
        $user = Filament::auth()->user();

        $query = parent::getEloquentQuery()
            ->orderBy('wo_no')
            ->orderBy('itm_cd')
            ->orderBy('start_time');

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
            //
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
