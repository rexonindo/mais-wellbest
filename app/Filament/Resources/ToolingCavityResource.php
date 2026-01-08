<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\ToolingCavityResource\Pages;
use App\Filament\Resources\ProductRouteResource\RelationManagers;
use App\Models\ToolingCavity;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToolingCavityResource extends BaseResource
{
    protected static ?string $model = ToolingCavity::class;
    protected static ?string $navigationGroup = 'Process & Flow';
    protected static ?string $navigationLabel = 'Tooling Cavity';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $title = 'Tooling Cavity';    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('itm_cd')
                ->label('Part No')
                ->relationship('item', 'itm_cd')
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function (Set $set) {
                    $set('proc_cd', null);
                })                
                ->default(fn () => session('tooling_cavity.itm_cd')),

            Forms\Components\TextInput::make('tool_cd')
                ->label('Tool Code')
                ->required()
                ->maxLength(50)
                ->default(fn () => session('tooling_cavity.tool_cd')),                

            Forms\Components\Select::make('proc_cd')
                ->label('Process')
                ->required()
                ->options(
                    fn () => \App\Models\Process::orderBy('proc_nm')
                        ->get()
                        ->mapWithKeys(fn ($p) => [$p->proc_cd => "{$p->proc_nm} ({$p->proc_cd})"])
                        ->toArray()
                )                    
                ->searchable()
                ->options(function (Get $get) {
                    $itmCd = $get('itm_cd');

                    if (! $itmCd) {
                        return [];
                    }

                    return DB::table('itm_proc_view')
                        ->where('itm_cd', $itmCd)
                        ->orderBy('seq_no')
                        ->get()
                        ->mapWithKeys(fn ($row) => [
                            $row->proc_cd => "{$row->proc_nm} ({$row->proc_cd})",
                        ])
                        ->toArray();
                })
                ->disabled(fn (Get $get) => blank($get('itm_cd')))
                ->autofocus(fn () => session()->pull('tooling_cavity.focus_proc_cd', false)),     

            Forms\Components\TextInput::make('cav')
                ->label('Cavity')
                ->required()
                ->numeric()
                ->minValue(1),
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
                Tables\Actions\DeleteAction::make(),
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
