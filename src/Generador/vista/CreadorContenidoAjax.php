<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\Mapeador;

class CreadorContenidoAjax {

    public static function getDatosParaAjax(Tabla $tabla, bool $esParaCrear): string {

        $contenido = '';

        foreach ($tabla->getColumnas() as $columna) {

            $nombreColumna = $columna->getNombreColumna();

            if ($nombreColumna === $tabla->getClavePrimaria()) {
                continue;
            }

            if ($esParaCrear) {
                if ($columna->isCampoGuardable()) {
                    $contenido .= "                    $nombreColumna: $(\"#$nombreColumna\").val()";
//                    if ($columna->getTipo() === 'date') {
//                        $contenido .= ".split(\"/\").reverse().join(\"-\")";
//                    }
                    $contenido .= ",\n";
                } else {
                    $contenido .= "                    $nombreColumna: null,\n";
                }
            } else {
                if ($columna->isVisibleEnFormularioActualizar() && $columna->isCampoActualizable()) {
                    $contenido .= "                    $nombreColumna: $(\"#$nombreColumna\").val()";
//                    if ($columna->getTipo() === 'date') {
//                        $contenido .= ".split(\"/\").reverse().join(\"-\")";
//                    }
                    $contenido .= ",\n";
                }
            }

        }

        return $contenido;
    }

}
