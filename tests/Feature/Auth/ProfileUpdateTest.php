<?php

namespace Tests\Feature\Auth;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_verified_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('user-profile-information.update'), [
            'username' => 'updateduser',
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertSame('updateduser', $user->username);
    }
}
