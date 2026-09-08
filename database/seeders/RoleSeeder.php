<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create(['name' => 'manage users', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage tournaments', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage matches', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage venues', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage topup', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage content', 'guard_name' => 'web']);
        Permission::create(['name' => 'view reports', 'guard_name' => 'web']);

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);

        $adminRole->givePermissionTo(Permission::all());

        User::create([
            'name' => 'Admin UniPlay',
            'email' => 'admin@uniplay.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ])->assignRole('admin');

        // Primary admin login (updated credentials)
        User::updateOrCreate(
            ['email' => 'adminidan@uniplay.com'],
            [
                'name' => 'Admin IIDAN',
                'password' => bcrypt('12345'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        )->assignRole('admin');

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ])->assignRole('user');
    }
}
