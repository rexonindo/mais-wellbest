<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditWorkOrder extends EditRecord
{
    protected static string $resource = WorkOrderResource::class;

    protected function afterSave(): void
    {
        // Refresh from DB to get trigger-updated value
        $this->record->refresh();

        // Refill the Filament form with latest data
        if (method_exists($this, 'form') && $this->form) {
            $this->form->fill($this->record->toArray());
        }

        // Optional diagnostic log
        $dbValue = DB::table($this->record->getTable())
            ->where('id', $this->record->id)
            ->value('plan_qty_pnl');
/*
        Log::info('WorkOrder afterSave refresh', [
            'id' => $this->record->id,
            'model_value' => $this->record->plan_qty_pnl,
            'db_value' => $dbValue,
        ]);

        // ✅ Filament v3 uses Notification class
        Notification::make()
            ->title('Record refreshed from database')
            ->success()
            ->send();
*/            
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }    

}
