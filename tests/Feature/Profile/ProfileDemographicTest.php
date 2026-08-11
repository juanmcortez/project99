<?php

namespace Tests\Feature\Profile;

use App\Models\Users\User;
use App\Services\Demographics\DemographicService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDemographicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validDemographicData(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'birthdate' => '1990-01-01',
        ];
    }

    public function test_profile_page_loads_for_verified_user(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Demographics');
    }

    public function test_verified_user_can_store_demographic_via_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('profile.demographic.store'), $this->validDemographicData());

        $response->assertRedirect(route('profile.edit').'#demographics');
        $response->assertSessionHas('status', 'demographic-saved');

        $this->assertDatabaseHas('demographics', [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'demographicable_id' => $user->id,
            'demographicable_type' => $user->getMorphClass(),
        ]);
    }

    public function test_verified_user_can_update_demographic_via_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        DemographicService::createFor($user, $this->validDemographicData());

        $response = $this->actingAs($user)->put(route('profile.demographic.update'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'birthdate' => '1985-06-20',
        ]);

        $response->assertRedirect(route('profile.edit').'#demographics');
        $response->assertSessionHas('status', 'demographic-saved');

        $user->load('demographic');
        $this->assertSame('Jane', $user->demographic?->first_name);
        $this->assertSame('Doe', $user->demographic?->last_name);
    }
}
