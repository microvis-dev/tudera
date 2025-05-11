<?php

namespace Database\Seeders;

use App\Models\WorkspaceColumn;
use App\Models\WorkspaceTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultTableSeeder extends Seeder
{
    private function generateLeads(): bool
    {
        $columns = [
            ['datetime', 'Date'],
            ['string', 'Deal Name'],
            ['string', 'Lead Source'],
            ['integer', 'Deal Amount'],
            ['status', 'Status']
        ];

        WorkspaceTable::$bypassProtectionCheck = true;
        $table = new WorkspaceTable([
            'workspace_id' => null,
            'name' => 'Leads',
            'protected' => true,
        ]);

        $table->save();

        $colModels = [];

        WorkspaceColumn::$bypassProtectionCheck = true;
        foreach ($columns as $i => $column) {
            $colModels[] = new WorkspaceColumn([
                'table_id' => $table->id,
                'name' => $column[1],
                'type' => $column[0],
                'order' => $i + 1,
            ]);
        }

        $table->columns()->saveMany($colModels);

        return true;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->generateLeads();
    }
}
