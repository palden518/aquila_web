<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'access admin panel',
            'manage users',
            'manage roles',
            'manage settings',
            'manage homepage content',
            'manage menu',
            'manage packages',
            'manage orders',
            'manage catering',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $owner = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $owner->syncPermissions($permissions);

        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'access admin panel',
            'manage settings',
            'manage homepage content',
            'manage menu',
            'manage packages',
            'manage orders',
            'manage catering',
        ]);
    }
}
