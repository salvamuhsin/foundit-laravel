<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Usage in routes: ->middleware('role:user') or ->middleware('role:admin')
class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== $role) {
            // Admins never see member pages (report, claim, dashboard); members never see admin pages.
            return $user && $user->isAdmin() ? redirect()->route('admin.index') : abort(403);
        }

        return $next($request);
    }
}
