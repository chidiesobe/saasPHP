<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->can('update', User::class);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->color('success')
                ->label('')
                ->visible(
                    fn(User $record) =>
                    auth()->user()?->can('delete', $record) &&
                        !$record->hasRole('admin')
                ),
            Actions\RestoreAction::make()
                ->icon('heroicon-o-arrow-path')
                ->label('')->color('warning')
                ->visible(
                    fn(User $record) =>
                    auth()->user()?->can('restore', $record) && $record->trashed()
                ),
            Actions\ForceDeleteAction::make()
                ->icon('heroicon-o-trash')
                ->label('')->color('danger')
                ->visible(
                    fn(User $record) =>
                    auth()->user()?->can('forceDelete', $record) &&
                        !$record->hasRole('admin')
                ),
        ];
    }

    protected function getActions(): array
    {
        return auth()->user()?->can('impersonate')
            ? [Impersonate::make()->record($record)]
            : [];
    }
}
