<?php

namespace Database\Seeders\Addresses;

use App\Models\Addresses\Address;
use App\Models\Demographics\Demographic;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
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

        if (! $demographic instanceof Demographic || $demographic->address()->exists()) {
            return;
        }

        Address::factory()
            ->for($demographic)
            ->create([
                'street_line_1' => '123 Main St',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62701',
                'country' => 'US',
            ]);
    }
}
