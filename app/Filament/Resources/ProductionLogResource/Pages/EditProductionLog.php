<?php

namespace App\Filament\Resources\ProductionLogResource\Pages;

use App\Models\WorkOrder;
use App\Models\Item;
use App\Filament\Resources\ProductionLogResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditProductionLog extends EditRecord
{
    protected static string $resource = ProductionLogResource::class;
    protected static ?string $title = 'Production Log <Edit>';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }    

    public static function canAccess(array $parameters = []): bool
    {
        $user = Filament::auth()->user();
        return $user->hasAnyRole(['admin', 'production']);
    }    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {

                    $prdlogId = $this->record->id;

                    // Check if prdng_tbl has any detail for this production log
                    $count = DB::table('prdng_tbl')
                        ->where('id_prd', $prdlogId)
                        ->count();

                    if ($count > 0) {

                        // Show error notification
                        Notification::make()
                            ->danger()
                            ->title('Cannot delete Production Log')
                            ->body("There are NG Detail records linked to this Production Log.")
                            ->send();

                        // Stop deletion
                        throw ValidationException::withMessages([
                            'delete' => 'Cannot delete because NG Detail exists.',
                        ]);
                    }
                }),
        ];
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
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
            }
            /*
            if (!empty($data['proc_cd'])) {
                $shootQty = DB::table('wo_proc_tbl')
                    ->where('wo_no', $data['wo_no'])
                    ->where('proc_cd', $data['proc_cd'])
                    ->value('shoot_qty');

                if ($shootQty !== null) {
                    $data['in_qty'] = $shootQty;
                } else {
                    $data['in_qty'] = $data['in_qty'] ?? $workOrder->plan_qty ?? 0;
                }
            } else {
                $data['in_qty'] = $data['in_qty'] ?? $workOrder->plan_qty ?? 0;
            }
            */
        }
        return $data;
    }

    protected function beforeSave(): void
    {
        $record = $this->record;
        $data   = $this->data; // form data user submitted

        $AvailQty = floatval($data['avail_qty'] ?? 0);
        $InQty = floatval($data['in_qty'] ?? 0);
        if ($InQty > $AvailQty) {
            Notification::make()
                ->danger()
                ->title('Input Qty cannot larger than Available Qty')
                ->body("
                    Input Qty: {$InQty}<br>
                    Available Qty: {$AvailQty}
                ")
                ->send();
            throw ValidationException::withMessages([
                'in_qty' => "Data cannot be saved.",
            ]);
        }   

        $RsltQty = floatval($data['out_qty'] ?? 0) + floatval($data['ng_qty'] ?? 0) + floatval($data['rwk_qty'] ?? 0);
        if ($RsltQty > $InQty) {
            Notification::make()
                ->danger()
                ->title('Total Output, NG and Rework quantity cannot larger than input qty')
                ->body("
                    Input Qty: {$InQty}<br>
                    Total Out + NG + Rework Qty: {$RsltQty}
                ")
                ->send();
            throw ValidationException::withMessages([
                'in_qty' => "Data cannot be saved.",
            ]);
        }   

        // sum NG detail
        $sumNg = \DB::table('prdng_tbl')
            ->where('id_prd', $record->id)
            ->sum('ng_qty');

        $NgTotal = (floatval($data['ng_qty'] ?? 0) * floatval($data['cav'] ?? 0)) + floatval($data['ng_qty_pcs'] ?? 0);
        if ($NgTotal < $sumNg) {
            Notification::make()
                ->danger()
                ->title('NG Quantity less than detail NG')
                ->body("
                    Input NG Qty: {$data['ng_qty']}<br>
                    Total Detail NG: {$sumNg}
                ")
                ->send();
            throw ValidationException::withMessages([
                'ng_qty' => "NG Qty ({$data['ng_qty']}) cannot be less than total NG Details ({$sumNg}).",
            ]);
        }
    }

}
