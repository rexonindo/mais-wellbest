<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CompanyWelcome extends Page
{
    protected static ?string $title = 'Welcome';
    protected static bool $shouldShowTitle = false;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Welcome';
    protected static string $view = 'filament.pages.company-welcome';
    protected static ?string $slug = 'company-welcome';
    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 1;

    public function getTitle(): string
    {
        return '';
    }  
}