<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'vendor',
            'email' => 'vendor@email.com',
            'password' => Hash::make('vendor1123'),
            'role' => 'Vendor',
        ]);

        User::create([
            'name' => 'manager',
            'email' => 'manager@email.com',
            'password' => Hash::make('manager123'),
            'role' => 'Manager',
        ]);

        User::create([
            'name' => 'accountant',
            'email' => 'accountant@email.com',
            'password' => Hash::make('accountant123'),
            'role' => 'Accountant',
        ]);
    }
}