<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class WebAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (count(User::all()) === 0) {
            return \Response::view('auth.register_first_time');
        }
        return $next($request);
    }
}
