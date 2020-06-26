<?php

namespace TesGen\Generador\Generador;

use TesGen\Generador\Generador\vista\CreadorContenidoCreate;
use TesGen\Generador\Generador\vista\CreadorContenidoEdit;
use TesGen\Generador\Generador\vista\CreadorContenidoIndex;
//use TesGen\Generador\Generador\vista\CreadorContenidoMdCreate;
use TesGen\Generador\Generador\vista\CreadorContenidoMdCreate;
use TesGen\Generador\Generador\vista\CreadorContenidoShow;
use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\ArchivoUtil;
use TesGen\Generador\Utils\Constante;

class GeneradorVista {

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     */
    public function generar(Tabla $tabla, $tablas) {

        if ($tabla->isDetalle()) {
            return;
        }

        $archivoCreate = Constante::ARCHIVO_PLANTILLA_CREATE;
        $archivoEdit = Constante::ARCHIVO_PLANTILLA_EDIT;
        $archivoIndex = Constante::ARCHIVO_PLANTILLA_INDEX;
        $archivoShow = Constante::ARCHIVO_PLANTILLA_SHOW;
//        $archivoMdCreate = Constante::ARCHIVO_PLANTILLA_CREATE_MD;
        $archivoMdCreate = Constante::ARCHIVO_PLANTILLA_CREATE_MD2;

        $archivos = [$archivoCreate, $archivoEdit, $archivoIndex, $archivoShow];

        $nombreTabla = $tabla->getNombreTabla();
        $nombreClase = $tabla->getNombreClase();

        $clavePrimaria = $tabla->getClavePrimaria();

        $directorioPlantillaVista = base_path(Constante::DIRECTORIO_PLANTILLAS_VISTA);

        foreach ($archivos as $archivo) {

            $campo = '';
            $contenido = '';

            if ($archivo === $archivoIndex) {

                $generadorIndex = new CreadorContenidoIndex();
                $directorioPlantillaIndex = $directorioPlantillaVista . $archivoIndex . '.txt';
                $contenido = $generadorIndex->getContenido($tabla, $tablas, $directorioPlantillaIndex);

            } else if ($archivo === $archivoShow) {

                $generadorShow = new CreadorContenidoShow();
                $directorioPlantillaShow = $directorioPlantillaVista . $archivoShow . '.txt';
                $contenido = $generadorShow->getContenido($tabla, $tablas, $directorioPlantillaShow);

            } else if ($archivo === $archivoCreate) {

                if ($tabla->isMaestro()) {
//                    $generadorMdCreate = new CreadorContenidoMdCreate();
                    $generadorMdCreate = new CreadorContenidoMdCreate();
                    $directorioPlantillaMdCreate = $directorioPlantillaVista . $archivoMdCreate . '.txt';
                    $contenido = $generadorMdCreate->getContenido($tabla, $tablas, $directorioPlantillaMdCreate);
                } else {
                    $generadorCreate = new CreadorContenidoCreate();
                    $directorioPlantillaCreate = $directorioPlantillaVista . $archivoCreate . '.txt';
                    $contenido = $generadorCreate->getContenido($tabla, $tablas, $directorioPlantillaCreate);
                }

            } else if ($archivo === $archivoEdit) {

                $generadorEdit = new CreadorContenidoEdit();
                $directorioPlantillaEdit = $directorioPlantillaVista . $archivoEdit . '.txt';
                $contenido = $generadorEdit->getContenido($tabla, $tablas, $directorioPlantillaEdit);

            }

            $contenido = str_replace('$CLAVE_PRIMARIA$', $clavePrimaria, $contenido);
            $contenido = str_replace('$NOMBRE_PLURAL$', $tabla->getNombrePlural(), $contenido);
            $contenido = str_replace('$NOMBRE_NATURAL$', $tabla->getNombreNatural(), $contenido);
            $contenido = str_replace('$NOMBRE_TABLA$', $nombreTabla, $contenido);
            $contenido = str_replace('$NOMBRE_CLASE$', $nombreClase, $contenido);

            $directorioAGenerar = resource_path() . '\\views\\' . $tabla->getNombreTabla();
            $archivoAGenerar = $archivo . '.blade.php';

            ArchivoUtil::createFile($directorioAGenerar, $archivoAGenerar, $contenido);
        }

    }

}
