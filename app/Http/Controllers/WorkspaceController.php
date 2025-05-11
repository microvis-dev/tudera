<?php

namespace App\Http\Controllers;

use App\Enums\RolesEnum;
use App\Models\Role;
use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use App\Models\WorkspaceTable;
use App\Services\WorkspaceService;
use App\Services\WorkspaceTableService;
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

    function get(Request $request) {
        $workspace_id = session('current_workspace_id') ?? $request->user()->workspaces()->first()->id;
        return response()->json($workspace_id);
    }

    function create(Request $request) {
        $user = $request->user();
        $workspaces = $user->workspaces;
        return inertia('Setup/CreateWorkspace');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $workspace = new Workspace();
            $workspace->name = $request->input('name');
            $workspace->save();
            WorkspaceTableService::initDefaultTables($workspace);
        });

        return redirect()->route('dashboard.index')->with('success', 'Workspace created successfully!');
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
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the workspace. Please try again later.');
        }
    }

    public function update(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S.*$/'
        ]);
        $workspace_id = $request->workspace;

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

    public function change(Request $request) {
        $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        $workspace = Workspace::find($request->workspace_id);
        return WorkspaceService::change($request->user(), $workspace) ? back() : back()->with('error', 'Workspace not found or you do not have permission to change it.');
    }

    public function settings(Request $request, $id)
    {
        $user = $request->user();
        $workspace = $user->workspaces()->findOrFail($id);

        $workspaceUsers = UsersToWorkspace::where('workspace_id', $id)
            ->with(['user', 'role'])
            ->get()
            ->map(function ($userWorkspace) {
                return [
                    'id' => $userWorkspace->user->id,
                    'name' => $userWorkspace->user->name,
                    'email' => $userWorkspace->user->email,
                    'role' => $userWorkspace->user->roles,
                    'joined_at' => $userWorkspace->created_at->format('Y-m-d'),
                ];
            });

        return inertia('Workspaces/Settings/Index', [
            'workspace' => $workspace,
            'url' => url('api/v1/users-to-workspace/datatable')
        ]);
    }
}

