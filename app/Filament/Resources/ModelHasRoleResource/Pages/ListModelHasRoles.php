<?php

namespace App\Filament\Resources\ModelHasRoleResource\Pages;

use App\Filament\Resources\ModelHasRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModelHasRoles extends ListRecords
{
    protected static string $resource = ModelHasRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
