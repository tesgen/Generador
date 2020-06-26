<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Columna;
use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\Constante;

class CreadorContenidoTablaHtml {

    /**
     * @param Tabla $tabla
     * @return string el contenido del arhivo index
     */
    public function getContenido(Tabla $tabla): string {

        $archivoTablaHtml = Constante::ARCHIVO_PLANTILLA_TABLA_HTML;
        $directorioPlantillaTablaHtml = base_path(Constante::DIRECTORIO_PLANTILLAS_VISTA) . $archivoTablaHtml . '.txt';

        $filasTabla = '';
        $cabeceraTabla = '';

        $contenido = file_get_contents($directorioPlantillaTablaHtml);

        $columnas = $tabla->getColumnas();

        usort($columnas, function (Columna $first, Columna $second) {
            return strcmp($first->getOrdenTablaHtml(), $second->getOrdenTablaHtml());
        });

        foreach ($columnas as $columna) {

            if ($columna->getNombreColumna() === 'created_at' || $columna->getNombreColumna() === 'updated_at') {
                continue;
            }

            if ($columna->isVisibleEnTabla()) {

                if ($columna->isClaveForanea()) {

                    foreach ($tabla->getRelaciones() as $relacion) {
                        if ($relacion->getNombreClaveForanea() === $columna->getNombreColumna()) {
                            break;
                        }
                    }

                    $cabeceraTabla .= str_repeat("\t", 9) . '<th>' . $columna->getNombreNatural() . '</th>' . "\n";
                    $filasTabla .= str_repeat("\t", 10) . '<td>{{$item->' .
                        $relacion->getNombreTablaRelacion() . '->campo_referente}}</td>' . "\n";

                } else {
                    $cabeceraTabla .= str_repeat("\t", 9) . '<th>' . $columna->getNombreNatural() . '</th>' . "\n";
                    $filasTabla .= str_repeat("\t", 10) . '<td>{{$item->' . $columna->getNombreColumna() . '}}</td>' . "\n";
                }

            }
        }

        if ($tabla->isDetalle()) {
            $contenido = str_replace('$TITULO_TABLA$', str_repeat("\t", 7) .
                '<h5 class="text-center">' . $tabla->getNombreNatural() . '</h5>' . "\n",
                $contenido);
        } else {
            $cabeceraTabla .= str_repeat("\t", 9) . '<th>Acciones</th>' . "\n";
            $filasTabla .= '                                        <td>
                                            <div class=\'btn-group\'>
                                                <form action="{{url(\'$NOMBRE_TABLA$/\' . $item->$CLAVE_PRIMARIA$)}}" method="POST">
                                                    <a class="btn btn-default btn-xs" href="{{ url(\'$NOMBRE_TABLA$/\' . $item->$CLAVE_PRIMARIA$) }}"><i class="fa fa-eye"></i></a>
                                                    <a class="btn btn-default btn-xs" href="{{ url(\'$NOMBRE_TABLA$/\' . $item->$CLAVE_PRIMARIA$ . \'/edit\') }}"><i class="fa fa-edit"></i></a>
                                                    {{ method_field(\'DELETE\') }}
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <button type="submit" class="btn btn-danger eliminar"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>' . "\n";
            $contenido = str_replace('$TITULO_TABLA$', '', $contenido);
        }

        $contenido = str_replace('$CABECERAS_TABLA$', $cabeceraTabla, $contenido);
        $contenido = str_replace('$FILAS_TABLA$', $filasTabla, $contenido);
        $contenido = str_replace('$NOMBRE_TABLA$', $tabla->getNombreTabla(), $contenido);
        $contenido = str_replace('$CLAVE_PRIMARIA$', $tabla->getClavePrimaria(), $contenido);

        return $contenido;
    }

}
