<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateWorkOrder extends CreateRecord
{
    protected static string $resource = WorkOrderResource::class;
    protected static ?string $title = 'Work Order <List>';  

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }    
      
    protected function afterCreate(): void
    {
        $this->record->refresh();

        // 1. Get WO number and user
        $woNo = $this->record->wo_no;
        $user = Auth::user()->name ?? 'system';

        // 2. Call stored procedure
        DB::statement('CALL gen_wo_process(?, ?)', [$woNo, $user]);

        // 3. Optional: log for debugging
        \Log::info("gen_wo_process called for WO: $woNo by $user");

        // 4. Optional notification
        Notification::make()
            ->title('Work Order Created Successfully')
            ->body("Process for WO $woNo generated.")
            ->success()
            ->send();

        if (method_exists($this, 'form') && $this->form) {
            $this->form->fill($this->record->toArray());
        }

        $dbValue = DB::table($this->record->getTable())
            ->where('id', $this->record->id)
            ->value('plan_qty_pnl');
            
    }

}
