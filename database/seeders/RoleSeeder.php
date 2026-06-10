<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'Manajemen',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'Staf Gudang',
            'guard_name' => 'web'
        ]);
    }
}
