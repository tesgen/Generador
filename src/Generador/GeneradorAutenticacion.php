<?php


namespace TesGen\Generador\Generador;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TesGen\Generador\Controllers\BaseController;
use TesGen\Generador\Modelo\Auntenticacion;
use TesGen\Generador\Utils\ArchivoUtil;
use TesGen\Generador\Utils\Constante;
use TesGen\Generador\Utils\Mapeador;

class GeneradorAutenticacion {

    private $directorioPlantilla;

    /**
     * @var Auntenticacion
     */
    private $auntenticacion;

    /**
     * GeneradorAutenticacion constructor.
     */
    public function __construct() {
        $this->directorioPlantilla = Constante::DIRECTORIO_PLANTILLAS;
    }

    /**
     * @param Auntenticacion $auntenticacion
     */
    public function generar($auntenticacion): void {

        $this->auntenticacion = $auntenticacion;

        $this->crearControladores();
        $this->crearRequests();
        $this->crearModelos();
        $this->crearVistasAuth();
        $this->crearVistasUsers();
        $this->crearVistasRoles();
        $this->crearMiddleware();
        $this->crearTablas();

//        $path = base_path('.env');
//        if (file_exists($path)) {
//            file_put_contents($path, str_replace(
//                'SESSION_LIFETIME' . '=' . env('SESSION_LIFETIME'), 'SESSION_LIFETIME' . '=' . 2, file_get_contents($path)
//            ));
//        }
    }

    /**
     * Crear clases controladoras para registros, logueo, roles y gestion de usuarios
     */
    private function crearControladores() {

        $directorioControlador = app_path() . '\\Http\\Controllers\\Auth';

        $archivosPlantillasAuth = ['RegisterController.txt', 'LoginController.txt', 'RoleController.txt', 'UserController.txt'];
        $archivosPhpAuth = ['RegisterController.php', 'LoginController.php', 'RoleController.php', 'UserController.php'];

        for ($i = 0; $i < count($archivosPlantillasAuth); $i++) {
            $archivoControlador = $archivosPhpAuth[$i];

            $directorioPlantillaControlador = base_path($this->directorioPlantilla . 'autenticacion\\controladores\\' . $archivosPlantillasAuth[$i]);
            $contenidoControlador = file_get_contents($directorioPlantillaControlador);
            if ($archivoControlador === 'RegisterController.php') {
                $contenidoControlador = str_replace('$LONGITUD_MINIMA_USUARIO$', $this->auntenticacion->getLongitudMinimaUsuario(), $contenidoControlador);
                $contenidoControlador = str_replace('$LONGITUD_MAXIMA_USUARIO$', $this->auntenticacion->getLongitudMaximaUsuario(), $contenidoControlador);
                $contenidoControlador = str_replace('$LONGITUD_MINIMA_CONTRASENA$', $this->auntenticacion->getLongitudMinimaContrasena(), $contenidoControlador);
            }
            ArchivoUtil::createFile($directorioControlador, $archivoControlador, $contenidoControlador);
        }

        $directorio = app_path() . '\\Http\\Controllers\API';

        $directorio_pantilla_base = base_path('vendor\\tesgen\\generador\\plantillas\\api\\UserController.txt');
        $contenidoPlantillaBase = file_get_contents($directorio_pantilla_base);

        ArchivoUtil::createFile($directorio, "UserController.php", $contenidoPlantillaBase);
    }

    /**
     * Crear Form Requests para cambiar contraseña, roles y gestion de usuarios
     */
    private function crearRequests() {

        $directorioRequests = app_path() . '\\Http\\Requests';

        $archivosPlantillasRequest = ['ChangePasswordRequest.txt', 'RoleCreateRequest.txt',
            'RoleUpdateRequest.txt', 'UserCreateRequest.txt', 'UserUpdateRequest.txt'];

        $archivosPhpRequest = ['ChangePasswordRequest.php', 'RoleCreateRequest.php',
            'RoleUpdateRequest.php', 'UserCreateRequest.php', 'UserUpdateRequest.php'];

        for ($i = 0; $i < count($archivosPlantillasRequest); $i++) {
            $archivoRequest = $archivosPhpRequest[$i];
            $directorioPlantillaRequests = base_path($this->directorioPlantilla . 'autenticacion\\requests\\' . $archivosPlantillasRequest[$i]);
            $contenidoRequest = file_get_contents($directorioPlantillaRequests);
            $contenidoRequest = str_replace('$LONGITUD_MINIMA_USUARIO$', $this->auntenticacion->getLongitudMinimaUsuario(), $contenidoRequest);
            $contenidoRequest = str_replace('$LONGITUD_MAXIMA_USUARIO$', $this->auntenticacion->getLongitudMaximaUsuario(), $contenidoRequest);
            $contenidoRequest = str_replace('$LONGITUD_MINIMA_CONTRASENA$', $this->auntenticacion->getLongitudMinimaContrasena(), $contenidoRequest);
            ArchivoUtil::createFile($directorioRequests, $archivoRequest, $contenidoRequest);
        }
    }

    /**
     * Crear clases modelos para permisos, roles y usuarios
     */
    private function crearModelos() {

        $directorioModelos = app_path();

        $archivosPlantillas = ['Permission.txt', 'Role.txt', 'User.txt'];
        $archivosPhp = ['Permission.php', 'Role.php', 'User.php'];

        for ($i = 0; $i < count($archivosPlantillas); $i++) {
            $archivoUser = $archivosPhp[$i];
            $directorioPlantillaUser = base_path($this->directorioPlantilla . 'autenticacion\\modelos\\' . $archivosPlantillas[$i]);
            $contenidoModelo = file_get_contents($directorioPlantillaUser);
            ArchivoUtil::createFile($directorioModelos, $archivoUser, $contenidoModelo);
        }
    }

    /**
     * Crear vistas para cambiar contraseña, login y registro por primera vez
     */
    private function crearVistasAuth() {

        $directorioVista = resource_path() . '\\views\\auth';

        $archivosPlantillasVista = ['change_password.txt', 'login.txt', 'register_first_time.txt'];
        $archivosPhpVista = ['change_password.blade.php', 'login.blade.php', 'register_first_time.blade.php'];

        for ($i = 0; $i < count($archivosPlantillasVista); $i++) {
            $archivoVista = $archivosPhpVista[$i];
            $directorioPlantillaVista = base_path($this->directorioPlantilla . 'autenticacion\\auth\\' . $archivosPlantillasVista[$i]);
            $contenidoVista = file_get_contents($directorioPlantillaVista);
            ArchivoUtil::createFile($directorioVista, $archivoVista, $contenidoVista);
        }
    }

    /**
     * Crear vistas para crear, editar, listar y ver datos de usuarios
     */
    private function crearVistasUsers() {
        $directorioVista = resource_path() . '\\views\\users';

        $archivosPlantillasVista = ['create.txt', 'edit.txt', 'index.txt', 'show.txt'];
        $archivosPhpVista = ['create.blade.php', 'edit.blade.php', 'index.blade.php', 'show.blade.php'];

        for ($i = 0; $i < count($archivosPlantillasVista); $i++) {
            $archivoVista = $archivosPhpVista[$i];
            $directorioPlantillaVista = base_path($this->directorioPlantilla . 'autenticacion\\users\\' . $archivosPlantillasVista[$i]);
            $contenidoVista = file_get_contents($directorioPlantillaVista);
            ArchivoUtil::createFile($directorioVista, $archivoVista, $contenidoVista);
        }
    }

    /**
     * * Crear vistas para crear, editar, listar, eliminar y ver datos de roles
     */
    private function crearVistasRoles() {
        $directorioVista = resource_path() . '\\views\\roles';

        $archivosPlantillasVista = ['create.txt', 'edit.txt', 'index.txt', 'show.txt'];
        $archivosPhpVista = ['create.blade.php', 'edit.blade.php', 'index.blade.php', 'show.blade.php'];

        for ($i = 0; $i < count($archivosPlantillasVista); $i++) {
            $archivoVista = $archivosPhpVista[$i];
            $directorioPlantillaVista = base_path($this->directorioPlantilla . 'autenticacion\\roles\\' . $archivosPlantillasVista[$i]);
            $contenidoVista = file_get_contents($directorioPlantillaVista);
            ArchivoUtil::createFile($directorioVista, $archivoVista, $contenidoVista);
        }
    }

    private function crearMiddleware() {
        $directorioMiddleware = app_path() . '\\Http\\Middleware';
        $directorioPlantillaMiddleware = base_path($this->directorioPlantilla . 'autenticacion\\RolesAuth.txt');
        $contenidoMiddleware = file_get_contents($directorioPlantillaMiddleware);
        ArchivoUtil::createFile($directorioMiddleware, "RolesAuth.php", $contenidoMiddleware);
    }

    /**
     * Crear las tablas de usuarios en la base de datos si no existen
     */
    private function crearTablas() {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
            });
        }

        if (!Schema::hasTable('users')) {

            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username')->unique();
                $table->string('password');
                $table->unsignedInteger('role_id')->index();
                $table->foreign('role_id')->references('id')->on('roles');
            });

        }
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('table');
                $table->string('description');
                $table->string('controller');
            });
        }
        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->unsignedInteger('permission_id');
                $table->unsignedInteger('role_id');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');
                $table->foreign('role_id')
                    ->references('id')
                    ->on('roles')
                    ->onDelete('cascade');
                $table->primary(['permission_id', 'role_id']);
            });
        }
    }

    public function eliminar() {

        //Eliminar algunos archivos dentro de directorios
        $directorioMiddleware = app_path() . '\\Http\\Middleware';
        $directorioRequests = app_path() . '\\Http\\Requests';
        $directorioApi = app_path() . '\\Http\\Controllers\\API';
        $directorioModelos = app_path();

        $archivosEliminar = [
            $directorioMiddleware => ['RolesAuth.php'],
            $directorioRequests => ['ChangePasswordRequest.php', 'RoleCreateRequest.php','RoleUpdateRequest.php',
                'UserCreateRequest.php', 'UserUpdateRequest.php'],
            $directorioApi => ['UserController.php'],
            $directorioModelos => ['Permission.php', 'Role.php', 'User.php']
        ];

        foreach ($archivosEliminar as $k => $v) {
            foreach ($v as $item) {
                ArchivoUtil::deleteFile($k, $item);
            }
        }

        //Eliminar directorios completos
        $directorioControlador = app_path() . '\\Http\\Controllers\\Auth';
        $directorioVistaAuth = resource_path() . '\\views\\auth';
        $directorioVistaRoles = resource_path() . '\\views\\roles';
        $directorioVistaUsers = resource_path() . '\\views\\users';

        $directoriosEliminar = [$directorioControlador, $directorioVistaAuth, $directorioVistaRoles, $directorioVistaUsers];

        foreach ($directoriosEliminar as $directorio) {
            ArchivoUtil::deleteFolderWithFiles($directorio);
        }

        if (Schema::hasTable('permission_role')) {
            Schema::drop('permission_role');
        }

        if (Schema::hasTable('users')) {
            Schema::drop('users');
        }

        if (Schema::hasTable('roles')) {
            Schema::drop('roles');
        }

        if (Schema::hasTable('permissions')) {
            Schema::drop('permissions');
        }

        $baseController = new BaseController();

        $arrayAutenticacion = $baseController->obtenerJsonMapAutenticacion();
        $arrayAutenticacion['generado'] = false;

        $mapeador = new Mapeador();
        $mapeador->guardarConfiguracionAutenticacion($arrayAutenticacion);

    }

}
