<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\MachineResource\Pages;
use App\Models\Machine;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MachineResource extends BaseResource
{
    protected static ?string $model = Machine::class;
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Machine';
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('mchn_cd')
                    ->label('Machine Code')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('mchn_nm')
                    ->label('Machine Name')
                    ->maxLength(100),

                Forms\Components\Select::make('dept_cd')
                    ->label('Department')
                    ->options(Department::pluck('dept_nm', 'dept_cd'))
                    ->searchable(),

                Forms\Components\TextInput::make('uom')
                    ->label('UOM')
                    ->maxLength(20),

                Forms\Components\TextInput::make('dsc')
                    ->label('Description')
                    ->maxLength(50),

                Forms\Components\Select::make('stats')
                    ->label('Status')
                    ->options([
                        'Running' => 'Running',
                        'Idle' => 'Idle',
                        'Maintenance' => 'Maintenance',
                        'Breakdown' => 'Breakdown',
                    ])
                    ->default('Idle')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mchn_cd')->label('Code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('mchn_nm')->label('Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('department.dept_nm')->label('Department'),
                Tables\Columns\TextColumn::make('uom')->label('UOM'),
                Tables\Columns\TextColumn::make('dsc')->label('Description'),
                Tables\Columns\BadgeColumn::make('stats')
                    ->colors([
                        'success' => 'Running',
                        'warning' => 'Idle',
                        'info' => 'Maintenance',
                        'danger' => 'Breakdown',
                    ]),
            ])
            ->defaultSort('mchn_cd')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printLabel')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Machine $record) => route('machine.print-label', $record))
                    ->openUrlInNewTab(),      
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
            'index' => Pages\ListMachines::route('/'),
            'create' => Pages\CreateMachine::route('/create'),
            'edit' => Pages\EditMachine::route('/{record}/edit'),
        ];
    }
}
