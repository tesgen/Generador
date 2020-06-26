<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;

class CreadorContenidoIndex {

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @return string el contenido del arhivo index
     */
    public function getContenido(Tabla $tabla, $tablas, $directorioPlantillaIndex): string {

        $nombreTabla = $tabla->getNombreTabla();
        $contenido = file_get_contents($directorioPlantillaIndex);
        $columnasVisiblesReporte = [];

        $i = 0;

        foreach ($tabla->getColumnas() as $columna) {

            if ($columna->isVisibleEnTabla()) {

                $columnaNumero = $i;

                if ($columna->isVisibleEnReporte()) {
                    $columnasVisiblesReporte[] = $columnaNumero;
                }

                $i++;
            }
        }

        $contenido = str_replace('$COLUMNAS_VISIBLES_REPORTE$', implode($columnasVisiblesReporte, ', '), $contenido);

        $generadorTablaHtml = new CreadorContenidoTablaHtml();
        $tablaHtml = $generadorTablaHtml->getContenido($tabla);

        $contenido = str_replace('$TABLA_HTML$', $tablaHtml, $contenido);
        $contenido = str_replace('$LISTA_FOR_EACH$', '$lista_' . $tabla->getNombreTabla(), $contenido);

        return $contenido;
    }

}
