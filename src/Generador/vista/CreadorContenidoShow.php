<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\Mapeador;

class CreadorContenidoShow {

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @return string el contenido del arhivo show
     */
    public function getContenido(Tabla $tabla, $tablas, $directorioPlantillaShow): string {

        $nombreTabla = $tabla->getNombreTabla();

        $contenido = file_get_contents($directorioPlantillaShow);
        $datos = '';
        foreach ($tabla->getColumnas() as $columna) {
            $datos .= str_repeat("\t", 8) . '<tr>' . "\n";

            $nombreNatural = $columna->getNombreNatural();

            $valorCampo = '';

            if ($columna->isClaveForanea()) {

                $tablaRelacion = Mapeador::getTablaRelacion($tabla, $columna);

                $nombreTablaRelacion = $tablaRelacion->getNombreTablaRelacion();
                $nombreIdTablaRelacion = $tablaRelacion->getNombreIdTablaRelacion();

                $valorCampo .= "\n" . str_repeat("\t", 10) . '@foreach($lista_' . $nombreTablaRelacion . ' as $' . $nombreTablaRelacion . ')' . "\n";
                $valorCampo .= str_repeat("\t", 11) . '@if($' . $nombreTablaRelacion . '->' . $nombreIdTablaRelacion . ' === $' . $nombreTabla . '->' . $columna->getNombreColumna() . ')' . "\n";
                $valorCampo .= str_repeat("\t", 12) . '{{$' . $nombreTablaRelacion . '->campo_referente}}' . "\n";
                $valorCampo .= str_repeat("\t", 12) . '@break' . "\n";
                $valorCampo .= str_repeat("\t", 11) . '@endif' . "\n";
                $valorCampo .= str_repeat("\t", 10) . '@endforeach' . "\n" . str_repeat("\t", 9);

            } else {
                $valorCampo = '{{$' . $nombreTabla . '->' . $columna->getNombreColumna() . '}}';

            }

            $datos .= str_repeat("\t", 9) . '<th>' . $nombreNatural . '</th>' . "\n";
            $datos .= str_repeat("\t", 9) . '<td>' .$valorCampo . '</td>' . "\n";

            $datos .= str_repeat("\t", 8) . '</tr>' . "\n";
        }
        $contenido = str_replace('$DATOS$', $datos, $contenido);

        $contenidotablaHtmlDetalle = new CreadorContenidoTablaDetalleHtml();
        $tablaHtmlDetalle = $contenidotablaHtmlDetalle->getContenido($tabla, $tablas);

        $contenido = str_replace('$TABLA_DETALLE$', $tablaHtmlDetalle, $contenido);

        return $contenido;

    }

}
