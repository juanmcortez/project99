<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * @var list<string>
     */
    private const PERMISSIONS = [
        'activity-log.view',
        'users.manage',
        'roles.manage',
        'permissions.manage',
        'edit.profile.username',
        'edit.profile.email',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $userRole = Role::findOrCreate('user');
        $adminRole = Role::findOrCreate('admin');
        $superadminRole = Role::findOrCreate('superadmin');

        $adminRole->syncPermissions([
            'edit.profile.email',
            'activity-log.view',
            'users.manage',
        ]);

        $superadminRole->syncPermissions(self::PERMISSIONS);
    }
}
