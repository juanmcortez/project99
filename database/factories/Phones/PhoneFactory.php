<?php

namespace Database\Factories\Phones;

use App\Enums\PhoneType;
use App\Models\Phones\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Phone>
 */
class PhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_number' => '+1'.fake()->numerify('##########'),
            'type' => fake()->randomElement(PhoneType::cases())->value,
        ];
    }
}
