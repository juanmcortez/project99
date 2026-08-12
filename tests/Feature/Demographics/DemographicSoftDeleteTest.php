<?php

namespace Tests\Feature\Demographics;

use App\Models\Addresses\Address;
use App\Models\Demographics\Demographic;
use App\Models\Phones\Phone;
use App\Models\Users\User;
use App\Services\Addresses\AddressService;
use App\Services\Demographics\DemographicService;
use App\Services\Phones\PhoneService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DemographicSoftDeleteTest extends TestCase
{
    use LazilyRefreshDatabase;

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

    public function test_deleting_demographic_soft_deletes_address_and_phones(): void
    {
        $user = User::factory()->create();
        $demographic = DemographicService::createFor($user, $this->validDemographicData());
        $address = AddressService::createFor($demographic, $this->validAddressData());
        $phone = PhoneService::createFor($demographic, [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);

        DemographicService::delete($demographic);

        $this->assertSoftDeleted('demographics', ['id' => $demographic->id]);
        $this->assertSoftDeleted('addresses', ['id' => $address->id]);
        $this->assertSoftDeleted('phones', ['id' => $phone->id]);
    }

    public function test_soft_deleted_demographic_is_hidden_from_normal_queries(): void
    {
        $user = User::factory()->create();
        $demographic = DemographicService::createFor($user, $this->validDemographicData());
        AddressService::createFor($demographic, $this->validAddressData());
        PhoneService::createFor($demographic, [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);

        DemographicService::delete($demographic);

        $this->assertNull(Demographic::query()->find($demographic->id));
        $this->assertSame(0, Address::query()->count());
        $this->assertSame(0, Phone::query()->count());
    }

    public function test_soft_deleted_demographic_is_accessible_with_trashed(): void
    {
        $user = User::factory()->create();
        $demographic = DemographicService::createFor($user, $this->validDemographicData());
        $address = AddressService::createFor($demographic, $this->validAddressData());
        $phone = PhoneService::createFor($demographic, [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);

        DemographicService::delete($demographic);

        $this->assertNotNull(Demographic::withTrashed()->find($demographic->id));
        $this->assertNotNull(Address::withTrashed()->find($address->id));
        $this->assertNotNull(Phone::withTrashed()->find($phone->id));
    }
}
