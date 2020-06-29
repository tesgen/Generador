<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\Mapeador;

class CreadorContenidoMdCreate {

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @return string el contenido del arhivo create
     */
    public function getContenido(Tabla $tabla, $tablas, $directorioPlantillaMdCreate): string {

        $contenido = file_get_contents($directorioPlantillaMdCreate);

        $generadorCamposMaestro = new CreadorContenidoCamposCrear();
        $camposMaestro = $generadorCamposMaestro->getContenido($tabla, $tablas);

        $idTablaAuxiliar = Mapeador::getTablaAuxiliar($tabla, $tablas)->getClavePrimaria();

        $tablaDetalle = Mapeador::getTablaDetalle($tabla, $tablas);

        $generadorCamposDetalle = new CreadorContenidoCamposCrear();
        $camposAuxiliar = $generadorCamposDetalle->getContenido($tablaDetalle, $tablas);

        $contenidoLimpiarCamposDetalle = $generadorCamposDetalle->getContenidoLimpiarCamposDetalle();
        $definicionVariablesJsDetalle = $generadorCamposDetalle->getDefinicionVariablesJsDetalle();
        $asignacionVariablesJsDetalle = $generadorCamposDetalle->getAsignacionVariablesJsDetalle();
        $cabeceraTablaDetalle = $generadorCamposDetalle->getCabeceraTablaDetalle();
        $filasTablaDetalle = $generadorCamposDetalle->getFilasTablaDetalle();

        $asignacionVariablesJsMaestro = CreadorContenidoAjax::getDatosParaAjax($tabla, true);
        $asignacionVariablesJsMaestro .= str_repeat("\t", 5) . "detalles: detalles";

        $contenido = str_replace('$CAMPOS_MAESTRO$', $camposMaestro, $contenido);
        $contenido = str_replace('$CAMPOS_AUXILIAR$', $camposAuxiliar, $contenido);
        $contenido = str_replace('$CABECERA_TABLA_DETALLE$', $cabeceraTablaDetalle, $contenido);
        $contenido = str_replace('$DEFINICION_VARIABLES_JS_DETALLE$', $definicionVariablesJsDetalle, $contenido);
        $contenido = str_replace('$ASIGNACION_VARIABLES_JS_DETALLE$', $asignacionVariablesJsDetalle, $contenido);
        $contenido = str_replace('$FILAS_TABLA_DETALLE$', $filasTablaDetalle, $contenido);
        $contenido = str_replace('$CONTENIDO_LIMPIAR_CAMPOS_DETALLE$', $contenidoLimpiarCamposDetalle, $contenido);
        $contenido = str_replace('$ASIGNACION_VARIABLES_JS_MAESTRO$', $asignacionVariablesJsMaestro, $contenido);

//        $confDateTimePicker = "\n";
        $confDateTimePicker = CreadorContenidoConfDateTimePicker::getContenido($tabla);
        $contenido = str_replace('$CONF_DATE_TIME_PICKER$', $confDateTimePicker, $contenido);

        $funcionesFormula = CreadorContenidoFuncionesFormula::getFunciones($tabla, true);
        $contenido = str_replace('$FUNCIONES_PARA_FORMULA$', $funcionesFormula, $contenido);

        $contenidoOnReady = CreadorContenidoFuncionesFormula::getContenidoOnReady($tabla, true);
        $contenido = str_replace('$ON_READY$', $contenidoOnReady, $contenido);

        $nombreClaseDetalle = Mapeador::getTablaDetalle($tabla, $tablas)->getNombreClase();
        $contenido = str_replace('$NOMBRE_CLASE_DETALLE$', $nombreClaseDetalle, $contenido);

        $funcionesFormulaDetalle = CreadorContenidoFuncionesFormula::getFunciones($tablaDetalle, true);
        $contenido = str_replace('$FUNCIONES_PARA_FORMULA_DETALLE$', $funcionesFormulaDetalle, $contenido);

        $contenidoOnReadyDetalle = CreadorContenidoFuncionesFormula::getContenidoOnReady($tablaDetalle, true);;
        $contenido = str_replace('$ON_READY_DETALLE$', $contenidoOnReadyDetalle, $contenido);

        $contenido = str_replace('$ID_TABLA_AUXILIAR$', $idTablaAuxiliar, $contenido);

        return $contenido;
    }

}
