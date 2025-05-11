<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Search\UsersToWorkspaceSearch;
use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use Illuminate\Http\Request;

class UsersToWorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function datatable(Request $request)
    {
        $usersSearch = new UsersToWorkspaceSearch();
        $users = $usersSearch->search($request);

        $data = [];
        foreach ($users->get() as $user) {
            $data[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->pivot->role,
            ];
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $usersSearch->total,
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UsersToWorkspace $usersToWorkspace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsersToWorkspace $usersToWorkspace)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsersToWorkspace $usersToWorkspace)
    {
        //
    }
}
