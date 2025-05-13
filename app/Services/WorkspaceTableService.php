<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceTable;

class WorkspaceTableService
{
    public static function initDefaultTables(Workspace $workspace): void
    {
        WorkspaceTable::where('workspace_id', null)->each(function ($table) use ($workspace) {
            $table->copy($workspace);
        });
    }
}
?>
