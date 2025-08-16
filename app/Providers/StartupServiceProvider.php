<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;

class StartupServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->app->booted(function () {
            if (
                !Schema::hasTable('users') ||
                !Schema::hasTable('roles') ||
                !Schema::hasTable('model_has_roles') ||
                !Schema::hasTable('settings')
            ) {
                return; // Tables not yet migrated
            }

            // Ensure the 'settings' table is populated with default values
            // from the configuration file 'saasphp-data.php'
            // This is done only once to avoid duplicates
            // and to ensure the table is initialized with necessary settings.
            if (DB::table('settings')->count() === 0) {
                $defaults = config('saasphp-data');
                foreach ($defaults as $group => $settings) {
                    foreach ($settings as $key => $meta) {
                        $exists = DB::table('settings')->where('key', $key)->exists();

                        if (! $exists) {
                            DB::table('settings')->insert([
                                'key'        => $key,
                                'value'      => Crypt::encryptString(
                                    $meta['type'] === 'boolean'
                                        ? ($meta['value'] ? 'true' : 'false')
                                        : $meta['value']
                                ),
                                'group'      => $group,
                                'type'       => $meta['type'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }


            // Only create these once, on first install
            if (DB::table('users')->count() === 0 && DB::table('roles')->count() === 0) {

                $adminRoleId = DB::table('roles')->insertGetId([
                    'name'       => 'admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $userRoleId = DB::table('roles')->insertGetId([
                    'name'       => 'user',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $adminUserId = DB::table('users')->insertGetId([
                    'name'              => 'Admin User',
                    'email'             => 'admin@saasphp.com',
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                $userOneId = DB::table('users')->insertGetId([
                    'name'       => 'User One',
                    'email'      => 'user1@saasphp.com',
                    'password'   => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $userTwoId = DB::table('users')->insertGetId([
                    'name'       => 'User Two',
                    'email'      => 'user2@saasphp.com',
                    'password'   => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Attach roles
                $this->assignRole($adminUserId, $adminRoleId);
                $this->assignRole($userOneId, $userRoleId);
                $this->assignRole($userTwoId, $userRoleId);
            }
        });
    }

    protected function assignRole($userId, $roleId): void
    {
        DB::table('model_has_roles')->updateOrInsert([
            'role_id'    => $roleId,
            'model_type' => User::class,
            'model_id'   => $userId,
        ]);
    }
}
