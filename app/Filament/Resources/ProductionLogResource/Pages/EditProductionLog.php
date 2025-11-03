<?php

namespace App\Filament\Resources\ProductionLogResource\Pages;

use App\Filament\Resources\ProductionLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\WorkOrder;
use App\Models\Item;

class EditProductionLog extends EditRecord
{
    protected static string $resource = ProductionLogResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // ✅ When editing, fill itm_nm based on existing wo_no
        if (!empty($data['wo_no'])) {
            $workOrder = WorkOrder::where('wo_no', $data['wo_no'])->first();
            if ($workOrder) {
                $item = Item::where('itm_cd', $workOrder->itm_cd)->first();
                if ($item) {
                    $data['itm_cd'] = $item->itm_cd;
                    $data['itm_nm'] = $item->itm_cd . ' (' . $item->itm_type . ')';
                } else {
                    $data['itm_cd'] = $workOrder->itm_cd;
                    $data['itm_nm'] = $workOrder->itm_cd;
                }

                $data['in_qty'] = $data['in_qty'] ?? $workOrder->plan_qty;
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


}
