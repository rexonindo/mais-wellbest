<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\ProductRouteResource\Pages;
use App\Filament\Resources\ProductRouteResource\RelationManagers;
use App\Models\ProductRoute;
use App\Models\Process;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class ProductRouteResource extends BaseResource
{
    protected static ?string $model = ProductRoute::class;
    protected static ?string $navigationGroup = 'Process & Flow';
    protected static ?string $navigationLabel = 'Process Flow';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $title = 'Manage Process Flow';
    protected static ?int $navigationSort = 3;

    /**
     * Lookup for P/N Type: SELECT itm_type FROM mais_wellbest.itm_type_view
     */
    protected static function getItmTypeOptions(): array
    {
        return DB::table('mais_wellbest.itm_type_view')
            ->whereNotNull('itm_type')
            ->where('itm_type', '<>', '')
            ->distinct()
            ->orderBy('itm_type')
            ->pluck('itm_type', 'itm_type')
            ->toArray();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('itm_type')
                    ->label('P/N Type')
                    ->options(fn () => self::getItmTypeOptions())
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('seq_no')
                    ->label('Sequence No')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('proc_cd')
                    ->label('Process')
                    ->options(
                        fn () => \App\Models\Process::orderBy('proc_nm')
                            ->get()
                            ->mapWithKeys(fn ($p) => [$p->proc_cd => "{$p->proc_nm} ({$p->proc_cd})"])
                            ->toArray()
                    )
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
                    ->formatStateUsing(function ($state, $record) {
                        return $record->process
                            ? "{$record->process->proc_nm} ({$record->process->proc_cd})"
                            : null;
                    })
                    ->searchable(),
            ])
            ->defaultSort('itm_type')
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
            ->orderBy('itm_type')
            ->orderBy('seq_no');
    }

    public static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('itm_type')
                ->label('P/N Type')
                ->options(fn () => self::getItmTypeOptions())
                ->searchable()
                ->multiple(),
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