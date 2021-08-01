<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCompletedRegistration
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
        if (auth()->check() && !$request->user()->hasCompletedRegistration()) {
            if ($guards[0] == 'api') {
                return response(message('complete_registration'), 401);
            }
            return redirect(route('register.complete'));
        }

        return $next($request);
    }
}
