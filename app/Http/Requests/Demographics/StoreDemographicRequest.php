<?php

namespace App\Http\Requests\Demographics;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDemographicRequest extends FormRequest
{
    protected $errorBag = 'updateDemographic';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before:today'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'social_security' => ['nullable', 'string', 'max:11'],
            'gender' => ['nullable', 'string', 'max:64'],
            'race' => ['nullable', 'string', 'max:64'],
            'ethnicity' => ['nullable', 'string', 'max:64'],
            'language' => ['nullable', 'string', 'max:64'],
            'marital_status' => ['nullable', 'string', 'max:64'],
            'education_level' => ['nullable', 'string', 'max:64'],
            'employment_status' => ['nullable', 'string', 'max:64'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'income' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
