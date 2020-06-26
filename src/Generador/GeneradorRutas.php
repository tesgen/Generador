<?php

namespace TesGen\Generador\Generador;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\ArchivoUtil;

class GeneradorRutas {

    /**
     * @param Tabla[] $tablas
     * @param array $autenticacion
     */
    public function generar($tablas, $autenticacion): void {

        $directorio = base_path() . '\\routes';
        $archivo = 'web.php';
        $archivoApi = 'api.php';

        $autenticacionGenerada = $autenticacion['generado'];

        $contenido = "<?php\n\n";
        $contenidoApi = "<?php\n\n";

        if ($autenticacionGenerada) {

            $contenido .= "Route::group(array('middleware' => 'roleAuth'), function () {\n";

            $contenidoApi .= "Route::post('login', 'API\UserController@login');\n\n";
            $contenidoApi .= "Route::middleware('auth:api')->group(function () {\n\n";
            $contenidoApi .= "    Route::get('logout', 'API\UserController@logout');\n\n";
            $contenidoApi .= "    Route::middleware('roleAuthApi')->group(function () {\n";

            $indentacion = "    ";
            $indentacionApi = "        ";
        } else {
            $indentacion = "";
            $indentacionApi = "";
        }

        foreach ($tablas as $tabla) {

            if ($tabla->isDetalle()) {
                continue;
            }

            if ($tabla->isGenerado()) {

                $nombreClase = $tabla->getNombreClase();
                $contenido .= $indentacion . "Route::resource('" . $tabla->getNombreTabla() . "', '" . $nombreClase . "Controller');\n";
                if ($tabla->isGenerarApi()) {
                    $contenidoApi .= $indentacionApi . "Route::resource('" . $tabla->getNombreTabla() . "', 'API\\" . $nombreClase . "Controller');\n";
                }

            }

        }


        if ($autenticacionGenerada) {

            $contenidoApi .= "    });\n";
            $contenidoApi .= "});\n";

            $contenido .= $indentacion . "Route::resource('users', 'Auth\UserController');\n";
            $contenido .= $indentacion . "Route::resource('roles', 'Auth\RoleController');\n";

            $contenido .= $indentacion . "Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');\n";

            $contenido .= "});

Route::post('register', 'Auth\RegisterController@register');\n";

            $contenido .= "
Route::group(array('middleware' => 'webAuth'), function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
});\n\n";

        }

        if ($autenticacionGenerada) {
            $contenido .= "Route::group(array('middleware' => 'auth'), function () {\n";
            $contenido .= $indentacion . "Route::get('/error403', 'HomeController@error403')->name('error403');\n";
        }

        $contenido .= $indentacion . "Route::get('/', 'HomeController@index')->name('home');\n";

        if ($autenticacionGenerada) {
            $contenido .= $indentacion . "Route::post('logout', 'Auth\LoginController@logout')->name('logout');\n";

            $contenido .= $indentacion . "Route::get('/user/edit_password', 'Auth\UserController@editPassword')->name('editPassword');\n";
            $contenido .= $indentacion . "Route::post('/user/change_password', 'Auth\UserController@changePassword')->name('changePassword');\n";
            $contenido .= "});\n";
        }

        ArchivoUtil::createFile($directorio, $archivo, $contenido);
        ArchivoUtil::createFile($directorio, $archivoApi, $contenidoApi);

    }

}
