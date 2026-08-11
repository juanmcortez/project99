<?php

namespace Tests\Feature\Users;

use App\Http\Requests\Users\StoreUserRequest;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validUserData(): array
    {
        return [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
    }

    public function test_valid_user_data_passes_validation(): void
    {
        $validator = Validator::make(
            $this->validUserData(),
            (new StoreUserRequest)->rules()
        );

        $this->assertFalse($validator->fails());
    }

    public function test_username_over_128_chars_fails_validation(): void
    {
        $data = $this->validUserData();
        $data['username'] = Str::random(129);

        $validator = Validator::make($data, (new StoreUserRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('username', $validator->errors()->toArray());
    }

    public function test_duplicate_username_fails_validation(): void
    {
        User::factory()->create(['username' => 'existinguser']);

        $data = $this->validUserData();
        $data['username'] = 'existinguser';

        $validator = Validator::make($data, (new StoreUserRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('username', $validator->errors()->toArray());
    }
}
