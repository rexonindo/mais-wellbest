<?php

namespace App\Filament\Resources\ToolingCavityResource\Pages;

use App\Filament\Resources\ToolingCavityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateToolingCavity extends CreateRecord
{
    protected static string $resource = ToolingCavityResource::class;

    public function mount(): void
    {
        parent::mount();

        // Optional: clear session when opening create page fresh
        if (! request()->has('createAnother')) {
            session()->forget('tooling_cavity.itm_cd');
        }
    }

    protected function afterCreate(): void
    {
        session()->put('tooling_cavity.itm_cd', $this->data['itm_cd'] ?? null);
        session()->flash('tooling_cavity.focus_tool_cd', true);        

    }
}
