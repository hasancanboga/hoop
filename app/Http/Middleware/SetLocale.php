<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (request()->has('locale')) {
            app()->setLocale(request('locale'));
        } else if (request()->cookie('locale')) {
            app()->setLocale($request->cookie('locale'));
        }

        // else it defaults to the locale or falback_locale in config/app.php

        return $next($request);
    }
}
