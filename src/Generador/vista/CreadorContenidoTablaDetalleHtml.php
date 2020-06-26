<?php
namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\Constante;
use TesGen\Generador\Utils\Mapeador;

class CreadorContenidoTablaDetalleHtml {

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @return string
     */
    public function getContenido(Tabla $tabla, $tablas): string {

        $tablaHtmlDetalle = '';

        if ($tabla->isMaestro()) {
            $tablaDetalle = Mapeador::getTablaDetalle($tabla, $tablas);
            $generadorTablaHtml = new CreadorContenidoTablaHtml();
            $tablaHtmlDetalle .= $generadorTablaHtml->getContenido($tablaDetalle);

            $tablaHtmlDetalle = str_replace('$LISTA_FOR_EACH$', '$' . $tabla->getNombreTabla() . '->lista_' . $tablaDetalle->getNombreTabla(), $tablaHtmlDetalle);
        }

        return $tablaHtmlDetalle;
    }

}
