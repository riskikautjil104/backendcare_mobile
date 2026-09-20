<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super Admin Account
        User::updateOrCreate(
            ['email' => 'superadmin@rsud.ternate.go.id'],
            [
                'name' => 'Super Admin RSUD',
                'phone_number' => '081100001111',
                'password' => Hash::make('superadmin123'),
                'role' => 'superadmin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
