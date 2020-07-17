<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\Mapeador;

class CreadorContenidoAjax {

    public static function getDatosParaAjax(Tabla $tabla, bool $esParaGuardar): string {

        $contenido = '';

        foreach ($tabla->getColumnas() as $columna) {

            $nombreColumna = $columna->getNombreColumna();

            if ($nombreColumna === $tabla->getClavePrimaria() || $columna->isAutoincrementalGuardar()) {
                continue;
            }

            if ($esParaGuardar || (!$esParaGuardar && $tabla->isFormActualizarIgualQueGuardar())) {
                if ($columna->isCampoMostrable()) {
                    $contenido .= "                    $nombreColumna: $(\"#$nombreColumna\").val()";
                    if ($columna->getTipo() === 'date') {
                        $contenido .= ".split(\"/\").reverse().join(\"-\")";
                    }
                    $contenido .= ",\n";
                } else {

                    $valorPorDefecto = '';

                    if($columna->isAutomaticoGuardar()) {
                        $valorPorDefecto = "'" . $columna->getValorAutomaticoGuardar() . "'";
                    } else if($columna->isFormulaGuardar()) {
                        $valorPorDefecto = "calcular_" . $columna->getNombreColumna() . "()";
                    } else {
                        $valorPorDefecto = 'null';
                    }
                    $contenido .= "                    $nombreColumna: ${valorPorDefecto},\n";
                }
            } else {
                if (/*$columna->isVisibleEnFormularioActualizar() && */$columna->isCampoActualizable()) {
                    if ($columna->isVisibleEnFormularioActualizar()) {
                        $contenido .= "                    $nombreColumna: $(\"#$nombreColumna\").val()";
                        if ($columna->getTipo() === 'date') {
                            $contenido .= ".split(\"/\").reverse().join(\"-\")";
                        }
                        $contenido .= ",\n";
                    } else {
                        $valorPorDefecto = '';

                        if($columna->isAutomaticoActualizar()) {
                            $valorPorDefecto = "'" . $columna->getValorAutomaticoActualizar() . "'";
                        } else if($columna->isFormulaActualizar()) {
                            $valorPorDefecto = "calcular_" . $columna->getNombreColumna() . "()";
                        } else {
                            $valorPorDefecto = 'null';
                        }
                        $contenido .= "                    $nombreColumna: ${valorPorDefecto},\n";
                    }

                }
            }

        }

        return $contenido;
    }

}
