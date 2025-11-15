<?php

namespace App\Filament\Resources\ProductRouteResource\Pages;

use App\Filament\Resources\ProductRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductRoute extends EditRecord
{
    protected static string $resource = ProductRouteResource::class;
    protected static ?string $title = 'Process Flow <Edit>';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
