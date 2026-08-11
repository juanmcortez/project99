<?php

namespace Database\Factories\Phones;

use App\Models\Phones\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Phone>
 */
class PhoneFactory extends Factory
{
    /**
     * @var list<string>
     */
    private const TYPES = ['mobile', 'home', 'work', 'fax', 'emergency'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_number' => '+1'.fake()->numerify('##########'),
            'type' => fake()->randomElement(self::TYPES),
        ];
    }
}
