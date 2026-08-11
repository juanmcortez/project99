<?php

namespace Database\Seeders\Demographics;

use App\Models\Demographics\Demographic;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class DemographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('username', 'superadmin')->first();

        if ($user === null || $user->demographic()->exists()) {
            return;
        }

        Demographic::factory()
            ->for($user, 'demographicable')
            ->create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'birthdate' => '1990-01-01',
            ]);
    }
}
