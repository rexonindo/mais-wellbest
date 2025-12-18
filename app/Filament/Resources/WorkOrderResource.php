<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\WorkOrderResource\Pages;
use App\Models\WorkOrder;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkOrderResource extends BaseResource
{
    protected static ?string $model = WorkOrder::class;
    protected static ?string $navigationGroup = 'Production Planning';
    protected static ?string $navigationLabel = 'Work Orders';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';   
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('wo_no')
                ->label('WO No')
                ->maxLength(50)
                ->required(),  
            Forms\Components\Select::make('itm_cd')
                ->label('Part No')
                ->options(function () {
                    return Item::orderBy('itm_nm')
                    ->get()
                    ->mapWithKeys(fn ($item) => [
                        $item->itm_cd => "{$item->itm_cd} - {$item->itm_nm}",
                    ])
                    ->toArray();
                })
                ->searchable()
                ->getSearchResultsUsing(function (string $search) {
                    return Item::query()
                    ->where('itm_cd', 'like', "%{$search}%")
                    ->orWhere('itm_nm', 'like', "%{$search}%")
                    ->limit(50)
                    ->get()
                    ->mapWithKeys(fn ($item) => [
                        $item->itm_cd => "{$item->itm_cd} - {$item->itm_nm}",
                    ])
                    ->toArray();
                })
                ->getOptionLabelUsing(function ($value): ?string {
                    $item = Item::where('itm_cd', $value)->first();
                    return $item ? "{$item->itm_cd} - {$item->itm_nm}" : null;
                }),
            Forms\Components\TextInput::make('po_no')
                ->label('PO Number')
                ->maxLength(50),              
            Forms\Components\DatePicker::make('req_dt')->label('Request Date'),     
            Forms\Components\TextInput::make('plan_qty')->numeric()->label('Planned Quantity'),
            Forms\Components\TextInput::make('plan_qty_raw')
                    ->label('Raw Material Qty ')
                    ->readOnly(),               
            Forms\Components\TextInput::make('plan_qty_pnl')
                    ->label('Panel Qty')
                    ->readOnly(),          
            Forms\Components\DatePicker::make('start_dt')->label('Start Date'),
            Forms\Components\DatePicker::make('end_dt')->label('End Date'),
            Forms\Components\Select::make('tool_cd')
                ->label('Tooling Code')
                ->options(function (callable $get) {
                    $itmCd = $get('itm_cd');
                    if (!$itmCd) {
                        return [];
                    }

                    return \App\Models\ToolingCavity::where('itm_cd', $itmCd)
                        ->orderBy('tool_cd')
                        ->pluck('tool_cd', 'tool_cd')
                        ->toArray();
                })
                ->reactive()
                ->required()
                ->placeholder('Select Tooling')
                ->searchable(),            
            Forms\Components\Select::make('stats')
                ->options([
                    'Planned' => 'Planned',
                    'In Progress' => 'In Progress',
                    'Completed' => 'Completed',
                    'Cancelled' => 'Cancelled',
                ])
                ->default('Planned'),              
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wo_no')
                    ->label('Wo No')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('itm_cd')
                    ->label('Part No')
                    ->sortable()
                    ->searchable(),                    
                Tables\Columns\TextColumn::make('item.itm_nm')->label('Customer P/N'),
                Tables\Columns\TextColumn::make('po_no')
                    ->label('PO Number')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('req_dt')->label('Request Date'),
                Tables\Columns\TextColumn::make('plan_qty')
                    ->label('Plan Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),
                Tables\Columns\TextColumn::make('plan_qty_pnl')
                    ->label('Pannel Qty')
                    ->numeric()
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0, 0)),                

                Tables\Columns\TextColumn::make('start_dt')->label('Start Date'),
                Tables\Columns\TextColumn::make('end_dt')->label('End Date'),
                Tables\Columns\TextColumn::make('tool_cd')->label('Tooling Code'),
                Tables\Columns\BadgeColumn::make('stats')
                    ->colors([
                        'primary' => 'Planned',
                        'warning' => 'In Progress',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),
            ])
            ->filters([])         
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printLabel')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->url(fn (WorkOrder $record) => route('workorder.print-label', $record))
                    ->openUrlInNewTab(),                
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
            ->orderBy('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
