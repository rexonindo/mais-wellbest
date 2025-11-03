<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductRouteResource\Pages;
use App\Filament\Resources\ProductRouteResource\RelationManagers;
use App\Filament\BaseResource;
use App\Models\ProductRoute;
use App\Models\Process;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductRouteResource extends BaseResource
{
    protected static ?string $model = ProductRoute::class;
    protected static ?string $navigationGroup = 'Process & Flow';
    protected static ?string $navigationLabel = 'Process Flow';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';      
    protected static ?string $title = 'Manage Process Flow';
    protected static ?int $navigationSort = 3;        

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('itm_type')
                    ->label('P/N Type')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('seq_no')
                    ->label('Sequence No')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('proc_cd')
                    ->label('Process')
                    ->options(fn () => Process::orderBy('proc_nm')->pluck('proc_nm', 'proc_cd')->toArray())
                    ->searchable()
                    ->nullable(),                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('itm_type')
                    ->label('P/N Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seq_no')
                    ->label('Seq No')
                    ->numeric(),
                Tables\Columns\TextColumn::make('process.proc_nm')
                    ->label('Process Name')
                    ->searchable(),
            ])
            ->defaultSort('itm_type')
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
            ->orderBy('itm_type')
            ->orderBy('seq_no');
    }    

    public static function getTableFilters(): array    
    {
        return [
            Tables\Filters\Filter::make('itm_type')
                ->form([
                    Forms\Components\TextInput::make('itm_type')
                        ->label('P/N Type')
                        ->placeholder('Enter P/N Type'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['itm_type'], fn($q, $value) => $q->where('itm_type', 'like', "%{$value}%"));
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['itm_type'] ? "P/N Type: {$data['itm_type']}" : null;
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
            'index' => Pages\ListProductRoutes::route('/'),
            'create' => Pages\CreateProductRoute::route('/create'),
            'edit' => Pages\EditProductRoute::route('/{record}/edit'),
        ];
    }
}
