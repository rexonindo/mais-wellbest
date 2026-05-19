<?php

namespace App\Filament\Resources\RwkResource\Pages;

use App\Filament\Resources\RwkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRwks extends ListRecords
{
    protected static string $resource = RwkResource::class;
    protected static ?string $title = 'Rework <List>';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }    
}
