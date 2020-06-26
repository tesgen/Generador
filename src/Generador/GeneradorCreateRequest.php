<?php


namespace TesGen\Generador\Generador;


use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\ArchivoUtil;
use TesGen\Generador\Utils\Constante;
use TesGen\Generador\Utils\Mapeador;

class GeneradorCreateRequest {

    public function generar(Tabla $tabla) {

        $directorio_pantilla = base_path(Constante::DIRECTORIO_PLANTILLAS . '\\request.txt');

        $contenidoPlantilla = file_get_contents($directorio_pantilla);

        $nombreClase = $tabla->getNombreClase();

        $creadorRulesCampo = new CreadorRulesCampo($tabla, true);

        $contenidoReturn = $creadorRulesCampo->getContenidoReturn();
        $contenidoMessages = $creadorRulesCampo->getContenidoMessages();

        $contenidoPlantilla = str_replace('$NOMBRE_MODELO$', $nombreClase, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$TIPO_REQUEST$', 'Create', $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CONTENIDO_ID$', '', $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CONTENIDO_RETURN$', $contenidoReturn, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CONTENIDO_MESSAGES$', $contenidoMessages, $contenidoPlantilla);

        $contenido = $contenidoPlantilla;

        $archivo = $nombreClase . 'CreateRequest.php';
        $directorio = app_path() . '\\Http\\Requests';
        ArchivoUtil::createFile($directorio, $archivo, $contenido);

    }

    public function eliminar() {

        $directorioControladores = app_path() . '\\Http\\Requests';

        $mapeador = new Mapeador();
        $arrayTablas = $mapeador->obtenerJsonMap()['tablas'];

        foreach ($arrayTablas as $t) {

            if ($t['generado'] === true) {

                $nombreClase = $t['nombreClase'] . 'CreateRequest.php';
                ArchivoUtil::deleteFile($directorioControladores, $nombreClase);
            }
        }
    }

}
