<?php

namespace App\Services\Roles;

use App\Exceptions\ProtectedSystemRoleException;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * @var list<string>
     */
    private const SYSTEM_ROLES = [
        'user',
        'admin',
        'superadmin',
    ];

    /**
     * @param  array{name: string, permissions?: list<string>}  $data
     */
    public static function create(array $data): Role
    {
        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return $role;
    }

    /**
     * @param  array{name: string, permissions?: list<string>}  $data
     */
    public static function update(Role $role, array $data): Role
    {
        if (self::isSystemRole($role)) {
            $role->syncPermissions($data['permissions'] ?? []);

            return $role->fresh();
        }

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return $role->fresh();
    }

    public static function delete(Role $role): void
    {
        if (self::isSystemRole($role)) {
            throw new ProtectedSystemRoleException;
        }

        $role->delete();
    }

    public static function isSystemRole(Role $role): bool
    {
        return in_array($role->name, self::SYSTEM_ROLES, true);
    }
}
