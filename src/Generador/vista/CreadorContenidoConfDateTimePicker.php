<?php


namespace TesGen\Generador\Generador\vista;


use TesGen\Generador\Modelo\Tabla;

class CreadorContenidoConfDateTimePicker {

    /**
     * @param Tabla $tabla
     * @return string
     */
    public static function getContenido(Tabla $tabla) {

        $contenido = '';

        $hayTipoDate = false;
        $hayTipoTime = false;

        //verificar si hay columnas de tipo date o time
        foreach ($tabla->getColumnas() as $columna) {

            if ($columna->getTipo() === 'date') {
                $hayTipoDate = true;
            }

            if ($columna->getTipo() === 'time') {
                $hayTipoTime = true;
            }

        }

        if (!$hayTipoDate && !$hayTipoTime) {
            return $contenido;
        }

        if ($hayTipoDate) {

            $contenido .= "            var confDatePicker = {
                uiLibrary: 'bootstrap4',
                locale: 'es-es',
                format: 'dd/mm/yyyy',
                autoclose: true,
                showTodayButton: true
            };\n";
        }

        if ($hayTipoTime) {
            $contenido .= "            var confTimePicker = {uiLibrary: 'bootstrap4', format: 'HH:MM', locale: 'es-es'};\n";
        }

        foreach ($tabla->getColumnas() as $columna) {
            if ($columna->getTipo() === 'date') {
                $nombreColumna = $columna->getNombreColumna();
                $contenido .= "            $('#${nombreColumna}').datepicker(confDatePicker);\n";
            }
        }
        foreach ($tabla->getColumnas() as $columna) {
            if ($columna->getTipo() === 'time') {
                $nombreColumna = $columna->getNombreColumna();
                $contenido .= "            $('#${nombreColumna}').timepicker(confTimePicker);\n";
            }
        }
        $contenido .= "\n";

        return $contenido;

    }

}
