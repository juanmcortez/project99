<?php

namespace App\Services\Permissions;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * @param  array{name: string}  $data
     */
    public static function create(array $data): Permission
    {
        return Permission::create(['name' => $data['name']]);
    }

    /**
     * @param  array{name: string}  $data
     */
    public static function update(Permission $permission, array $data): Permission
    {
        $permission->update(['name' => $data['name']]);

        return $permission->fresh();
    }

    public static function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
