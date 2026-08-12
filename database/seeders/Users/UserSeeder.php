<?php

namespace Database\Seeders\Users;

use App\Models\Users\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'superadmin',
            'email' => 'superadmin@project99.com',
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('superadmin');
    }
}
