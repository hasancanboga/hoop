<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureParentRegistered
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        if (auth()->check() && $request->user()->age < 13 && !$request->user()->hasRegisteredParent()) {
            if ($guards[0] == 'api') {
                return response(message('parent_registration'), 401);
            }
            return redirect(route('register.parent'));
        }

        return $next($request);
    }
}
