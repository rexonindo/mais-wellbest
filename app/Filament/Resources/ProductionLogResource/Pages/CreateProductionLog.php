<?php

namespace App\Filament\Resources\ProductionLogResource\Pages;

use App\Filament\Resources\ProductionLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\On;

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
}
