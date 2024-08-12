<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BranchUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // user permissions
        Permission::create(['name' => 'article_show','guard_name' => 'web']);
        Permission::create(['name' => 'article_create','guard_name' => 'web']);
        Permission::create(['name' => 'article_edit','guard_name' => 'web']);
        Permission::create(['name' => 'article_delete','guard_name' => 'web']);

        $role2 = Role::create(['name' => 'Branch'])
        ->givePermissionTo(['article_show','article_create','article_edit','article_delete']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Branch',
            'email' => 'branch@branch.com',
        ]);
        $user->assignRole($role2);

    }
}
