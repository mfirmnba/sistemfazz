<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('Ladisman12'),
                'role' => 'owner',
            ]
        );

        User::updateOrCreate(
            ['email' => 'driver@gmail.com'],
            [
                'name' => 'Driver 1',
                'password' => Hash::make('password'),
                'role' => 'driver',
            ]
        );

        User::updateOrCreate(
            ['email' => 'produksi@gmail.com'],
            [
                'name' => 'Produksi 1',
                'password' => Hash::make('password'),
                'role' => 'produksi',
            ]
        );

        User::updateOrCreate(
            ['email' => 'driver2@gmail.com'],
            [
                'name' => 'Driver 2',
                'password' => Hash::make('password'),
                'role' => 'driver',
            ]
        );
    }
}
