<?php

namespace App\Filament\Resources\ToolingCavityResource\Pages;

use App\Filament\Resources\ToolingCavityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreateToolingCavity extends CreateRecord
{
    protected static string $resource = ToolingCavityResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }    

    public function mount(): void
    {
        parent::mount();

        // Optional: clear session when opening create page fresh
        if (! request()->has('createAnother')) {
            session()->forget('tooling_cavity.itm_cd');
            session()->forget('tooling_cavity.tool_cd');
        }
    }

    protected function afterCreate(): void
    {
        session()->put('tooling_cavity.itm_cd', $this->data['itm_cd'] ?? null);
        session()->put('tooling_cavity.tool_cd', $this->data['tool_cd'] ?? null);
        session()->flash('tooling_cavity.focus_proc_nm', true);                

    }

}
