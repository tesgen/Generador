<?php

use Illuminate\Database\Schema\Blueprint;
use TesGen\Generador\Controllers\BaseController;
use TesGen\Generador\Generador\GeneradorAutenticacion;
use TesGen\Generador\Generador\GeneradorControlador;
use TesGen\Generador\Generador\GeneradorCreateRequest;
use TesGen\Generador\Generador\GeneradorModelo;
use TesGen\Generador\Generador\vista\CreadorContenidoTablaHtml;
use TesGen\Generador\Utils\ArchivoUtil;
use TesGen\Generador\Utils\Mapeador;
use Tests\TestCase;

class MapperTest extends TestCase {

    public function testGetTablas() {

        foreach (Route::getRoutes()->getRoutes() as $key => $route) {
            $action = $route->getActionname();
            echo $action . "\n";
        }

//        $generador = new BaseController();
//        $generador->generarConNombreTabla('cliente', true);
//        $generador->generarMenuYRutas();

//        $call = Artisan::call("make:migration create_table_prueba");
//        $call = Artisan::call("migrate --path=/app/database/migrations/usuarios");
//        Schema::create('tableName', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('name');
//        });

//        $nombreBd = DB::getDatabaseName();
//        $directorioParcialMap = 'resources/map/';
//        $directorioCarpetaMap = base_path($directorioParcialMap);
//        $directorioArchivoMap = base_path($directorioParcialMap . $nombreBd . '.json');
//        $mapeador = new Mapeador();
//        $mapeador->crearArchivoMapSiNoExiste($directorioCarpetaMap, $nombreBd);

//        $auth = new GeneradorAutenticacion();
//        $auth->generar('carlos', 'carlos');

        $this->assertEquals(true, true);

    }

}
