<?php

namespace Tests\Feature\Profile;

use App\Http\Requests\Phones\StorePhoneRequest;
use App\Models\Phones\Phone;
use App\Models\Users\User;
use App\Services\Demographics\DemographicService;
use App\Services\Phones\PhoneService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ProfilePhoneTest extends TestCase
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

    /**
     * @return array<string, string>
     */
    private function validPhoneData(): array
    {
        return [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ];
    }

    public function test_verified_user_can_store_phone_via_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        DemographicService::createFor($user, $this->validDemographicData());

        $response = $this->actingAs($user)->post(route('profile.phone.store'), $this->validPhoneData());

        $response->assertRedirect(route('profile.edit').'#demographics');
        $response->assertSessionHas('status', 'phone-saved');

        $this->assertDatabaseHas('phones', [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);
    }

    public function test_verified_user_can_update_phone_via_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $demographic = DemographicService::createFor($user, $this->validDemographicData());
        $phone = PhoneService::createFor($demographic, $this->validPhoneData());

        $response = $this->actingAs($user)->put(route('profile.phone.update', $phone), [
            'phone_number' => '+13105559876',
            'type' => 'work',
        ]);

        $response->assertRedirect(route('profile.edit').'#demographics');
        $response->assertSessionHas('status', 'phone-saved');

        $this->assertDatabaseHas('phones', [
            'id' => $phone->id,
            'phone_number' => '+13105559876',
            'type' => 'work',
        ]);
    }

    public function test_verified_user_can_delete_phone_via_profile(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $demographic = DemographicService::createFor($user, $this->validDemographicData());
        $phone = PhoneService::createFor($demographic, $this->validPhoneData());

        $response = $this->actingAs($user)->delete(route('profile.phone.destroy', $phone));

        $response->assertRedirect(route('profile.edit').'#demographics');
        $response->assertSessionHas('status', 'phone-deleted');

        $this->assertSoftDeleted('phones', [
            'id' => $phone->id,
        ]);
        $this->assertNull(Phone::query()->find($phone->id));
    }

    public function test_storing_phone_without_demographic_returns_error(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('profile.phone.store'), $this->validPhoneData());

        $response->assertRedirect(route('profile.edit').'#demographics');
        $response->assertSessionHasErrors('phone', null, 'updateDemographic');
    }

    public function test_storing_third_phone_returns_error(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $demographic = DemographicService::createFor($user, $this->validDemographicData());

        PhoneService::createFor($demographic, [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);

        PhoneService::createFor($demographic, [
            'phone_number' => '+13105559876',
            'type' => 'work',
        ]);

        $response = $this->actingAs($user)->post(route('profile.phone.store'), [
            'phone_number' => '+14155551212',
            'type' => 'home',
        ]);

        $response->assertRedirect(route('profile.edit').'#demographics');
        $response->assertSessionHasErrors('phone', null, 'updateDemographic');
    }

    public function test_user_cannot_update_another_users_phone(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $otherUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $demographic = DemographicService::createFor($otherUser, $this->validDemographicData());
        $phone = PhoneService::createFor($demographic, $this->validPhoneData());

        $response = $this->actingAs($user)->put(route('profile.phone.update', $phone), [
            'phone_number' => '+13105559876',
            'type' => 'work',
        ]);

        $response->assertForbidden();
    }

    public function test_validation_rejects_invalid_e164_phone_number(): void
    {
        $validator = Validator::make([
            'phone_number' => '2125551234',
            'type' => 'mobile',
        ], (new StorePhoneRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone_number', $validator->errors()->toArray());
    }

    public function test_validation_rejects_missing_required_fields(): void
    {
        $validator = Validator::make([], (new StorePhoneRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone_number', $validator->errors()->toArray());
        $this->assertArrayHasKey('type', $validator->errors()->toArray());
    }
}
