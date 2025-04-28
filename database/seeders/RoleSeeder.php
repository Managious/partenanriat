<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'created_by' => 'Seeder'],
            ['name' => 'Manager', 'created_by' => 'Seeder'],
            ['name' => 'User', 'created_by' => 'Seeder'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
