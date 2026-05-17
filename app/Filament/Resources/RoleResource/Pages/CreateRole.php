<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public array $permissionsToSync = [];

    protected function mutateFormDataBeforeCreate(array $data): array
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

    protected function afterCreate(): void
    {
        if (!empty($this->permissionsToSync)) {
            $this->record->permissions()->sync($this->permissionsToSync);
        }
    }
}
