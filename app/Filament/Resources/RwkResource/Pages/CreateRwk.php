<?php

namespace App\Filament\Resources\RwkResource\Pages;

use App\Filament\Resources\RwkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRwk extends CreateRecord
{
    protected static string $resource = RwkResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }       
}
