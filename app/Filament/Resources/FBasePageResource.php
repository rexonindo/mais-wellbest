<?php

namespace App\Filament\Resources;

use Filament\Pages\Page;
use Filament\Facades\Filament;
use App\Helpers\RoleAccess;

abstract class FBasePageResource extends Page
{
    /*
    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole('admin'); // or adjust as needed
    }    
    */
    public static function canAccess(): bool
    {
        return RoleAccess::canAccessGroup(static::$navigationGroup ?? '');
    }    

    public static function canViewAny(): bool
    {
        return RoleAccess::canAccessGroup(static::$navigationGroup ?? '');
    }

    public static function canCreate(): bool
    {
        return RoleAccess::canAccessGroup(static::$navigationGroup ?? '');
    }

    public static function canEdit($record): bool
    {
        return RoleAccess::canAccessGroup(static::$navigationGroup ?? '');
    }

    public static function canDelete($record): bool
    {
        return RoleAccess::canAccessGroup(static::$navigationGroup ?? '');
    }
}
