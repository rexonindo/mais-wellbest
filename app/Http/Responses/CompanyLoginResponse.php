<?php

namespace App\Http\Responses;

use App\Filament\Pages\CompanyWelcome;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class CompanyLoginResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        return redirect()->to(CompanyWelcome::getUrl());
    }
}