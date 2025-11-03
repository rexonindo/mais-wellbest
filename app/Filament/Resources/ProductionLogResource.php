<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionLogResource\Pages;
use App\Filament\Resources\ProductionLogResource\RelationManagers;
use App\Filament\BaseResource;
use App\Models\ProductionLog;
use App\Models\WorkOrder; 
use App\Models\Item; 
use App\Models\Machine; 
use App\Models\Employee; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->options(fn () => WorkOrder::orderBy('wo_no')->pluck('wo_no', 'wo_no')->toArray())
                    ->searchable()
                    ->reactive()
                    ->afterStateHydrated(function ($state, callable $set) {
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

                            $set('in_qty', $workOrder->plan_qty ?? null);
                        }
                    })                    
                    ->afterStateUpdated(function ($state, callable $set) {
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

                            $set('in_qty', $workOrder->plan_qty ?? null);
                        } else {
                            $set('itm_cd', null);
                            $set('itm_nm', null);
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
                        // Get the selected Work Order No
                        $woNo = $get('wo_no');
                        if (!$woNo) {
                            return [];
                        }

                        // Find the related work order and item type
                        $workOrder = \App\Models\WorkOrder::where('wo_no', $woNo)->first();
                        if (!$workOrder) {
                            return [];
                        }

                        $item = \App\Models\Item::where('itm_cd', $workOrder->itm_cd)->first();
                        if (!$item) {
                            return [];
                        }

                        // Lookup processes from prdroute_tbl
                        return \DB::table('prdroute_tbl')
                            ->join('proc_tbl', 'prdroute_tbl.proc_cd', '=', 'proc_tbl.proc_cd') // join to get proc_nm
                            ->where('prdroute_tbl.itm_type', $item->itm_type)
                            ->orderBy('prdroute_tbl.seq_no')
                            ->get()
                            ->mapWithKeys(fn ($r) => [$r->proc_cd => "{$r->proc_cd} - {$r->proc_nm}"])
                            ->toArray();
                    })
                    ->searchable()
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('mchn_cd')
                    ->label('Machine')
                    ->options(fn () => Machine::orderBy('mchn_nm')->pluck('mchn_nm', 'mchn_cd')->toArray())
                    ->searchable(),                    

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->label('Start Time'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('StartcurrentTime')
                                ->label('Current Time')
                                ->color('primary')
                                ->button()
                                ->action(function (callable $set) {
                                    // server-side current datetime, formatted for the DateTimePicker
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
                                ->label('Current Time')
                                ->color('primary')
                                ->button()
                                ->action(function (callable $set) {
                                    // server-side current datetime, formatted for the DateTimePicker
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

                Forms\Components\TextInput::make('in_qty')
                    ->label('Qty Input')
                    ->numeric(),
                Forms\Components\TextInput::make('out_qty')
                    ->label('Qty Output')
                    ->numeric(),
                Forms\Components\TextInput::make('ng_qty')
                    ->label('Qty NG')
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('rmks')
                    ->label('Remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
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
                    ->label('In Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),    
                Tables\Columns\TextColumn::make('out_qty')
                    ->label('Out Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),    
                Tables\Columns\TextColumn::make('ng_qty')
                    ->label('NG Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),    
            ])
            ->filters(self::getTableFilters())
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('wo_no')
            ->orderBy('itm_cd')
            ->orderBy('start_time');
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
        ];
    }
}
