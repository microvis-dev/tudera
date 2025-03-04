<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UsersToWorkspace;
use App\Models\Workspace;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $workspace = Workspace::factory()->create([
            'name' => 'Test Workspace'
        ]);

        UsersToWorkspace::factory()->create([
            'user_id' => $user->id,
            'workspace_id' => $workspace->id
        ]);
    }
}
