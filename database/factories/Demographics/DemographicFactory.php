<?php

namespace Database\Factories\Demographics;

use App\Models\Demographics\Demographic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Demographic>
 */
class DemographicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'middle_name' => fake()->optional()->firstName(),
            'birthdate' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'gender' => fake()->optional()->randomElement(['male', 'female', 'non-binary', 'other']),
            'race' => fake()->optional()->word(),
            'ethnicity' => fake()->optional()->word(),
            'language' => fake()->optional()->randomElement(['en', 'es', 'fr']),
            'marital_status' => fake()->optional()->randomElement(['single', 'married', 'divorced', 'widowed']),
            'education_level' => fake()->optional()->randomElement(['high_school', 'bachelor', 'master', 'doctorate']),
            'employment_status' => fake()->optional()->randomElement(['employed', 'unemployed', 'self_employed', 'retired']),
            'occupation' => fake()->optional()->jobTitle(),
            'income' => fake()->optional()->randomFloat(2, 0, 250000),
        ];
    }
}
