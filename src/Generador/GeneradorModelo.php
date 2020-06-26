<?php

namespace TesGen\Generador\Generador;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\ArchivoUtil;
use TesGen\Generador\Utils\Mapeador;

class GeneradorModelo {

    /**
     * @param $tabla
     * @param Tabla[] $tablas
     */
    public function generar(Tabla $tabla, $tablas) {

        $contenidoBelongsTo = '';
        $tablasRelaciones = array();
        $nombresTablasRelaciones = array();

        foreach ($tabla->getRelaciones() as $relacion) {
            foreach ($tablas as $t) {
                if ($t->getNombreTabla() === $relacion->getNombreTablaRelacion()) {
                    if (!in_array($relacion->getNombreTablaRelacion(), $nombresTablasRelaciones)) {
                        $tablasRelaciones[] = $t;
                        $nombresTablasRelaciones[] = $relacion->getNombreTablaRelacion();
                    }
                }
            }
        }

        foreach ($tablasRelaciones as $r) {
            $contenidoBelongsTo .= "\n";
            $contenidoBelongsTo .= '    public function ' . $r->getNombreTabla() . '() {' . "\n";
            $contenidoBelongsTo .= '        return $this->belongsTo(\'App\\' . $r->getNombreClase() . '\', \'' .
                $r->getClavePrimaria() . '\');' . "\n";
            $contenidoBelongsTo .= '    }' . "\n";
        }

        $tablasRelaciones = array();
        $arrayBelongsTo = array();

        foreach ($tablas as $t) {

            if ($t->getNombreTabla() === $tabla->getNombreTabla()) {
                continue;
            }

            foreach ($t->getRelaciones() as $relacion) {
                if ($relacion->getNombreTablaRelacion() === $tabla->getNombreTabla()
                    && !in_array($t->getNombreTabla(), $arrayBelongsTo)) {
                    $tablasRelaciones[] = $t;
                    $arrayBelongsTo[] = $t->getNombreTabla();
                }
            }

        }

        $contenidoHasMany = '';
        foreach ($tablasRelaciones as $r) {
            if ($r->isGenerado()) {
                $contenidoHasMany .= "\n";
                $contenidoHasMany .= '    public function lista_' . $r->getNombreTabla() . '() {' . "\n";
                $contenidoHasMany .= '        return $this->hasMany(\'App\\' . $r->getNombreClase() . '\', \'' .
                    $tabla->getClavePrimaria() . '\');' . "\n";
                $contenidoHasMany .= '    }' . "\n";
            }
        }

        //Establecer campos referentes
        $contenidoCampoReferente = '';
        $cantidadReferentes = 0;
        $referentes = array();

        foreach ($tabla->getColumnas() as $columna) {
            if ($columna->isReferente()) {
                $cantidadReferentes++;
                $referentes[] = '$this->' . $columna->getNombreColumna();
            }
        }

        if ($cantidadReferentes === 0) {
            $contenidoCampoReferente .= '$this->' . $tabla->getClavePrimaria();
        } else {
            $contenidoCampoReferente .= implode(' ', $referentes);
        }

        $nombreClase = $tabla->getNombreClase();

        $archivo = $nombreClase . '.php';
        $directorio = app_path();

        $nameSpace = "App";

        $directorio_pantilla = base_path('vendor\\tesgen\\generador\\plantillas\\modelo.txt');

        $contenidoPlantilla = file_get_contents($directorio_pantilla);

        $nombreTabla = $tabla->getNombreTabla();
        $clavePrimaria = $tabla->getClavePrimaria();

        $camposFillableArray = [];

        $createdAt = false;
        $updatedAt = false;

        foreach ($tabla->getColumnas() as $columna) {

            if ($columna->getNombreColumna() === 'created_at'){
                $createdAt = true;
                continue;
            }

            if ($columna->getNombreColumna() === 'updated_at'){
                $updatedAt = true;
                continue;
            }

            if ($columna->getNombreColumna() === $tabla->getClavePrimaria()){
                continue;
            }

            $camposFillableArray[] = "'" . $columna->getNombreColumna() . "'";
        }

        $timeStamps = $createdAt && $updatedAt ? 'true' : 'false';

        $camposFillable = implode($camposFillableArray, ", ");

        $contenidoPlantilla = str_replace('$NAME_SPACE$', $nameSpace, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$NOMBRE_MODELO$', $nombreClase, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$NOMBRE_TABLA$', $nombreTabla, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CLAVE_PRIMARIA$', $clavePrimaria, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CAMPOS_FILLABLE$', $camposFillable, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$BELONGS_TO$', $contenidoBelongsTo, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$HASMANY$', $contenidoHasMany, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CAMPO_REFERENTE$', $contenidoCampoReferente, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$TIME_STAMPS$', $timeStamps, $contenidoPlantilla);

        $contenido = $contenidoPlantilla;
        ArchivoUtil::createFile($directorio, $archivo, $contenido);

    }

}
