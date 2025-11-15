<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\BaseResource;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends BaseResource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Employee';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';    
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'employee';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('emp_id')
                ->label('Employee ID')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(20),

            Forms\Components\TextInput::make('emp_nm')
                ->label('Employee Name')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->required()
                ->maxLength(100),                

            Forms\Components\TextInput::make('psition')
                ->label('Position')
                ->maxLength(100),

            Forms\Components\Select::make('dept_cd')
                ->label('Department')
                ->relationship('department', 'dept_nm')
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('shift_cd')
                ->label('Shift')
                ->relationship('shift', 'shift_nm')
                ->searchable()
                ->preload(),

            Forms\Components\Select::make('stats')
                ->label('Status')
                ->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ])
                ->default('Active'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emp_id')->label('ID')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('emp_nm')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('psition')->label('Position'),
                Tables\Columns\TextColumn::make('department.dept_nm')->label('Department')->sortable(),
                Tables\Columns\TextColumn::make('shift.shift_nm')->label('Shift'),
                Tables\Columns\BadgeColumn::make('stats')
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Inactive',
                    ]),
                Tables\Columns\TextColumn::make('updated_at')->dateTime('d-M-Y H:i')->label('Updated'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('stats')->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
