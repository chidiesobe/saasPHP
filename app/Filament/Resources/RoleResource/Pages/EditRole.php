<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    public static function canAccess(array $parameters = []): bool
    {
        $user = auth()->user();

        if (!$user->can('update_role')) {
            return false;
        }

        $record = $parameters['record'] ?? null;

        if ($record) {
            $roleName = strtolower($record->name);

            if ($roleName === 'admin') {
                return $user->can('update_admin_role');
            }

            if (!in_array($roleName, ['admin', 'user'])) {
                return $user->can('update_staff_role');
            }
        }

        return true;
    }


    protected function getActions(): array
    {
        if (in_array($this->record->name, ['admin', 'user'])) {
            // Do not allow deleting
            return [];
        }

        return [
            Actions\DeleteAction::make()
                ->visible(
                    fn($record) =>
                    !in_array(strtolower($record->name), ['admin', 'user']) &&
                        $record->users()->count() === 0
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Collect the submitted role tab permissions (these are names)
        $submitted = collect($data['role'] ?? []);
        $original = $this->record->permissions
            ->pluck('name')
            ->filter(fn($name) => str($name)->endsWith('_role'));

        if (
            ! auth()->user()->hasRole('admin')
            &&
            ! auth()->user()->can('assign_core_role')

        ) {
            if ($submitted->sort()->values()->all() !== $original->sort()->values()->all()) {
                abort(403, 'You are not authorized to modify role-related permissions.');
            }
        }

        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return ! in_array($key, ['name', 'guard_name', 'select_all', Utils::getTenantModelForeignKey()]);
            })
            ->values()
            ->flatten()
            ->unique();


        if (Arr::has($data, Utils::getTenantModelForeignKey())) {
            return Arr::only($data, ['name', 'guard_name', Utils::getTenantModelForeignKey()]);
        }

        return Arr::only($data, ['name', 'guard_name']);
    }

    protected function afterSave(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }

    protected function beforeSave(): void
    {
        if (in_array($this->record->name, ['user'])) {
            abort(403, 'You are not allowed to edit this role.');
        }
    }
}
