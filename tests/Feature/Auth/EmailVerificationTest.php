<?php

namespace Tests\Feature\Auth;

use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function verificationUrl(User $user, ?Carbon $expiry = null): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            $expiry ?? Carbon::now()->addHour(),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }

    public function test_authenticated_user_can_verify_email(): void
    {
        $user = User::factory()->unverified()->create();
        $url = $this->verificationUrl($user);

        $response = $this->actingAs($user)->get($url);

        $response->assertRedirect(config('fortify.home').'?verified=1');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_unauthenticated_user_is_logged_in_and_verified(): void
    {
        $user = User::factory()->unverified()->create();
        $url = $this->verificationUrl($user);

        $response = $this->get($url);

        $response->assertRedirect(config('fortify.home').'?verified=1');
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_already_verified_user_is_redirected_to_dashboard(): void
    {
        $user = User::factory()->create();
        $url = $this->verificationUrl($user);

        $response = $this->actingAs($user)->get($url);

        $response->assertRedirect(config('fortify.home').'?verified=1');
        $this->assertTrue($user->hasVerifiedEmail());
    }

    public function test_invalid_hash_returns_403(): void
    {
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addHour(),
            [
                'id' => $user->getKey(),
                'hash' => 'invalid-hash',
            ]
        );

        $this->get($url)->assertForbidden();
    }

    public function test_expired_signed_url_returns_403(): void
    {
        $user = User::factory()->unverified()->create();
        $url = $this->verificationUrl($user, Carbon::now()->subMinute());

        $this->get($url)->assertForbidden();
    }

    public function test_wrong_user_id_returns_404(): void
    {
        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addHour(),
            [
                'id' => 99999,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $this->get($url)->assertNotFound();
    }
}
