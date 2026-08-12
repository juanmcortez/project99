<?php

namespace App\Actions\Fortify;

use App\Http\Requests\Users\StoreUserRequest;
use App\Models\Users\User;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, (new StoreUserRequest)->rules())->validate();

        return UserService::create($input)->assignRole('user');
    }
}
