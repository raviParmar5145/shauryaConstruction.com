<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'role' => 2, // admin
            'phone' => '123456789',
            'password' => Hash::make('123456'), // Password hashing
        ]);

        User::create([
            'name' => 'Ravi',
            'email' => 'ravi@gmail.com',
            'role' => 2, // Admin
            'phone' => '7874728723',
            'password' => Hash::make('123456'), // Password hashing
        ]);
    }
}
