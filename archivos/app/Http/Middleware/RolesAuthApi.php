<?php

namespace App\Http\Middleware;

use Closure;

class RolesAuthApi
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
        $actionName = explode('@', $request->route()->getActionName())[0];

        if (RolesAuth::puedeAccederA($actionName)) {
            return $next($request);
        }
        return response()->json(['message' => 'No tienes autorización para realizar esta acción'], 401);
    }
}
