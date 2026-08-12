<?php

namespace Tests\Feature;

use App\Models\Users\User;
use Database\Seeders\Roles\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class NavDropdownTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_dashboard_shows_nav_dropdown_with_username_when_demographic_missing(): void
    {
        $user = User::factory()->create(['username' => 'plainuser']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('data-nav-dropdown', false);
        $response->assertSee('plainuser');
        $response->assertSee('My profile');
    }

    public function test_nav_dropdown_shows_full_name_and_settings_for_superadmin(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create(['username' => 'superadmin']);
        $user->assignRole('superadmin');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('My profile');
        $response->assertSee('Settings');
        $response->assertSee('Activity log');
        $response->assertSee('Users roles');
        $response->assertSee('Roles config');
        $response->assertSee('Permissions');
        $response->assertSee('Roles');
        $response->assertSee('Log out');
    }

    public function test_nav_dropdown_hides_settings_for_user_without_admin_permissions(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('My profile');
        $response->assertDontSee('Settings');
        $response->assertDontSee('Activity log');
        $response->assertSee('Log out');
    }

    public function test_nav_dropdown_shows_full_name_from_demographic(): void
    {
        $user = User::factory()->create(['username' => 'jdoe']);

        $user->demographic()->create([
            'first_name' => 'John',
            'middle_name' => 'Q',
            'last_name' => 'Public',
            'birthdate' => '1990-01-01',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('John Q Public');
    }

    public function test_dashboard_link_is_highlighted_on_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('text-indigo-600', false);
    }

    public function test_admin_user_sees_only_users_roles_menu_item(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        Permission::findOrCreate('users.manage');

        $user = User::factory()->create();
        $user->syncPermissions(['users.manage']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Settings');
        $response->assertSee('Users roles');
        $response->assertDontSee('Roles config');
        $response->assertDontSee('Activity log');
    }

    public function test_nav_dropdown_shows_phones_with_type_labels(): void
    {
        $user = User::factory()->create();

        $demographic = $user->demographic()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'birthdate' => '1990-01-01',
        ]);

        $demographic->phones()->create([
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);

        $demographic->phones()->create([
            'phone_number' => '+13105559876',
            'type' => 'work',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Mobile: +12125551234');
        $response->assertSee('Work: +13105559876');
    }

    public function test_nav_dropdown_shows_formatted_address(): void
    {
        $user = User::factory()->create();

        $demographic = $user->demographic()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'birthdate' => '1990-01-01',
        ]);

        $demographic->address()->create([
            'street_line_1' => '123 Main St',
            'street_line_2' => 'Apt 4',
            'city' => 'Springfield',
            'state' => 'IL',
            'zip_code' => '62701',
            'country' => 'US',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('123 Main St');
        $response->assertSee('Apt 4');
        $response->assertSee('Springfield, IL 62701');
        $response->assertSee('United States');
    }

    public function test_nav_dropdown_hides_phone_and_address_when_missing(): void
    {
        $user = User::factory()->create(['username' => 'plainuser']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('Mobile:');
        $response->assertDontSee('Work:');
        $response->assertDontSee('United States');
        $response->assertDontSee('123 Main St');
    }
}
