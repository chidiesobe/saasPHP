<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;


    public static function canAccess(array $parameters = []): bool
    {
         return auth()->user()->can('create', \App\Models\User::class);
    }
}
