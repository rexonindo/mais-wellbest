<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcessResource\Pages;
use App\Filament\BaseResource;
use App\Models\Process;
use App\Models\Department;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Resources\Form;
use Filament\Resources\Table;

class ProcessResource extends BaseResource
{
    protected static ?string $model = Process::class;
    protected static ?string $navigationGroup = 'Process & Flow';
    protected static ?string $navigationLabel = 'Process';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 1;            

    // NOTE: correct type-hint uses Forms\Form and returns Forms\Form
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('proc_cd')
                    ->label('Process Code')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('proc_nm')
                    ->label('Process Name')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Select::make('dept_cd')
                    ->label('Department')
                    ->options(fn () => Department::orderBy('dept_nm')->pluck('dept_nm', 'dept_cd')->toArray())
                    ->searchable()
                    ->nullable(),

                Forms\Components\TextInput::make('std_time')
                    ->label('Standard Time (min)')
                    ->numeric()
                    ->suffix('min'),
            ]);
    }

    // NOTE: correct type-hint uses Tables\Table and returns Tables\Table
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('proc_cd')->label('Process Code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('proc_nm')->label('Process Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('department.dept_nm')->label('Department'),
                Tables\Columns\TextColumn::make('std_time')->label('Std Time (min)'),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListProcesses::route('/'),
            'create' => Pages\CreateProcess::route('/create'),
            'edit' => Pages\EditProcess::route('/{record}/edit'),
        ];
    }
}
