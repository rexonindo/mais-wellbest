<?php

namespace App\Filament\Resources\ProductionLogResource\RelationManagers;
 
use App\Models\NG;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RwkDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'rwkDetails';
    protected static ?string $title = 'Rework Detail (Panel)';    

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('rwk_nm')
                ->label('Rework Name')
                ->options(
                    NG::orderBy('ng_nm')->pluck('ng_nm', 'ng_nm')->toArray()
                )
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('rwk_qty')
                ->label('Rework Qty (Panel)')
                ->numeric()
                ->minValue(0)
                ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rwk_nm')->label('Rework Name'),
                Tables\Columns\TextColumn::make('rwk_qty')->label('Qty (Panel)'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->before(function ($data, RelationManager $livewire) {
                        $this->validateRwk($data, 0);
                        $data['id_prd'] = $livewire->ownerRecord->id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($data, RelationManager $livewire, $record) {
                        // The record being edited
                        $RwkQty = $record->rwk_qty;
                        $this->validateRwk($data, $RwkQty);
                    }),

                Tables\Actions\DeleteAction::make(),
            ]);
    }

    /**
     * Validate RWK Qty
     */
    private function validateRwk(array $data, float $RwkQty): void
    {
        $prd = $this->ownerRecord;
        $editingId = $prd->id;
        // $cav = intval($prd->cav ?? 0);
        $RwkMstPanel = floatval($prd->rwk_qty ?? 0);

        $originalRwkQty = 0;
        if ($RwkQty) {
            $originalRwkQty = $RwkQty;
        }        
        // Sum of other Rwk Shoot sum
        $currentSumPanel = DB::table('prdrwk_tbl')
            ->where('id_prd', $prd->id)
            ->when($editingId, fn($q) =>
                $q->where('id', '!=', $editingId)
            )
            ->sum(DB::raw('IFNULL(rwk_qty, 0)'));
        $newQty = floatval(($data['rwk_qty'] ?? 0));
        $newTotal = $currentSumPanel + $newQty - $originalRwkQty;
        // Compare against allowed
        if ($newTotal > $RwkMstPanel) {
            Notification::make()
                ->danger()
                ->title('Rework Quantity Exceeded')
                ->body("
                    Allowed: {$prd->rwk_qty}<br>
                    Total Qty Input : {$newTotal} (EXCEEDS)
                ")
                ->send();
            throw ValidationException::withMessages([
                'rwk_qty' => 'Rework exceeds allowed limit.',
            ]);
        }
    }
}
