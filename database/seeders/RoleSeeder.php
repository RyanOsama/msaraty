<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'student']);
        Role::create(['name' => 'driver']);
        Role::create(['name' => 'admin']);
    }
}