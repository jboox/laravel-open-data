<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Hapus cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definisi permission
        $permissions = [
            'dataset.view', 'dataset.create', 'dataset.edit', 'dataset.delete',
            'category.view', 'category.manage',
            'article.view', 'article.create', 'article.edit', 'article.delete',
            'region.view', 'region.manage',
            'user.manage'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $contributor = Role::firstOrCreate(['name' => 'contributor']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Assign permission ke roles
        $admin->givePermissionTo(Permission::all());

        $contributor->givePermissionTo([
            'dataset.view', 'dataset.create', 'dataset.edit',
            'article.view', 'article.create', 'article.edit'
        ]);

        $user->givePermissionTo([
            'dataset.view', 'category.view', 'article.view', 'region.view'
        ]);
    }
}
