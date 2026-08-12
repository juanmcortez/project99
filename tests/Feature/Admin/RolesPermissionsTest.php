<?php

namespace Tests\Feature\Admin;

use App\Enums\ActivityLogAction;
use App\Models\Users\User;
use Database\Seeders\Roles\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesPermissionsTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_user_role_cannot_access_activity_log(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user)->get(route('activity-log.index'))->assertForbidden();
    }

    public function test_admin_role_can_access_activity_log_and_user_management(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get(route('activity-log.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.roles.index'))->assertForbidden();
    }

    public function test_superadmin_can_access_all_admin_routes(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $this->actingAs($superadmin)->get(route('activity-log.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.roles.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.permissions.index'))->assertOk();
    }

    public function test_new_user_registration_gets_user_role(): void
    {
        $this->post('/register', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('user'));
    }

    public function test_superadmin_seeder_user_gets_superadmin_role(): void
    {
        $user = User::factory()->create([
            'username' => 'superadmin',
            'email' => 'superadmin@project99.com',
        ]);
        $user->assignRole('superadmin');

        $this->assertTrue($user->hasRole('superadmin'));
    }

    public function test_superadmin_can_create_edit_and_delete_custom_role(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $this->actingAs($superadmin)->post(route('admin.roles.store'), [
            'name' => 'editor',
            'permissions' => ['activity-log.view'],
        ])->assertRedirect(route('admin.roles.index'));

        $role = Role::where('name', 'editor')->first();
        $this->assertNotNull($role);
        $this->assertTrue($role->hasPermissionTo('activity-log.view'));

        $this->actingAs($superadmin)->put(route('admin.roles.update', $role), [
            'name' => 'editor-updated',
            'permissions' => ['users.manage'],
        ])->assertRedirect(route('admin.roles.index'));

        $role->refresh();
        $this->assertSame('editor-updated', $role->name);
        $this->assertTrue($role->hasPermissionTo('users.manage'));

        $this->actingAs($superadmin)->delete(route('admin.roles.destroy', $role))
            ->assertRedirect(route('admin.roles.index'));

        $this->assertNull(Role::where('name', 'editor-updated')->first());
    }

    public function test_superadmin_can_create_role_without_permissions(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $this->actingAs($superadmin)->post(route('admin.roles.store'), [
            'name' => 'viewer',
        ])->assertRedirect(route('admin.roles.index'));

        $role = Role::where('name', 'viewer')->first();
        $this->assertNotNull($role);
        $this->assertCount(0, $role->permissions);
    }

    public function test_superadmin_can_remove_all_permissions_from_role(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $role = Role::create(['name' => 'editor']);
        $role->syncPermissions(['activity-log.view']);

        $this->actingAs($superadmin)->put(route('admin.roles.update', $role), [
            'name' => 'editor',
        ])->assertRedirect(route('admin.roles.index'));

        $role->refresh();
        $this->assertCount(0, $role->permissions);
    }

    public function test_superadmin_cannot_delete_system_role(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $role = Role::where('name', 'user')->firstOrFail();

        $this->actingAs($superadmin)->delete(route('admin.roles.destroy', $role))
            ->assertStatus(500);

        $this->assertNotNull(Role::where('name', 'user')->first());
    }

    public function test_superadmin_can_edit_system_role_permissions(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->assertTrue($adminRole->hasPermissionTo('activity-log.view'));
        $this->assertFalse($adminRole->hasPermissionTo('roles.manage'));

        $this->actingAs($superadmin)->put(route('admin.roles.update', $adminRole), [
            'name' => 'admin',
            'permissions' => ['activity-log.view', 'roles.manage'],
        ])->assertRedirect(route('admin.roles.index'));

        $adminRole->refresh();
        $this->assertSame('admin', $adminRole->name);
        $this->assertTrue($adminRole->hasPermissionTo('roles.manage'));
    }

    public function test_superadmin_cannot_rename_system_role(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $adminRole = Role::where('name', 'admin')->firstOrFail();

        $this->actingAs($superadmin)->put(route('admin.roles.update', $adminRole), [
            'name' => 'admin-renamed',
            'permissions' => ['activity-log.view', 'users.manage'],
        ])->assertSessionHasErrors('name');

        $this->assertNotNull(Role::where('name', 'admin')->first());
    }

    public function test_superadmin_can_manage_permissions(): void
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $this->actingAs($superadmin)->post(route('admin.permissions.store'), [
            'name' => 'reports.view',
        ])->assertRedirect(route('admin.permissions.index'));

        $permission = Permission::where('name', 'reports.view')->first();
        $this->assertNotNull($permission);

        $this->actingAs($superadmin)->put(route('admin.permissions.update', $permission), [
            'name' => 'reports.export',
        ])->assertRedirect(route('admin.permissions.index'));

        $permission->refresh();
        $this->assertSame('reports.export', $permission->name);

        $this->actingAs($superadmin)->delete(route('admin.permissions.destroy', $permission))
            ->assertRedirect(route('admin.permissions.index'));

        $this->assertNull(Permission::where('name', 'reports.export')->first());
    }

    public function test_update_user_role_assigns_role_and_logs_activity(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $target = User::factory()->create();
        $target->assignRole('user');

        $this->actingAs($admin)->put(route('admin.users.update-role', $target), [
            'role' => 'admin',
        ])->assertRedirect(route('admin.users.index'));

        $target->refresh();
        $this->assertTrue($target->hasRole('admin'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => ActivityLogAction::UserRoleUpdated->value,
        ]);
    }
}
