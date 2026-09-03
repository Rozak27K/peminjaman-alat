<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $currentRole = $request->user()?->role;

        if (! in_array($currentRole, $roles, true)) {
            abort(403, 'Role Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
