<?php

namespace App\Filament\Resources\ModelHasRoleResource\Pages;

use App\Filament\Resources\ModelHasRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModelHasRole extends EditRecord
{
    protected static string $resource = ModelHasRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
