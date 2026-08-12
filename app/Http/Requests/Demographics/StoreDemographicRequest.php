<?php

namespace App\Http\Requests\Demographics;

use App\Enums\EducationLevel;
use App\Enums\EmploymentStatus;
use App\Enums\Ethnicity;
use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\MaritalStatus;
use App\Enums\Race;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'race' => ['nullable', Rule::enum(Race::class)],
            'ethnicity' => ['nullable', Rule::enum(Ethnicity::class)],
            'language' => ['nullable', Rule::enum(Language::class)],
            'marital_status' => ['nullable', Rule::enum(MaritalStatus::class)],
            'education_level' => ['nullable', Rule::enum(EducationLevel::class)],
            'employment_status' => ['nullable', Rule::enum(EmploymentStatus::class)],
            'occupation' => ['nullable', 'string', 'max:255'],
            'income' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
