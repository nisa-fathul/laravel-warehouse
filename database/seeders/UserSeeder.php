<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@giken.com'
            ],
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'password' => Hash::make('password')
            ]
        );

        $management = User::firstOrCreate(
            [
                'email' => 'manajemen@giken.com'
            ],
            [
                'username' => 'manajemen',
                'name' => 'Manajemen',
                'password' => Hash::make('password')
            ]
        );

        $warehouse = User::firstOrCreate(
            [
                'email' => 'gudang@giken.com'
            ],
            [
                'username' => 'gudang',
                'name' => 'Staf Gudang',
                'password' => Hash::make('password')
            ]
        );

        $admin->syncRoles(['Admin']);

        $management->syncRoles(['Manajemen']);

        $warehouse->syncRoles(['Staf Gudang']);
    }
}
