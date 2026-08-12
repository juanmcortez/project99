<?php

namespace Tests\Feature\Auth;

use App\Models\Users\User;
use Database\Seeders\Roles\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_verified_user_can_update_profile_with_both_permissions(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['edit.profile.username', 'edit.profile.email']);

        $response = $this->actingAs($user)->put(route('user-profile-information.update'), [
            'username' => 'updateduser',
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertSame('updateduser', $user->username);
    }

    public function test_user_with_email_permission_only_can_update_email_without_username(): void
    {
        $user = User::factory()->create(['username' => 'originaluser']);
        $user->givePermissionTo('edit.profile.email');

        $response = $this->actingAs($user)->put(route('user-profile-information.update'), [
            'email' => 'newemail@example.com',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertSame('originaluser', $user->username);
        $this->assertSame('newemail@example.com', $user->email);
    }

    public function test_user_with_username_permission_only_can_update_username_without_email(): void
    {
        $user = User::factory()->create(['email' => 'original@example.com']);
        $user->givePermissionTo('edit.profile.username');

        $response = $this->actingAs($user)->put(route('user-profile-information.update'), [
            'username' => 'updateduser',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertSame('updateduser', $user->username);
        $this->assertSame('original@example.com', $user->email);
    }

    public function test_user_without_profile_permissions_cannot_update_profile(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user)->put(route('user-profile-information.update'), [
            'username' => 'updateduser',
            'email' => 'newemail@example.com',
        ])->assertForbidden();
    }

    public function test_user_cannot_update_username_without_permission(): void
    {
        $user = User::factory()->create(['username' => 'originaluser']);
        $user->givePermissionTo('edit.profile.email');

        $this->actingAs($user)->put(route('user-profile-information.update'), [
            'username' => 'hackeduser',
            'email' => 'newemail@example.com',
        ])->assertRedirect();

        $user->refresh();
        $this->assertSame('originaluser', $user->username);
        $this->assertSame('newemail@example.com', $user->email);
    }
}
