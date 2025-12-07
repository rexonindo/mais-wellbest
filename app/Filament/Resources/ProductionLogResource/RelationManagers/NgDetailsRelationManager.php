<?php

namespace App\Filament\Resources\ProductionLogResource\RelationManagers;
 
use App\Models\NG;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NgDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'ngDetails';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('ng_nm')
                ->label('NG Name')
                ->options(
                    NG::orderBy('ng_nm')->pluck('ng_nm', 'ng_nm')->toArray()
                )
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('ng_qty')
                ->label('NG Qty')
                ->numeric()
                ->minValue(0)
                ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ng_nm')->label('NG Name'),
                Tables\Columns\TextColumn::make('ng_qty')->label('Qty'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->before(function ($data, RelationManager $livewire) {
                        $this->validateNg($data, 0);
                        $data['id_prd'] = $livewire->ownerRecord->id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($data, RelationManager $livewire, $record) {
                        // The record being edited
                        $NgQty = $record->ng_qty;
                        $this->validateNg($data, $NgQty);
                    }),

                Tables\Actions\DeleteAction::make(),
            ]);
    }

    /**
     * Validate NG Qty
     */
    private function validateNg(array $data, float $NgQty): void
    {
        $prd = $this->ownerRecord;
        $editingId = $prd->id;
        $originalNGQty = 0;
        if ($NgQty) {
            $originalNGQty = $NgQty;
        }        
        // Sum of other NG rows
        $currentSum = DB::table('prdng_tbl')
            ->where('id_prd', $prd->id)
            ->when($editingId, fn($q) =>
                $q->where('id', '!=', $editingId)
            )
            ->sum('ng_qty');

        $newQty = floatval($data['ng_qty']);
        $newTotal = $currentSum + $newQty - $originalNGQty;
        // Compare against allowed
        if ($newTotal > $prd->ng_qty) {

            Notification::make()
                ->danger()
                ->title('NG Quantity Exceeded')
                ->body("
                    Allowed: {$prd->ng_qty}<br>
                    Total Qty Input : {$newTotal} (EXCEEDS)
                ")
                ->send();

            throw ValidationException::withMessages([
                'ng_qty' => 'NG exceeds allowed limit.',
            ]);
        }
    }
}
