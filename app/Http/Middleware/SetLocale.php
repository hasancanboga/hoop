<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (request()->has('locale')) {
            app()->setLocale(request('locale'));
        } else if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            // defaults to the locale or falback_locale in config/app.php
        }

        return $next($request);
    }
}
