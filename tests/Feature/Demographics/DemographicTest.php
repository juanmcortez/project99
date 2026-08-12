<?php

namespace Tests\Feature\Demographics;

use App\Exceptions\DemographicAlreadyExistsException;
use App\Http\Requests\Demographics\StoreDemographicRequest;
use App\Models\Users\User;
use App\Services\Demographics\DemographicService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DemographicTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validDemographicData(): array
    {
        return [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'birthdate' => '1990-05-15',
        ];
    }

    public function test_demographic_can_be_created_for_user(): void
    {
        $user = User::factory()->create();

        $demographic = DemographicService::createFor($user, $this->validDemographicData());

        $this->assertSame('Jane', $demographic->first_name);
        $this->assertSame('Doe', $demographic->last_name);
        $this->assertTrue($user->demographic->is($demographic));
    }

    public function test_full_name_combines_available_name_parts(): void
    {
        $user = User::factory()->create();

        $demographic = DemographicService::createFor($user, array_merge($this->validDemographicData(), [
            'middle_name' => 'Marie',
        ]));

        $this->assertSame('Jane Marie Doe', $demographic->full_name);
    }

    public function test_full_name_omits_missing_middle_name(): void
    {
        $user = User::factory()->create();

        $demographic = DemographicService::createFor($user, $this->validDemographicData());

        $this->assertSame('Jane Doe', $demographic->full_name);
    }

    public function test_validation_rejects_missing_mandatory_fields(): void
    {
        $validator = Validator::make([], (new StoreDemographicRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('first_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('last_name', $validator->errors()->toArray());
        $this->assertArrayHasKey('birthdate', $validator->errors()->toArray());
    }

    public function test_create_for_throws_when_demographic_already_exists(): void
    {
        $user = User::factory()->create();

        DemographicService::createFor($user, $this->validDemographicData());

        $this->expectException(DemographicAlreadyExistsException::class);

        DemographicService::createFor($user, $this->validDemographicData());
    }

    public function test_social_security_is_encrypted_in_database(): void
    {
        $user = User::factory()->create();
        $plainSsn = '123456789';

        $demographic = DemographicService::createFor($user, array_merge($this->validDemographicData(), [
            'social_security' => $plainSsn,
        ]));

        $rawValue = DB::table('demographics')
            ->where('id', $demographic->id)
            ->value('social_security');

        $this->assertNotSame($plainSsn, $rawValue);
        $this->assertSame($plainSsn, $demographic->social_security);
    }
}
