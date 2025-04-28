<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role_id' => 1, // Assuming Admin role has ID = 1
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // use bcrypt hashing
            'is_active' => true,
            'created_by' => 'Seeder',
            'updated_by' => 'Seeder',
        ]);

        User::create([
            'name' => 'Manager User',
            'username' => 'manager',
            'email' => 'manager@example.com',
            'role_id' => 2, // Manager role
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_active' => true,
            'created_by' => 'Seeder',
            'updated_by' => 'Seeder',
        ]);

        User::create([
            'name' => 'Normal User',
            'username' => 'user',
            'email' => 'user@example.com',
            'role_id' => 3, // Normal user role
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_active' => true,
            'created_by' => 'Seeder',
            'updated_by' => 'Seeder',
        ]);
    
    }
}
