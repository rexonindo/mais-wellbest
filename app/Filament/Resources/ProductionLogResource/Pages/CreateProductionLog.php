<?php

namespace App\Filament\Resources\ProductionLogResource\Pages;

use App\Filament\Resources\ProductionLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use App\Models\WorkOrder;

class CreateProductionLog extends CreateRecord
{
    protected static string $resource = ProductionLogResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', [
            'record' => $this->record,
        ]);
    }
  
    public static function canAccess(array $parameters = []): bool
    {
        return true;
    }    

    #[On('focus-in-qty')]
    public function focusInQty()
    {
        $this->dispatch('move-focus-in-qty');
    }

    #[On('focus-out-qty')]
    public function focusOutQty()
    {
        $this->dispatch('move-focus-out-qty');
    }

    #[On('focus-rwk-qty')]
    public function focusRwkQty()
    {
        $this->dispatch('move-focus-rwk-qty');
    }   

    #[On('focus-ng-qty')]
    public function focusNgQty()
    {
        $this->dispatch('move-focus-ng-qty');
    }         

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $record = $this->record;
        $data   = $this->data; // form data user submitted
        $InQty = floatval($data['in_qty'] ?? 0);
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
        return $data;
    }  

    protected $listeners = [
        'work-order-barcode-scanned' => 'handleWorkOrderBarcodeScanned',
    ];

    public function handleWorkOrderBarcodeScanned($data): void
    {
        $barcode = trim($data['barcode'] ?? '');

        if ($barcode === '') {
            return;
        }

        $procCd = $this->data['proc_cd'] ?? null;

        if (!$procCd) {
            Notification::make()
                ->warning()
                ->title('Select Process first')
                ->send();

            return;
        }

        $wo = WorkOrder::query()
            ->join('wo_proc_tbl', 'wo_tbl.wo_no', '=', 'wo_proc_tbl.wo_no')
            ->where('wo_proc_tbl.proc_cd', $procCd)
            ->where('wo_tbl.wo_no', $barcode)
            ->select('wo_tbl.*')
            ->first();

        if (!$wo) {
            Notification::make()
                ->danger()
                ->title('Work Order not found')
                ->body("Barcode: {$barcode}")
                ->send();

            return;
        }

        $this->data['work_order_lookup'] = $wo->wo_no;

        $this->form->fill($this->data);
    }    

}
