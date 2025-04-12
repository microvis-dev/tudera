<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'owner', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'editor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'viewer', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
