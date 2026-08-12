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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', [
            'record' => $this->record,
        ]);
    }    

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Save the entered values
        $this->savedData = $data;

        return $data;
    }
     
    protected function afterCreate(): void
    {
        $woNo = $this->record->wo_no;
        $user = Auth::user()->name ?? 'system';

        DB::statement('CALL gen_wo_process(?, ?)', [$woNo, $user]);

        Notification::make()
            ->title('Work Order Created Successfully')
            ->body("Process for WO {$woNo} generated.")
            ->success()
            ->send();

        // Refill previous values
        $data = $this->savedData;

        // Clear only fields you want
        $data['wo_no'] = '';
        $data['stats'] = 'Planned';

        $this->form->fill($this->savedData);

    }

}
