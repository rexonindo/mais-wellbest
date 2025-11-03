<?php

namespace App\Filament\Resources\ProductRouteResource\Pages;

use App\Filament\Resources\ProductRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductRoute extends CreateRecord
{
    protected static string $resource = ProductRouteResource::class;
    protected static ?string $title = 'Process Flow List';    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }    
}
