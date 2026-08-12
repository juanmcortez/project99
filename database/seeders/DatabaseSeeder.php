<?php

namespace Database\Seeders;

use Database\Seeders\Addresses\AddressSeeder;
use Database\Seeders\Demographics\DemographicSeeder;
use Database\Seeders\Phones\PhoneSeeder;
use Database\Seeders\Roles\RoleAndPermissionSeeder;
use Database\Seeders\Users\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            DemographicSeeder::class,
            AddressSeeder::class,
            PhoneSeeder::class,
        ]);
    }
}
