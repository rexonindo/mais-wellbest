<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolingCavityResource\Pages;
use App\Filament\Resources\ProductRouteResource\RelationManagers;
use App\Models\ToolingCavity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToolingCavityResource extends Resource
{
    protected static ?string $model = ToolingCavity::class;
    protected static ?string $navigationGroup = 'Process & Flow';
    protected static ?string $navigationLabel = 'Tooling Cavity';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('itm_cd')
                ->label('Item Code')
                ->relationship('item', 'itm_cd')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('tool_cd')
                ->label('Tool Code')
                ->required()
                ->maxLength(50),

            Forms\Components\Select::make('proc_cd')
                ->label('Process')
                // ->options(fn () => Process::orderBy('proc_nm')->pluck('proc_nm', 'proc_cd')->toArray())
                ->options(
                    fn () => \App\Models\Process::orderBy('proc_nm')
                        ->get()
                        ->mapWithKeys(fn ($p) => [$p->proc_cd => "{$p->proc_nm} ({$p->proc_cd})"])
                        ->toArray()
                )                    
                ->searchable()
                ->nullable(),     

            Forms\Components\TextInput::make('cav')
                ->label('Cavity')
                ->numeric()
                ->minValue(1)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('itm_cd')->label('Item Code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tool_cd')->label('Tool Code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('process.proc_nm')
                    ->label('Process Name')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->process
                            ? "{$record->process->proc_nm} ({$record->process->proc_cd})"
                            : null;
                    })                    
                    ->searchable(),
                Tables\Columns\TextColumn::make('cav')->label('Cavity'),
            ])
            ->filters(self::getTableFilters())
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('itm_cd')
            ->orderBy('tool_cd')
            ->orderBy('proc_cd');
    }    

    public static function getTableFilters(): array    
    {
        return [
            Tables\Filters\Filter::make('itm_cd')
                ->form([
                    Forms\Components\TextInput::make('itm_cd')
                        ->label('Part No')
                        ->placeholder('Enter Part No'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['itm_cd'], fn($q, $value) => $q->where('itm_cd', 'like', "%{$value}%"));
                })
                ->indicateUsing(function (array $data): ?string {
                    return $data['itm_cd'] ? "Part No: {$data['itm_cd']}" : null;
                }),                     
        ];
    }   

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListToolingCavities::route('/'),
            'create' => Pages\CreateToolingCavity::route('/create'),
            'edit' => Pages\EditToolingCavity::route('/{record}/edit'),
        ];
    }
}
