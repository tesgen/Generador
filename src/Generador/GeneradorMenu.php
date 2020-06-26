<?php

namespace TesGen\Generador\Generador;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\ArchivoUtil;

class GeneradorMenu {

    /**
     * @param Tabla[] $tablas
     * @param array $autenticacion
     */
    public function generar($tablas, $autenticacion): void {

        $directorioAGenerar = resource_path() . '\\views\\layouts';
        $archivoAGenerar = 'menu.blade.php';
        $contenido = '';

        $autenticacionGenerada = $autenticacion['generado'];

        foreach ($tablas as $table) {

            if ($autenticacionGenerada) {
                $indentacion = "    ";
            } else {
                $indentacion = "";
            }

            if ($table->isGenerado()) {

                if ($table->isDetalle()) {
                    continue;
                }

                echo $table->getNombreTabla() . "\n";

                $nombreTabla = $table->getNombreTabla();
                $nombrePlural = $table->getNombrePlural();

                if ($autenticacionGenerada) {
                    $contenido .= "@if(RolesAuth::puedeAccederA('App\Http\Controllers\\" . $table->getNombreClase() . "Controller'))\n";
                }

                $contenido .= $indentacion . "<li class=\"nav-item {{ Request::is('$nombreTabla*') ? 'active' : '' }}\">\n";
                $contenido .= $indentacion . "<a class=\"nav-link {{ Request::is('$nombreTabla*') ? 'active' : '' }}\" href=\"{!! route('$nombreTabla.index') !!}\">\n";
                $contenido .= $indentacion . "        <i class=\"nav-icon icon-cursor\"></i><span>$nombrePlural</span>\n";
                $contenido .= $indentacion . "    </a>\n";
                $contenido .= $indentacion . "</li>\n";
                if ($autenticacionGenerada) {
                    $contenido .= "@endif\n";
                }

            }

        }

        if ($autenticacionGenerada) {
            $contenido .= "@if(RolesAuth::puedeAccederA('App\Http\Controllers\Auth\UserController') || RolesAuth::puedeAccederA('App\Http\Controllers\Auth\RoleController'))\n";
//            $contenido .= "<li class=\"nav-item {{ Request::is('register*') ? 'active' : '' }}\">\n";
//            $contenido .= "<a class=\"nav-link {{ Request::is('register*') ? 'active' : '' }}\" href=\"{!! route('register') !!}\">\n";
//            $contenido .= "        <i class=\"nav-icon icon-user-follow\"></i><span>Registrar Usuario</span>\n";
//            $contenido .= "    </a>\n";
//            $contenido .= "</li>\n";
            $contenido .= "<li class=\"nav-item nav-dropdown\">
    <a class=\"nav-link nav-dropdown-toggle\" href=\"#\">
        <i class=\"nav-icon icon-user\"></i>Autenticación</a>
    <ul class=\"nav-dropdown-items\">
        @if(RolesAuth::puedeAccederA('App\Http\Controllers\Auth\UserController'))
            <li class=\"nav-item {{ Request::is('users*') ? 'active' : '' }}\">
                <a class=\"nav-link {{ Request::is('users*') ? 'active' : '' }}\" href=\"{!! route('users.index') !!}\">
                    &nbsp;&nbsp;&nbsp;<i class=\"nav-icon icon-cursor\"></i><span>Usuarios</span>
                </a>
            </li>
        @endif
        @if(RolesAuth::puedeAccederA('App\Http\Controllers\Auth\RoleController'))
            <li class=\"nav-item {{ Request::is('roles*') ? 'active' : '' }}\">
                <a class=\"nav-link {{ Request::is('roles*') ? 'active' : '' }}\" href=\"{!! route('roles.index') !!}\">
                    &nbsp;&nbsp;&nbsp;<i class=\"nav-icon icon-cursor\"></i><span>Roles</span>
                </a>
            </li>
        @endif
    </ul>
</li>";
            $contenido .= "\n@endif\n";
        }

        ArchivoUtil::createFile($directorioAGenerar, $archivoAGenerar, $contenido);

    }

}
