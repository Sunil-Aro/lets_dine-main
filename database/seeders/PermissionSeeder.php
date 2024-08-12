<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // user permissions
        Permission::create(['name' => 'user_show','guard_name' => 'admin']);
        Permission::create(['name' => 'user_create','guard_name' => 'admin']);
        Permission::create(['name' => 'user_edit','guard_name' => 'admin']);
        Permission::create(['name' => 'user_delete','guard_name' => 'admin']);

        // role permissions
        Permission::create(['name' => 'role_show','guard_name' => 'admin']);
        Permission::create(['name' => 'role_create','guard_name' => 'admin']);
        Permission::create(['name' => 'role_edit','guard_name' => 'admin']);
        Permission::create(['name' => 'role_delete','guard_name' => 'admin']);

        // permission permissions
        Permission::create(['name' => 'permission_show','guard_name' => 'admin']);
        Permission::create(['name' => 'permission_create','guard_name' => 'admin']);
        Permission::create(['name' => 'permission_edit','guard_name' => 'admin']);
        Permission::create(['name' => 'permission_delete','guard_name' => 'admin']);

        // article permissions



        $role1 = Role::create(['name' => 'Super Admin','guard_name' => 'admin']);
        $role1->givePermissionTo(Permission::all());

        $user = \App\Models\Admin::factory()->create([
            'name' => 'Abishek',
            'email' => 'abiarun7708@gmail.com',
        ]);
        $user->assignRole($role1);

    }
}
