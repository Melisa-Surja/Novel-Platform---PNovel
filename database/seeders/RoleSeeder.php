<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // remove cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Permissions
        // translator permissions
        $post_methods = ['create', 'edit', 'edit all', 'delete', 'delete all', 'publish', 'review'];
        $post_types = ['series', 'novelChapter', 'comment'];
        $post_permissions = [];
        foreach ($post_types as $type) {
            foreach ($post_methods as $method) {
                $post_permissions[] = "$method $type";
            }
        }
        // admin permissions
        $admin_permissions = ['manage settings', 'manage users', 'manage ads', 'earn ads', 'access backend'];
        $permissions = [...$post_permissions, ...$admin_permissions];
        $insert_permissions = array_map(function($p) {
            return ['name' => $p, 'guard_name' => 'web'];
        }, $permissions);
        Permission::insert($insert_permissions);

        // Roles
        $roles = [
            'Reader'    => ['create comment', 'edit comment', 'delete comment'],
            'Poster' => ['create series', 'edit series', 'create novelChapter', 'edit novelChapter', 'publish novelChapter', 'earn ads', 'access backend'],
        ];
        foreach ($roles as $role => $p) {
            $role = Role::create(['name' => $role])->givePermissionTo($p);
        }
        $role = Role::create(['name' => 'Super Admin']);
    }
}
