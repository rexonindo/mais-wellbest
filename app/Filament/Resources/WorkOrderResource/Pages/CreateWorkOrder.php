<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateWorkOrder extends CreateRecord
{
    protected static string $resource = WorkOrderResource::class;

    protected function afterCreate(): void
    {
        $this->record->refresh();

        if (method_exists($this, 'form') && $this->form) {
            $this->form->fill($this->record->toArray());
        }

        $dbValue = DB::table($this->record->getTable())
            ->where('id', $this->record->id)
            ->value('plan_qty_pnl');
/*
        Log::info('WorkOrder afterCreate refresh', [
            'id' => $this->record->id,
            'model_value' => $this->record->plan_qty_pnl,
            'db_value' => $dbValue,
        ]);

        Notification::make()
            ->title('Record refreshed from database')
            ->success()
            ->send();
*/            
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }    
}
