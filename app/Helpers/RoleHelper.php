<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class RoleHelper
{
    private static ?int $adminCount = null;

    public static function shouldHideAdminDetach($record): bool
    {
        static::$adminCount ??= DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'admin')
            ->count();

        // Allow detaching if the user has permission to update the role and there are more than one admin
        return auth()->user()->can('update', $record) && static::$adminCount > 1;
    }

    public static function getAdminCount(): int
    {
        return static::$adminCount ??= DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'admin')
            ->count();
    }
}
