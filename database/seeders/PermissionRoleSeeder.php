<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::pluck('id', 'name')->toArray();
        $permissions = Permission::pluck('id', 'name')->toArray();

        $role_permission_map = [
            'owner' => [
                'workspace.create', 'workspace.read', 'workspace.update', 'workspace.delete',
                'table.create', 'table.read', 'table.update', 'table.delete',
                'value.create', 'value.read', 'value.update', 'value.delete',
                'calendar.create', 'calendar.read', 'calendar.update', 'calendar.delete',
            ],
            'editor' => [
                'table.create', 'table.read', 'table.update', 'table.delete',
                'value.create', 'value.read', 'value.update', 'value.delete',
                'calendar.create', 'calendar.read', 'calendar.update', 'calendar.delete',
            ],
            'viewer' => [
                'workspace.read',
                'table.read',
                'value.read',
                'calendar.read',
            ],
        ];

        foreach ($role_permission_map as $role => $perms) {
            foreach ($perms as $perm) {
                DB::table('permission_roles')->insert([
                    'role_id' => $roles[$role],
                    'permission_id' => $permissions[$perm],
                ]);
            }
        }
    }
}
