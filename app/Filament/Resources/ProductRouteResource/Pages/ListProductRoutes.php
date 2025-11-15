<?php

namespace App\Filament\Resources\ProductRouteResource\Pages;

use App\Filament\Resources\ProductRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductRoutes extends ListRecords
{
    protected static string $resource = ProductRouteResource::class;
    protected static ?string $title = 'Process Flow <List>';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
