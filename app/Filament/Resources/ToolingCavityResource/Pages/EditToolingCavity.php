<?php

namespace App\Filament\Resources\ToolingCavityResource\Pages;

use App\Filament\Resources\ToolingCavityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditToolingCavity extends EditRecord
{
    protected static string $resource = ToolingCavityResource::class;
    protected static ?string $title = 'Tooling Cavity <Edit>';    

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
