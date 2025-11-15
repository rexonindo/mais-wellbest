<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;
    protected static ?string $title = 'Customer <Edit>';    

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }    

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->user()->name ?? 'system';
        return $data;
    }
}
