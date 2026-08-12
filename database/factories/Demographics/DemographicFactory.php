<?php

namespace Database\Factories\Demographics;

use App\Enums\EducationLevel;
use App\Enums\EmploymentStatus;
use App\Enums\Ethnicity;
use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\MaritalStatus;
use App\Enums\Race;
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
            'gender' => fake()->optional()->passthrough(fake()->randomElement(Gender::cases())->value),
            'race' => fake()->optional()->passthrough(fake()->randomElement(Race::cases())->value),
            'ethnicity' => fake()->optional()->passthrough(fake()->randomElement(Ethnicity::cases())->value),
            'language' => fake()->optional()->passthrough(fake()->randomElement(Language::cases())->value),
            'marital_status' => fake()->optional()->passthrough(fake()->randomElement(MaritalStatus::cases())->value),
            'education_level' => fake()->optional()->passthrough(fake()->randomElement(EducationLevel::cases())->value),
            'employment_status' => fake()->optional()->passthrough(fake()->randomElement(EmploymentStatus::cases())->value),
            'occupation' => fake()->optional()->jobTitle(),
            'income' => fake()->optional()->randomFloat(2, 0, 250000),
        ];
    }
}
