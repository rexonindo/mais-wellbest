<?php

return [

    'default_home_route' => 'filament.pages.company-dashboard',
    
    /*
    |--------------------------------------------------------------------------
    | Filament Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Filament will be accessible from.
    | For example, "admin" will make the panel accessible at /admin.
    |
    */

    'path' => env('FILAMENT_PATH', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Filament Domain
    |--------------------------------------------------------------------------
    |
    | You may specify the domain where Filament should be accessible.
    | If set to null, it will be accessible on all domains.
    |
    */

    'domain' => env('FILAMENT_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | These settings control how users are authenticated with Filament.
    |
    */

    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Filament\Http\Livewire\Auth\Login::class,
        ],
    ],

    'path' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    |
    | Here you may customize the branding of your Filament panel.
    |
    */

    'brand' => [
        'name' => '', // env('APP_NAME', 'Filament'),
        'logo' => null, // e.g., asset('images/logo.svg')
        'favicon' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | You can use this to register custom themes or modify colors.
    |
    */

    'theme' => [
        'primary_color' => '#3b82f6', // Tailwind blue-500
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Customize the panel layout.
    |
    */

    'layout' => [
        'sidebar' => [
            'collapsible' => true,
        ],
        'max_content_width' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Configure the default notification duration, etc.
    |
    */

    'notifications' => [
        'duration' => 3000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | You can register custom CSS and JS files here.
    |
    */

    'assets' => [
        'styles' => [],
        'scripts' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware are applied to every Filament request.
    |
    */

    'middleware' => [
        'auth' => [
            \Filament\Http\Middleware\Authenticate::class,
        ],
        'base' => [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ],

];

