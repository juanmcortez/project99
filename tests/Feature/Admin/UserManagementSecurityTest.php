<?php

namespace Tests\Feature\Admin;

use App\Models\Users\User;
use Database\Seeders\Roles\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementSecurityTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_users_data_endpoint_does_not_expose_two_factor_secrets(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $target = User::factory()->create();
        $target->forceFill([
            'two_factor_secret' => encrypt('super-secret-value'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code'])),
        ])->save();

        $response = $this->actingAs($admin)->getJson(route('admin.users.data'));

        $response->assertOk();
        $response->assertDontSee('two_factor_secret');
        $response->assertDontSee('two_factor_recovery_codes');
        $response->assertDontSee('super-secret-value');
    }

    public function test_users_data_endpoint_escapes_role_names(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Role::create(['name' => '<script>alert(1)</script>']);

        $response = $this->actingAs($admin)->getJson(route('admin.users.data'));

        $response->assertOk();
        $response->assertDontSee('<script>alert(1)', false);
        $response->assertSee('&lt;script&gt;', false);
    }
}
