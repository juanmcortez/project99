<?php

namespace Tests\Feature\Auth;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->put(route('user-profile-information.update'), [
            'username' => 'updateduser',
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertSame('updateduser', $user->username);
    }
}
