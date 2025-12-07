<?php

namespace App\Filament\Resources\NgResource\Pages;

use App\Filament\Resources\NgResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNg extends CreateRecord
{
    protected static string $resource = NgResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }   
}
