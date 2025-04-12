<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'workspace.create',
            'workspace.read',
            'workspace.update',
            'workspace.delete',

            'table.create',
            'table.read',
            'table.update',
            'table.delete',

            'value.create',
            'value.read',
            'value.update',
            'value.delete',

            'calendar.create',
            'calendar.read',
            'calendar.update',
            'calendar.delete',
        ];

        DB::table('permissions')->insert(array_map(function ($name) {
            return [
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $permissions));
    }
}
