<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;

class CreadorContenidoEdit {

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @param $directorioEdit
     * @return string el contenido del arhivo edit
     */
    public function getContenido($tabla, $tablas, $directorioEdit): string {

        $contenido = file_get_contents($directorioEdit);

//        $generadorCampos = new CreadorContenidoCampos();
        $generadorCampos = new CreadorContenidoCamposEditar();

        $contenidoCampos = $generadorCampos->getContenido($tabla, $tablas, $tabla->isFormActualizarIgualQueGuardar());

        $contenido = str_replace('$CAMPOS_INPUT_HTML$', $contenidoCampos, $contenido);

        $contenidotablaHtmlDetalle = new CreadorContenidoTablaDetalleHtml();
        $tablaHtmlDetalle = $contenidotablaHtmlDetalle->getContenido($tabla, $tablas);

        $contenido = str_replace('$TABLA_DETALLE$', $tablaHtmlDetalle, $contenido);

        $contenido = str_replace('$CAMPOS_PARA_AJAX$', CreadorContenidoAjax::getDatosParaAjax($tabla, false), $contenido);

        $funcionesFormula = CreadorContenidoFuncionesFormula::getFunciones($tabla, $tabla->isFormActualizarIgualQueGuardar());
        $contenido = str_replace('$FUNCIONES_PARA_FORMULA$', $funcionesFormula, $contenido);

        $confDateTimePicker = CreadorContenidoConfDateTimePicker::getContenido($tabla);
//        $confDateTimePicker = "\n";
        $contenido = str_replace('$CONF_DATE_TIME_PICKER$', $confDateTimePicker, $contenido);

        $contenidoOnReady = CreadorContenidoFuncionesFormula::getContenidoOnReady($tabla, true);
        $contenido = str_replace('$ON_READY$', $contenidoOnReady, $contenido);

        return $contenido;
    }

}
