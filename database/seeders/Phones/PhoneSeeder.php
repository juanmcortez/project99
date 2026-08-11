<?php

namespace Database\Seeders\Phones;

use App\Models\Demographics\Demographic;
use App\Models\Phones\Phone;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('username', 'superadmin')->first();

        if ($user === null) {
            return;
        }

        $demographic = $user->demographic;

        if (! $demographic instanceof Demographic || $demographic->phones()->exists()) {
            return;
        }

        Phone::factory()
            ->for($demographic)
            ->create([
                'phone_number' => '+12125551234',
                'type' => 'mobile',
            ]);
    }
}
