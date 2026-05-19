<?php

namespace App\Filament\Resources\RwkResource\Pages;

use App\Filament\Resources\RwkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRwk extends EditRecord
{
    protected static string $resource = RwkResource::class;
    protected static ?string $title = 'Rework <Edit>';    

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
