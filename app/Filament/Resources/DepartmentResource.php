<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Filament\BaseResource;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends BaseResource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Departments';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';   
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('dept_cd')
                ->label('Department Code')
                ->required()
                ->maxLength(20),
            Forms\Components\TextInput::make('dept_nm')
                ->label('Department Name')
                ->required()
                ->maxLength(100),
            Forms\Components\Textarea::make('descrp')
                ->label('Description')
                ->rows(3),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dept_cd')->label('Code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('dept_nm')->label('Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('descrp')->label('Description')->limit(50),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
