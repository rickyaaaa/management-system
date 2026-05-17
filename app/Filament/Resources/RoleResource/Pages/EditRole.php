<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public array $permissionsToSync = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $permissions = $this->record->permissions()->get();
        $grouped = $permissions->groupBy('group');
        
        // Ensure all possible groups are set, even if empty, to clear out checkboxes if no permissions exist
        $groups = \App\Models\Permission::select('group')->distinct()->pluck('group');
        foreach ($groups as $group) {
            $data["permissions_group_{$group}"] = [];
        }

        foreach ($grouped as $group => $perms) {
            $data["permissions_group_{$group}"] = $perms->pluck('id')->toArray();
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissionsToSync = [];
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'permissions_group_')) {
                if (is_array($value)) {
                    $this->permissionsToSync = array_merge($this->permissionsToSync, $value);
                }
                unset($data[$key]);
            }
        }
        return $data;
    }

    protected function afterSave(): void
    {
        // Sync is always called to allow removing all permissions
        $this->record->permissions()->sync($this->permissionsToSync);
    }
}
