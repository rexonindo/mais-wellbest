<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\ProcessResource\Pages;
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

                Forms\Components\TextInput::make('wip_sfx')
                    ->label('WIP Suffix')
                    ->maxLength(50)
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
            ->headerActions([
                Tables\Actions\Action::make('printAllLabels')
                    ->label('Print All Labels')
                    ->icon('heroicon-o-printer')
                    ->url(fn () => route('process.print-multiple-labels', [
                        'ids' => Process::pluck('id')->implode(','),
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('proc_cd')
                    ->label('Process Code')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('proc_nm')
                    ->label('Process Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.dept_nm')->label('Department'),
                Tables\Columns\TextColumn::make('wip_sfx')
                    ->label('WIP Suffix')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('std_time')
                    ->label('Std Time (min)')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('printLabel')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Process $record) => route('process.print-label', $record))
                    ->openUrlInNewTab(),      
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('printLabels')
                        ->label('Print Labels')
                        ->icon('heroicon-o-printer')
                        ->action(function (array $records) {
                            // $records is a collection of selected Process models
                            // You can redirect to a route that generates PDFs for multiple processes
                            $ids = $records->pluck('id')->implode(',');
                            return redirect()->route('process.print-multiple-labels', ['ids' => $ids]);
                        })
                        ->requiresConfirmation(),
                ]),
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
