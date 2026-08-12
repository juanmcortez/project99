<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit.profile.username')
            || $this->user()->can('edit.profile.email');
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $rules = [];

        if ($user->can('edit.profile.username')) {
            $rules['username'] = ['required', 'string', 'max:128', Rule::unique('users', 'username')->ignore($user)];
        }

        if ($user->can('edit.profile.email')) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)];
        }

        return $rules;
    }
}
