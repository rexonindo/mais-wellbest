<?php

namespace App\Filament\Resources\ToolingCavityResource\Pages;

use App\Filament\Resources\ToolingCavityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateToolingCavity extends CreateRecord
{
    protected static string $resource = ToolingCavityResource::class;
    protected static ?string $title = 'Tooling Cavity <Create>';  

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }        
}
