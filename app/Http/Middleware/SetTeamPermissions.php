<?php

namespace App\Http\Middleware;

use App\Services\WorkspaceService;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetTeamPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $team = session(WorkspaceService::WORKSPACE_SESSION);
            app(PermissionRegistrar::class)->setPermissionsTeamId($team);
        }
        return $next($request);
    }
}
