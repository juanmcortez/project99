<?php

namespace Tests\Feature\Profile;

use App\Http\Requests\Addresses\StoreAddressRequest;
use App\Models\Users\User;
use App\Services\Demographics\DemographicService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProfileAddressTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validAddressData(): array
    {
        return [
            'street_line_1' => '123 Main St',
            'city' => 'Springfield',
            'state' => 'IL',
            'zip_code' => '62701',
            'country' => 'US',
        ];
    }

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

    public function test_verified_user_can_store_address_via_profile(): void
    {
        $user = User::factory()->create();

        DemographicService::createFor($user, $this->validDemographicData());

        $response = $this->actingAs($user)->post(route('profile.address.store'), $this->validAddressData());

        $response->assertRedirect(route('profile.edit').'#user-location');
        $response->assertSessionHas('status', 'address-saved');

        $this->assertDatabaseHas('addresses', [
            'street_line_1' => '123 Main St',
            'city' => 'Springfield',
            'country' => 'US',
        ]);
    }

    public function test_verified_user_can_update_address_via_profile(): void
    {
        $user = User::factory()->create();

        DemographicService::createFor($user, $this->validDemographicData());

        $this->actingAs($user)->post(route('profile.address.store'), $this->validAddressData());

        $response = $this->actingAs($user)->put(route('profile.address.update'), [
            'street_line_1' => '456 Oak Ave',
            'city' => 'Chicago',
            'state' => 'IL',
            'zip_code' => '60601',
            'country' => 'US',
        ]);

        $response->assertRedirect(route('profile.edit').'#user-location');
        $response->assertSessionHas('status', 'address-saved');

        $user->load('demographic.address');
        $this->assertSame('456 Oak Ave', $user->demographic?->address?->street_line_1);
    }

    public function test_storing_address_without_demographic_returns_error(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.address.store'), $this->validAddressData());

        $response->assertRedirect(route('profile.edit').'#user-details');
        $response->assertSessionHasErrors('address', null, 'updateDemographic');
    }

    public function test_validation_rejects_invalid_country_code(): void
    {
        $validator = Validator::make([
            'street_line_1' => '123 Main St',
            'city' => 'Springfield',
            'state' => 'IL',
            'zip_code' => '62701',
            'country' => 'USA',
        ], (new StoreAddressRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('country', $validator->errors()->toArray());
    }

    public function test_validation_rejects_missing_required_fields(): void
    {
        $validator = Validator::make([], (new StoreAddressRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('street_line_1', $validator->errors()->toArray());
        $this->assertArrayHasKey('city', $validator->errors()->toArray());
        $this->assertArrayHasKey('state', $validator->errors()->toArray());
        $this->assertArrayHasKey('zip_code', $validator->errors()->toArray());
        $this->assertArrayHasKey('country', $validator->errors()->toArray());
    }
}
