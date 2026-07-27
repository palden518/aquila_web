<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeds the 2 roles (Owner, Manager) and their permissions.
 *
 * 'access admin panel' is the baseline gate — every admin route requires
 * this FIRST, before any specific permission is checked. Both roles get it.
 *
 * Run standalone with:
 *   php artisan db:seed --class=RolePermissionSeeder
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'access admin panel',        // baseline gate — required for ANY /admin route
            'manage users',              // create staff accounts, assign roles
            'manage roles',              // create/edit roles & permissions
            'manage settings',           // general site settings
            'manage homepage content',   // hero text, events teaser
            'manage menu',               // menu categories + items
            'manage packages',           // specials & packages
            'manage orders',             // view/update customer orders
            'manage catering',           // view/respond to catering enquiries
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $owner   = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);

        // Owner: full control, including staff/user and role management.
        $owner->syncPermissions($permissions);

        // Manager: baseline access + day-to-day operations only.
        // NOT users, roles, or settings.
        $manager->syncPermissions([
            'access admin panel',
            'manage homepage content',
            'manage menu',
            'manage packages',
            'manage orders',
            'manage catering',
        ]);
    }
}
