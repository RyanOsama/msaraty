<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'admin'], // لا يتكرر
            [
                'password'   => Hash::make('123456789'), 
                'role_id'    => 3, 
                'full_name'  => 'ريان بن عسله',
                'phone'      => '780791584',
            ]
        );
    }
}