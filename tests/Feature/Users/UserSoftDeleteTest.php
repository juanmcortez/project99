<?php

namespace Tests\Feature\Users;

use App\Models\Demographics\Demographic;
use App\Models\Users\User;
use App\Services\Addresses\AddressService;
use App\Services\Demographics\DemographicService;
use App\Services\Phones\PhoneService;
use App\Services\Users\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSoftDeleteTest extends TestCase
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

    public function test_deleting_user_soft_deletes_demographic_address_and_phones(): void
    {
        $user = User::factory()->create();
        $demographic = DemographicService::createFor($user, $this->validDemographicData());
        $address = AddressService::createFor($demographic, $this->validAddressData());
        $phone = PhoneService::createFor($demographic, [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);

        UserService::delete($user);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
        $this->assertSoftDeleted('demographics', ['id' => $demographic->id]);
        $this->assertSoftDeleted('addresses', ['id' => $address->id]);
        $this->assertSoftDeleted('phones', ['id' => $phone->id]);
    }

    public function test_soft_deleted_user_is_hidden_from_normal_queries(): void
    {
        $user = User::factory()->create();
        $demographic = DemographicService::createFor($user, $this->validDemographicData());

        UserService::delete($user);

        $this->assertNull(User::query()->find($user->id));
        $this->assertNull(Demographic::query()->find($demographic->id));
    }

    public function test_soft_deleted_user_is_accessible_with_trashed(): void
    {
        $user = User::factory()->create();
        $demographic = DemographicService::createFor($user, $this->validDemographicData());

        UserService::delete($user);

        $this->assertNotNull(User::withTrashed()->find($user->id));
        $this->assertNotNull(Demographic::withTrashed()->find($demographic->id));
    }
}
