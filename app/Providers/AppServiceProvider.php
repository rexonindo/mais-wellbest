<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationItem::make('Dashboard')
                    ->url(url('/wellbest/company-dashboard'))  // full URL to page
                    ->icon('heroicon-o-home')
                    ->sort(1),
            ]);
        });       
        */

    }
}
