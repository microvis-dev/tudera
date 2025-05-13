<?php

namespace App\Models\Search;

use App\Enums\RolesEnum;
use App\Models\Workspace;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\Builder;

class
UsersToWorkspaceSearch extends Model
{
    private int $total = 0;
    private ?BelongsToMany $query = null;

    public function search(Request $request)
    {
        $workspaceId = $request->input('workspace_id');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search');
        $filters = $request->input('filters', []);

        if (!$workspace = Workspace::find($workspaceId)) {
            throw new NotFoundHttpException('Workspace not found.');
        }

        if ($user = auth()->user()) {
            if (!PermissionService::userHasWorkspacePerm($user, $workspace, [RolesEnum::ADMIN])) {
                return redirect()->back()->with('error', 'You do not have permission to see the users of this workspace.');
            }
        }
        $query = $workspace->users()
            ->select('users.id', 'users.name', 'users.email')
            ->where('users_to_workspace.workspace_id', $workspaceId);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
            });
        }

        $this->total = $query->count();
        $this->query = $query->offset($start)->limit($length);
        return $this->query;
    }

    public function __get($key)
    {
        return match ($key) {
            'total' => $this->total,
            'query' => $this->query,
            default => parent::__get($key),
        };
    }
}
