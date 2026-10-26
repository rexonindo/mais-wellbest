<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\ItemGroupResource\Pages;
use App\Filament\Resources\ItemGroupResource\RelationManagers;
use App\Models\ItemGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemGroupResource extends BaseResource
{

    protected static ?string $model = ItemGroup::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Part Number Group';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';   
    protected static ?int $navigationSort = 5;  

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('itm_grp')
                    ->label('P/N Group')
                    ->required()
                    ->maxLength(50),                
                Forms\Components\TextInput::make('itm_type')
                    ->label('P/N Type')
                    ->required()
                    ->maxLength(50),                                    
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('itm_grp')
                    ->label('Part No Group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('itm_type')
                    ->label('Part No Type')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemGroups::route('/'),
            'create' => Pages\CreateItemGroup::route('/create'),
            'edit' => Pages\EditItemGroup::route('/{record}/edit'),
        ];
    }
}
