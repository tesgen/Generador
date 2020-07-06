<?php

namespace App\Http\Middleware;

use App\Role;
use App\User;
use Closure;

class RolesAuth {

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $actionName = explode('@', $request->route()->getActionName())[0];

        if (self::puedeAccederA($actionName)) {
            return $next($request);
        }

        return redirect()->route('error403');
    }

    /**
     * @return bool
     */
    public static function puedeAccederA($actionName) {

        $primerUsuarioId = User::all()->first()->id;

        if ($primerUsuarioId === auth()->user()->id) {
            return true;
        }

        $role = Role::findOrFail(auth()->user()->role_id);
        $permissions = $role->permissions;

        foreach ($permissions as $permission) {

            $actionNameArray = explode('\\', $actionName);
            $actionName = end($actionNameArray);

            $controller = $permission->controller;
            $controllerArray = explode('\\', $controller);
            $controller = end($controllerArray);

            if ($actionName == $controller) {
                return true;
            }
        }

        return false;
    }

}
