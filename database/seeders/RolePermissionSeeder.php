<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions
        // Permissions for News Articles
        Permission::create(['name' => 'create news']);
        Permission::create(['name' => 'edit news']);
        Permission::create(['name' => 'delete news']);
        Permission::create(['name' => 'publish news']); // Hanya untuk Editor/Admin

        // Permissions for Categories
        Permission::create(['name' => 'manage categories']);

        // Permissions for Users (only Admin)
        Permission::create(['name' => 'manage users']);

        // 2. Create Roles and assign Permissions
        // Admin Role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); // Admin memiliki semua izin

        // Editor Role
        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo(['create news', 'edit news', 'delete news', 'publish news', 'manage categories']);

        // Wartawan (Reporter) Role
        $wartawanRole = Role::create(['name' => 'wartawan']);
        $wartawanRole->givePermissionTo(['create news', 'edit news']); // Wartawan hanya bisa membuat & mengedit miliknya sendiri

        // 3. Create initial users and assign roles
        // Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Ganti dengan password kuat di produksi
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        // Editor User
        $editorUser = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $editorUser->assignRole('editor');

        // Wartawan User
        $wartawanUser = User::firstOrCreate(
            ['email' => 'wartawan@example.com'],
            [
                'name' => 'Wartawan User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $wartawanUser->assignRole('wartawan');
    }
}