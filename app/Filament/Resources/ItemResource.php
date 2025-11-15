<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Filament\BaseResource;
use App\Models\Item;
use App\Models\ProductRoute;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends BaseResource
{
    protected static ?string $model = Item::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Part Number';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';   
    protected static ?int $navigationSort = 5;      

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('itm_cd')
                    ->label('Part No')
                    ->required()
                    ->maxLength(50),                
                Forms\Components\TextInput::make('itm_nm')
                    ->label('Customer P/N')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Select::make('itm_type')
                    ->label('P/N Type')
                    ->options(fn () => ProductRoute::orderBy('itm_type')->pluck('itm_type', 'itm_type')->toArray())
                    ->searchable()
                    ->nullable(),

                Forms\Components\Toggle::make('fg_flg')
                    ->label('Finished Goods')
                    ->inline(false),
                Forms\Components\TextInput::make('uom')
                    ->label('UOM')
                    ->maxLength(20),
                Forms\Components\TextInput::make('std_rate')
                    ->label('Standard Rate')
                    ->numeric(),

                Forms\Components\TextInput::make('cavity')
                    ->label('Cavity')
                    ->numeric(),
                    
                Forms\Components\Select::make('cust_cd')
                    ->label('Customer')
                    ->options(function () {
                        return Customer::orderBy('cust_nm')
                        ->get()
                        ->mapWithKeys(fn ($cust) => [
                            $cust->cust_cd => "{$cust->cust_cd} - {$cust->cust_nm}",
                        ])
                        ->toArray();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Customer::query()
                        ->where('cust_cd', 'like', "%{$search}%")
                        ->orWhere('cust_nm', 'like', "%{$search}%")
                        ->limit(50)
                        ->get()
                        ->mapWithKeys(fn ($cust) => [
                            $cust->cust_cd => "{$cust->cust_cd} - {$cust->cust_nm}",
                        ])
                        ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $cust = Customer::where('itm_cd', $value)->first();
                        return $cust ? "{$cust->cust_cd} - {$cust->cust_nm}" : null;
                    }),                
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('itm_cd')
                    ->label('Part No')
                    // ->required()
                    ->searchable(),
                Tables\Columns\TextColumn::make('itm_nm')
                    ->label('Customer P/N')
                    // ->required()
                    ->searchable(),
                Tables\Columns\TextColumn::make('itm_type')
                    ->label('P/N Type')
                    // ->required()
                    ->searchable(),                    
                Tables\Columns\TextColumn::make('fg_flg')
                    ->label('Finished Goods'),
                Tables\Columns\TextColumn::make('uom')
                    ->label('UOM')
                    ->searchable(),
                Tables\Columns\TextColumn::make('std_rate')
                    ->label('Standard Rate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cavity')
                    ->label('Cavity'),    
                Tables\Columns\TextColumn::make('customer.cust_nm')
                    ->label('Customer Name')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->customer
                            ? "{$record->customer->cust_nm} ({$record->customer->cust_cd})"
                            : null;
                    })                    
                    ->searchable(),                    

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getModelLabel(): string
    {
        return 'Product';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Products';
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
