<?php

namespace App\Filament\Resources\ProductionLogResource\Pages;

use App\Filament\Resources\ProductionLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateProductionLog extends CreateRecord
{
    protected static string $resource = ProductionLogResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
/*
    protected function beforeSave(): void
    {
        $record = $this->record;
        $data   = $this->data; // form data user submitted
        $InQty = ($data['in_qty'] ?? 0);
        $RsltQty = ($data['out_qty'] ?? 0) + ($data['ng_qty'] ?? 0) + ($data['rwk_qty'] ?? 0);

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
    }    
*/    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $record = $this->record;
        $data   = $this->data; // form data user submitted
        $InQty = ($data['in_qty'] ?? 0);
        $RsltQty = ($data['out_qty'] ?? 0) + ($data['ng_qty'] ?? 0) + ($data['rwk_qty'] ?? 0);
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

}
