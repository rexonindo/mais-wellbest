<?php

namespace App\Filament\Resources\NgResource\Pages;

use App\Filament\Resources\NgResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNgs extends ListRecords
{
    protected static string $resource = NgResource::class;
    protected static ?string $title = 'NG <List>';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }    
}
