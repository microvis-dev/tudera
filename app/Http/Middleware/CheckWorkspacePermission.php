<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkspaceRole
{
    protected $levels = [
        'viewer' => 0,
        'editor' => 1,
        'owner'  => 2,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string   $requiredRole  
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $requiredRole)
    {
        $user = $request->user();
        $workspace = $request->route('workspace');

        $utw = $user->userToWorkspaces()
                    ->where('workspace_id', $workspace->id)
                    ->first();

        if (!$utw) {
            abort(403, '!');
        }

        $userRole = $utw->role->name; 

        if ($this->levels[$userRole] < $this->levels[$requiredRole]) {
            abort(403, '!');
        }

        return $next($request);
    }
}
