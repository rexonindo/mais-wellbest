<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Facades\Filament;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Filament\Support\Colors\Color;

use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class WellbestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('wellbest')
            ->path('wellbest')

            // --- INSERT PWA FILES HERE ---
            // 1. Include manifest
            ->renderHook(
                'panels::head.end',
                fn (): string => <<<HTML
                    <link rel="manifest" href="/manifest.json">
                    <meta name="theme-color" content="#1a73e8">
                    <script type="module" src="/build/assets/pwa.js"></script>
                HTML
            )
            // 2. Load pwa.js
            ->bootUsing(function () {
                FilamentAsset::register([
                    \Filament\Support\Assets\Js::make(
                        'pwa-js',
                        asset('build/assets/pwa-DwUm5zTl.js') // ← USE YOUR HASHED FILE
                    ),
                ], 'wellbest-pwa');
            })
            // ---------------------------------

            ->login()
            ->sidebarCollapsibleOnDesktop()
            ->brandName('MAIS - Wellbest')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()->label('Master Data'),
                NavigationGroup::make()->label('Process & Flow'),
                NavigationGroup::make()->label('Production Planning'),
                NavigationGroup::make()->label('Actual Production'),
                NavigationGroup::make()->label('Quality Control'),
                NavigationGroup::make()->label('Reports'),
                NavigationGroup::make()->label('Administration'),
            ]);
    }

    public function navigation(\Filament\Navigation\NavigationBuilder $builder): \Filament\Navigation\NavigationBuilder
    {
        $user = Filament::auth()->user();

        return $builder->groups([
            NavigationGroup::make('Master Data')
                ->visible(fn() => $user && ($user->hasRole('admin') || $user->hasRole('master data'))),

            NavigationGroup::make('Process & Flow')
                ->visible(fn() => $user && ($user->hasRole('admin') || $user->hasRole('master data') || $user->hasRole('ppic'))),

            NavigationGroup::make('Production Planning')
                ->visible(fn() => $user && ($user->hasRole('admin') || $user->hasRole('ppic'))),

            NavigationGroup::make('Actual Production')
                ->visible(fn() => $user && ($user->hasRole('admin') || $user->hasRole('production'))),

            NavigationGroup::make('Quality Control')
                ->visible(fn() => $user && ($user->hasRole('admin') || $user->hasRole('quality control'))),

            NavigationGroup::make('Reports')
                ->visible(fn() => $user && ($user->hasRole('admin') || $user->hasRole('management') || $user->hasRole('master data') || $user->hasRole('ppic') || $user->hasRole('production') || $user->hasRole('quality control'))),

            NavigationGroup::make('Administration')
                ->visible(fn() => $user && $user->hasRole('admin')),
        ]);
    }

    public function boot(): void
    {
        // No parent::boot() required
    }
}
