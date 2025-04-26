<?php

namespace Database\Seeders;

use App\Models\StatusSelectValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSelectValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'None', // nem fog szerepelni a kanbanban ha a status None
            'backlog',
            'in-progress',
            'review',
            'done',
        ];

        foreach ($statuses as $status) {
            StatusSelectValue::create([
                'column_id' => null,
                'value' => $status,
            ]);
        }
    }
}
