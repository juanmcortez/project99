<?php

namespace Tests\Feature\ActivityLogs;

use App\Enums\ActivityLogAction;
use App\Models\ActivityLogs\ActivityLog;
use App\Models\Users\User;
use App\Services\Demographics\DemographicService;
use Database\Seeders\Roles\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
    }

    private function actingAsWithActivityLogAccess(User $user): self
    {
        $user->assignRole('superadmin');

        return $this->actingAs($user);
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

    public function test_login_is_logged(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'login' => $user->email,
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::UserLogin->value,
        ]);

        $this->assertSame(1, ActivityLog::query()
            ->where('user_id', $user->id)
            ->where('action', ActivityLogAction::UserLogin->value)
            ->count());
    }

    public function test_logout_is_logged(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('logout'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::UserLogout->value,
        ]);

        $this->assertSame(1, ActivityLog::query()
            ->where('user_id', $user->id)
            ->where('action', ActivityLogAction::UserLogout->value)
            ->count());
    }

    public function test_registration_is_logged(): void
    {
        $this->post('/register', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::UserRegistered->value,
        ]);
    }

    public function test_profile_update_is_logged(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['edit.profile.username', 'edit.profile.email']);

        $this->actingAs($user)->put(route('user-profile-information.update'), [
            'username' => 'updateduser',
            'email' => $user->email,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::UserProfileUpdated->value,
        ]);
    }

    public function test_demographic_create_is_logged(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('profile.demographic.store'), $this->validDemographicData());

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::DemographicCreated->value,
        ]);
    }

    public function test_demographic_update_and_profile_picture_are_logged(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        DemographicService::createFor($user, $this->validDemographicData());

        $this->actingAs($user)->put(route('profile.demographic.update'), array_merge($this->validDemographicData(), [
            'first_name' => 'Jane',
            'profile_picture' => UploadedFile::fake()->create('avatar.jpg', 100, 'image/jpeg'),
        ]));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::DemographicUpdated->value,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::ProfilePictureUpdated->value,
        ]);
    }

    public function test_address_create_and_update_are_logged(): void
    {
        $user = User::factory()->create();
        DemographicService::createFor($user, $this->validDemographicData());

        $this->actingAs($user)->post(route('profile.address.store'), $this->validAddressData());

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::AddressCreated->value,
        ]);

        $this->actingAs($user)->put(route('profile.address.update'), [
            'street_line_1' => '456 Oak Ave',
            'city' => 'Chicago',
            'state' => 'IL',
            'zip_code' => '60601',
            'country' => 'US',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::AddressUpdated->value,
        ]);
    }

    public function test_phone_create_update_and_delete_are_logged(): void
    {
        $user = User::factory()->create();
        $demographic = DemographicService::createFor($user, $this->validDemographicData());

        $this->actingAs($user)->post(route('profile.phone.store'), [
            'phone_number' => '+12125551234',
            'type' => 'mobile',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::PhoneCreated->value,
        ]);

        $phone = $demographic->phones()->first();
        $this->assertNotNull($phone);

        $this->actingAs($user)->put(route('profile.phone.update', $phone), [
            'phone_number' => '+13105559876',
            'type' => 'work',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::PhoneUpdated->value,
        ]);

        $this->actingAs($user)->delete(route('profile.phone.destroy', $phone));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::PhoneDeleted->value,
        ]);
    }

    public function test_activity_log_data_endpoint_returns_datatables_json(): void
    {
        $user = User::factory()->create();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => ActivityLogAction::UserLogin,
            'description' => 'User logged in',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'created_at' => now(),
        ]);

        $response = $this->actingAsWithActivityLogAccess($user)->getJson(route('activity-log.data'));

        $response->assertOk();
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                [
                    'created_at',
                    'username',
                    'action_label',
                    'description',
                    'ip_address',
                ],
            ],
        ]);
    }

    public function test_activity_log_index_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAsWithActivityLogAccess($user)->get(route('activity-log.index'));

        $response->assertOk();
        $response->assertSee('Activity Log');
        $response->assertSee('activity-log-table');
    }

    public function test_account_deletion_is_logged(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('profile.destroy'), [
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'action' => ActivityLogAction::UserAccountDeleted->value,
        ]);
    }

    public function test_username_is_shown_in_datatables_response_after_user_is_soft_deleted(): void
    {
        $viewer = User::factory()->create();
        $deleted = User::factory()->create(['username' => 'deleteduser']);

        ActivityLog::create([
            'user_id' => $deleted->id,
            'action' => ActivityLogAction::UserLogin,
            'description' => 'User logged in',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'created_at' => now(),
        ]);

        $deleted->delete();

        $response = $this->actingAsWithActivityLogAccess($viewer)->getJson(route('activity-log.data'));

        $response->assertOk();
        $response->assertJsonFragment(['username' => 'deleteduser']);
    }
}
