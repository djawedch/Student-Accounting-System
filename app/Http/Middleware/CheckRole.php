<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->guard()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->guard()->user();

        if (!$user->is_active) {
            auth()->guard()->logout();
            return redirect()->route('login')
                ->withErrors('Your account is disabled.');
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
