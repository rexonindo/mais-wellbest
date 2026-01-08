<?php

namespace App\Filament\Resources\ToolingCavityResource\Pages;

use App\Filament\Resources\ToolingCavityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListToolingCavities extends ListRecords
{
    protected static string $resource = ToolingCavityResource::class;
    protected static ?string $title = 'Tooling Cavity <List>';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }    

}
