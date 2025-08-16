<?php

namespace App\Policies;

use App\Models\User;

class SettingPolicy
{
    public function modify(User $user): bool
    {
        return $user->hasPermissionTo('modify_settings_role');
    }
}
