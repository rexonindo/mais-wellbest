<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NgResource\Pages;
use App\Models\NG;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class NgResource extends Resource
{
    protected static ?string $model = Ng::class;

    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'NG List';
    protected static ?string $pluralModelLabel = 'NG';
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';    
    protected static ?string $modelLabel = 'NG';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ng_nm')
                    ->label('NG Name')
                    ->required()
                    ->maxLength(50),

                Forms\Components\Textarea::make('dsc')
                    ->label('Description')
                    // ->required()
                    ->maxLength(200),

                Forms\Components\TextInput::make('location')
                    ->label('Location')
                    // ->required()
                    ->maxLength(50),                    
                /*
                Forms\Components\TextInput::make('created_by')
                    ->default(auth()->user()?->name)
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Hidden::make('updated_by')
                    ->default(auth()->user()?->name),
                */
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ng_nm')
                    ->label('NG Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('dsc')
                    ->label('Description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),    

                Tables\Columns\TextColumn::make('created_by')
                    ->label('Created By'),             
                Tables\Columns\TextColumn::make('updated_by')
                    ->label('Updated By'), 
                /*    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                */
            ])
            ->defaultSort('ng_nm')
            ->filters([
                // Add filter if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // No relations yet
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNgs::route('/'),
            'create' => Pages\CreateNg::route('/create'),
            'edit'   => Pages\EditNg::route('/{record}/edit'),
        ];
    }
}
