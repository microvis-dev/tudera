<?php

namespace App\Http\Controllers;

use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use App\Models\WorkspaceTable;
use App\Models\WorkspaceColumn;
use App\Models\WorkspaceRow;
use App\Models\TableValue;
use Exception;
use Illuminate\Http\Request;
use Log;
use Illuminate\Support\Facades\DB;

class WorkspaceController extends Controller
{
    function index(Request $request) {
        try {
            $user = $request->user();
            $user_workspaces = $user->workspaces()->get(['name', 'workspace_id'])->toArray();

            return inertia('Workspaces/Index', [
                'user_workspaces' => $user_workspaces
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->intended('/')->with('error', 'An error occurred while retrieving your workspaces. Please try again later.');
        }
    }

    function create(Request $request) {
        $user = $request->user();
        $workspaces = $user->workspaces;

        if ($workspaces->count() == 0) {
            return inertia('Setup/CreateWorkspace');
        }
        return inertia('Workspaces/Create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        DB::transaction(function () use ($request) {
            $workspace = new Workspace();
            $workspace->name = $request->input('name');
            $workspace->save();
            
            $users_to_workspace = new UsersToWorkspace();

            $users_to_workspace->workspace_id = $workspace->id;
            $users_to_workspace->user_id = $request->user()->id;
            $users_to_workspace->save();

            // create leads
            //$this->createDefaultLeadsTable($workspace);

        });

        return redirect()->route('index')->with('success', 'Workspace created successfully!');
    }

    function show() {
        
    }

    public function destroy(Request $request, $id) {
        try {
            $user = $request->user();
    
            $workspace = $user->workspaces()->find($id);
    
            if (!$workspace) {
                return redirect()->back()->with('error', 'Workspace not found or you do not have permission to delete it.');
            }
            
            if ($workspace->users()->count() == 1) {
                $workspace->delete();
            } else {
                $user->workspaces()->detach($workspace->id);
            }

            return redirect()->back()->with('success', 'Workspace deleted successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the workspace. Please try again later.');
        }
    }

    public function update(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S.*$/'
        ]);
        $workspace_id = $request->workspace_id;
    
        try {
            $user = $request->user();
            $workspace = $user->workspaces()->find($workspace_id);
    
            if (!$workspace) {
                return redirect()->back()->with('error', 'Workspace not found or you do not have permission to edit it.');
            }
    
            $workspace->update(['name' => strip_tags($request->name)]);
    
            return redirect()->back()->with('success', 'Workspace name updated successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the workspace name.');
        }
    }

    private function createDefaultLeadsTable($workspace) {
        try {
            // Create leads table
            $leadsTable = new WorkspaceTable();
            $leadsTable->workspace_id = $workspace->id;
            $leadsTable->name = 'Leads';
            $leadsTable->save();
            
            // Import the enum if needed
            // use App\WorkspaceColumnTypeEnum;
            
            // Create columns with appropriate types
            $columnDefinitions = [
                'Lead' => 'TEXT',
                'Status' => 'TEXT',
                'Create a contact' => 'TEXT',
                'Company' => 'TEXT',
                'Title' => 'TEXT',
                'Email' => 'TEXT',
                'Phone' => 'TEXT',
                'Last interaction' => 'TEXT',
                'Active sequences' => 'TEXT'
            ];
            
            $columns = [];
            $index = 0;
            foreach ($columnDefinitions as $name => $type) {
                $column = new WorkspaceColumn();
                $column->table_id = $leadsTable->id;
                $column->name = $name;
                $column->type = $type; // Use the appropriate type value
                $column->order = $index++;
                $column->save();
                $columns[$name] = $column;
            }
            
            // Rest of your code remains the same
            // Create rows with data
            $rowData = [
                'Robert Thomson' => [
                    'Create a contact' => 'Move to Contacts',
                    'Company' => 'Apple',
                    'Title' => 'COO',
                    'Email' => 'robert@apple.com',
                    'Phone' => '+1 202 795 3213',
                    'Last interaction' => 'Feb 28'
                ],
                'Steven Scott' => [
                    'Create a contact' => 'Move to Contacts',
                    'Company' => 'Microsoft',
                    'Title' => 'Team leader',
                    'Email' => 'steven@microsoft.com',
                    'Phone' => '+1 202 795 3265',
                    'Last interaction' => 'Dec 16, 2024'
                ]
            ];
        
            $rowOrder = 0;
            foreach ($rowData as $rowName => $values) {
                // Create row
                $row = new WorkspaceRow();
                $row->table_id = $leadsTable->id;
                $row->name = $rowName;
                $row->order = $rowOrder++;
                $row->save();
                
                // Add lead name as a value for the "Lead" column
                if (isset($columns['Lead'])) {
                    $leadValue = new TableValue();
                    $leadValue->row_id = $row->id;
                    $leadValue->column_id = $columns['Lead']->id;
                    $leadValue->value = $rowName;
                    $leadValue->save();
                }
                
                // Add all other values
                foreach ($values as $columnName => $value) {
                    if (isset($columns[$columnName])) {
                        $tableValue = new TableValue();
                        $tableValue->row_id = $row->id;
                        $tableValue->column_id = $columns[$columnName]->id;
                        $tableValue->value = $value;
                        $tableValue->save();
                    }
                }
            }
            
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
