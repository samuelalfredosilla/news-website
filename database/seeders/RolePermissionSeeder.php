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
        Permission::firstOrCreate(['name' => 'create news']);
        Permission::firstOrCreate(['name' => 'edit news']);
        Permission::firstOrCreate(['name' => 'delete news']); // Pastikan ini ada
        Permission::firstOrCreate(['name' => 'publish news']);

        // Permissions for Categories
        Permission::firstOrCreate(['name' => 'manage categories']);

        // Permissions for Users (only Admin)
        Permission::firstOrCreate(['name' => 'manage users']);

        // 2. Create Roles and assign Permissions
        // Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Editor Role
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        // Pastikan editor punya permission ini:
        $editorRole->givePermissionTo(['create news', 'edit news', 'delete news', 'publish news', 'manage categories']);

        // Wartawan (Reporter) Role
        $wartawanRole = Role::firstOrCreate(['name' => 'wartawan']);
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