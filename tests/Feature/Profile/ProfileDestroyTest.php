<?php

namespace Tests\Feature\Profile;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProfileDestroyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_verified_user_can_delete_account_with_correct_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertGuest();
    }

    public function test_guest_cannot_delete_account(): void
    {
        $response = $this->post(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_delete_account_rejects_incorrect_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('profile.edit').'#security');
        $response->assertSessionHasErrors('password', null, 'deleteAccount');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }

    public function test_delete_account_requires_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.destroy'), []);

        $response->assertRedirect(route('profile.edit').'#security');
        $response->assertSessionHasErrors('password', null, 'deleteAccount');
        $this->assertAuthenticated();
    }
}
