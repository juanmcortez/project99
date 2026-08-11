<?php

namespace App\Http\Requests\Phones;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePhoneRequest extends FormRequest
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
            'phone_number' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/'],
            'type' => ['required', 'string', 'max:50'],
        ];
    }
}
