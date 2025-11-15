<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelHasRoleResource\Pages;
use App\Filament\BaseResource;
use App\Models\ModelHasRole;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ModelHasRoleResource extends BaseResource
{
    protected static ?string $model = ModelHasRole::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Administration';
    protected static ?string $navigationLabel = 'User Roles';
    protected static ?string $title = 'Manage User Roles';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole('admin');
    }        

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('model_id')
                ->label('User')
                ->options(User::pluck('email', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('role_id')
                ->label('Role')
                ->options(Role::pluck('name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Hidden::make('model_type')
                ->default('App\\Models\\User'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User Email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('role.name')
                    ->label('Role Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('model_type')
                    ->label('Model Type')
                    ->sortable(),
            ])
            ->filters([])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModelHasRoles::route('/'),
            'create' => Pages\CreateModelHasRole::route('/create'),
            'edit' => Pages\EditModelHasRole::route('/{record}/edit'),
        ];
    }
}
