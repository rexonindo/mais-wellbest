<?php

namespace App\Helpers;

use Filament\Facades\Filament;

class RoleAccess
{
    public static function canAccessGroup(string $group): bool
    {
        $user = Filament::auth()->user();
        if (! $user) return false;

        return match ($group) {
            'Master Data' => $user->hasRole(['admin', 'master data']),
            'Process & Flow' => $user->hasRole(['admin', 'master data', 'ppic']),
            'Production Planning' => $user->hasRole(['admin', 'ppic']),
            'Actual Production' => $user->hasRole(['admin', 'production']),
            'Quality Control' => $user->hasRole(['admin', 'quality control']),
            'Reports' => $user->hasRole(['admin', 'management', 'master data', 'ppic', 'production', 'quality control']),
            'Administration' => $user->hasRole(['admin']),
            default => false,
        };
    }
}
