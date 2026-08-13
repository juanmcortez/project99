<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name'),
                $this->notBeyondActorPermissions(),
            ],
        ];
    }

    /**
     * Reject assigning a role whose permissions exceed the acting user's own,
     * preventing privilege escalation (e.g. an admin granting superadmin).
     */
    private function notBeyondActorPermissions(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $role = Role::where('name', $value)->first();

            if ($role === null) {
                return;
            }

            $actorPermissions = $this->user()->getAllPermissions()->pluck('name')->all();
            $rolePermissions = $role->permissions->pluck('name')->all();
            $missing = array_diff($rolePermissions, $actorPermissions);

            if ($missing !== []) {
                $fail('You cannot assign a role with permissions beyond your own.');
            }
        };
    }
}
