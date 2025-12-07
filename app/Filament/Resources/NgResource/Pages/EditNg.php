<?php

namespace App\Filament\Resources\NgResource\Pages;

use App\Filament\Resources\NgResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNg extends EditRecord
{
    protected static string $resource = NgResource::class;
    protected static ?string $title = 'NG <Edit>';    

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
