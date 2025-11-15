<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Helpers\RoleAccess;

abstract class BaseResource extends Resource
{
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
