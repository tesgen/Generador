<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;

class CreadorContenidoCreate {

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @param $directorioPlantillaCreate
     * @return string el contenido del arhivo create
     */
    public function getContenido(Tabla $tabla, $tablas, $directorioPlantillaCreate): string {

        $contenido = file_get_contents($directorioPlantillaCreate);

//        $generadorCampos = new CreadorContenidoCampos();
        $generadorCampos = new CreadorContenidoCamposCrear();

        $contenidoCampos = $generadorCampos->getContenido($tabla, $tablas);

        $contenido = str_replace('$CAMPOS_INPUT_HTML$', $contenidoCampos, $contenido);
        $contenido = str_replace('$CAMPOS_PARA_AJAX$', CreadorContenidoAjax::getDatosParaAjax($tabla, true), $contenido);

        $funcionesFormula = CreadorContenidoFuncionesFormula::getFunciones($tabla, true);
        $contenido = str_replace('$FUNCIONES_PARA_FORMULA$', $funcionesFormula, $contenido);

        $confDateTimePicker = CreadorContenidoConfDateTimePicker::getContenido($tabla);
        $contenido = str_replace('$CONF_DATE_TIME_PICKER$', $confDateTimePicker, $contenido);

        $contenidoOnReady = CreadorContenidoFuncionesFormula::getContenidoOnReady($tabla, true);
        $contenido = str_replace('$ON_READY$', $contenidoOnReady, $contenido);

        return $contenido;
    }

}
