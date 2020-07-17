<?php

namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Columna;
use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\Mapeador;

class CreadorContenidoCamposCrear {

    /**
     * @var string
     */
    private $contenidoLimpiarCamposDetalle = '';

    /**
     * @var string
     */
    private $definicionVariablesJsDetalle = '';

    /**
     * @var string
     */
    private $asignacionVariablesJsDetalle = '';

    /**
     * @var string
     */
    private $cabeceraTablaDetalle = '';

    /**
     * @var string
     */
    private $filasTablaDetalle = '';

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @return string el contenido del arhivo campos
     */
    public function getContenido(Tabla $tabla, $tablas): string {

        $nombreTabla = $tabla->getNombreTabla();
        $cantidadIndentacion = $tabla->isMaestro() || $tabla->isDetalle() ? 10 : 8;
        $campo = '';

        $columnas = $tabla->getColumnas();

        usort($columnas, function (Columna $first, Columna $second) {

            $xFirst = $first->getJsonInputGuardar()['x'];
            $xSecond = $second->getJsonInputGuardar()['x'];

            $yFirst = $first->getJsonInputGuardar()['y'];
            $ySecond = $second->getJsonInputGuardar()['y'];

            if ($yFirst === $ySecond) return $xFirst - $xSecond;
            return $yFirst - $ySecond;
        });

        $idTablaMaestro = '';
        $idTablaAuxiliar = '';

        if ($tabla->isDetalle()) {

            $tablaMaestro = Mapeador::getTablaMaestro($tabla, $tablas);
            $idTablaMaestro = $tablaMaestro->getClavePrimaria();
            $tablaAuxiliar = Mapeador::getTablaAuxiliar($tablaMaestro, $tablas);
            $idTablaAuxiliar = $tablaAuxiliar->getClavePrimaria();
        }

        for ($i = 0; $i < count($columnas);) {

            $campo .= str_repeat("\t", $cantidadIndentacion) . '<div class="form-row">' . "\n";

            $columna = $columnas[$i];

//            $esClaveForanea = $columna->isClaveForanea();

            $bloque = $columna->getJsonInputGuardar();

            $y = $bloque['y'];

            while ($y === $bloque['y']) {

                $esClaveForanea = $columna->isClaveForanea();

                $nombreColumna = $columna->getNombreColumna();
                $nombreNatural = $columna->getNombreNatural();

                //campo no sea id o autoincremental y sea guardable
                $campoMostrable = (($columna->getNombreColumna() !== $tabla->getClavePrimaria() || !$columna->isAutoIncrement())
                        /*&& $columna->isCampoMostrable()*/) && ($idTablaMaestro !== $nombreColumna);

                if ($campoMostrable) {

                    if ($columna->isCampoMostrable()) {

                        $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '<div class="form-group col-md-' . $bloque['width'] . '">' . "\n";
                        $campo .= str_repeat("\t", $cantidadIndentacion + 2) . '<label for="' . $nombreColumna . '">' . $nombreNatural . '</label>' . "\n";

                        if ($esClaveForanea) {

                            $tablaRelacion = Mapeador::getTablaRelacion($tabla, $columna);

                            $nombreTablaRelacion = $tablaRelacion->getNombreTablaRelacion();
                            $nombreIdTablaRelacion = $tablaRelacion->getNombreIdTablaRelacion();

                            $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '<select class="form-control" id="' . $columna->getNombreColumna() . '" name="' . $columna->getNombreColumna() . '">' . "\n";
                            $campo .= str_repeat("\t", $cantidadIndentacion + 2) . '@foreach($lista_' . $nombreTablaRelacion . ' as $' . $nombreTablaRelacion . ')' . "\n";
                            $campo .= str_repeat("\t", $cantidadIndentacion + 3) . '<option value="{{$' . $nombreTablaRelacion . '->' . $nombreIdTablaRelacion . '}}"' . "\n";
                            $campo .= str_repeat("\t", $cantidadIndentacion + 4) . '{{ ($' . $nombreTablaRelacion . '->' . $nombreIdTablaRelacion . ' == ($' . $nombreTabla . '->' . $columna->getNombreColumna() . ' ?? \'\') ? "selected" : "") }}>' . "\n";
                            $campo .= str_repeat("\t", $cantidadIndentacion + 4) . '{{$' . $nombreTablaRelacion . '->campo_referente}}' . "\n";
                            $campo .= str_repeat("\t", $cantidadIndentacion + 3) . '</option>' . "\n";
                            $campo .= str_repeat("\t", $cantidadIndentacion + 2) . '@endforeach' . "\n";
                            $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '</select>' . "\n";

                            $this->contenidoLimpiarCamposDetalle .= str_repeat("\t", 3) . '$("#' . $columna->getNombreColumna() . '").prop(\'selectedIndex\', 0);' . "\n";

                        } else {

                            if ($columna->isConjuntoDeValoresGuardar()) {

                                $valores = explode(",", $columna->getValorConjuntoDeValoresGuardar());

                                $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '<select class="form-control" id="' . $nombreColumna . '" name="' . $nombreColumna . '">' . "\n";

                                foreach ($valores as $valor) {
                                    $campo .= str_repeat("\t", $cantidadIndentacion + 3) . '<option value="' . $valor . '">' . "\n";
                                    $campo .= str_repeat("\t", $cantidadIndentacion + 4) . $valor . "\n";
                                    $campo .= str_repeat("\t", $cantidadIndentacion + 3) . '</option>' . "\n";
                                }

                                $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '</select>' . "\n";
                                $this->contenidoLimpiarCamposDetalle .= str_repeat("\t", 3) . '$("#' . $columna->getNombreColumna() . '").prop(\'selectedIndex\', 0);' . "\n";

                            } else {

                                $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '<input class="form-control" name="' .
                                    $columna->getNombreColumna() . '" ' . "\n";
                                $campo .= str_repeat("\t", $cantidadIndentacion + 3) . 'value="$VALOR$" type="text" id="' . $nombreColumna . '" $READ_ONLY$>' . "\n";

                                if ($columna->isAutomaticoGuardar() || $columna->isFormulaGuardar()
                                    || $columna->isAutoincrementalGuardar()) {
                                    $campo = str_replace('$READ_ONLY$', 'readonly', $campo);
                                } else {
                                    $campo = str_replace('$READ_ONLY$', '', $campo);
                                }

                                $valor = '';

                                if ($columna->isCampoDeTextoGuardar()) {
                                    $valor = '';
                                    $this->contenidoLimpiarCamposDetalle .= str_repeat("\t", 3) . '$("#' . $columna->getNombreColumna() . '").val(\'\');' . "\n";
                                } elseif ($columna->isAutomaticoGuardar()) {
                                    $valor = $columna->getValorAutomaticoGuardar();
                                } elseif ($columna->isFormulaGuardar()) {
                                    $valor = '0';
                                    $this->contenidoLimpiarCamposDetalle .= str_repeat("\t", 3) . '$("#' . $columna->getNombreColumna() . '").val(0);' . "\n";
                                } elseif ($columna->isAutoincrementalGuardar()) {
                                    $valor = '{{$' . $columna->getNombreColumna() . '_autoincremental ?? \'\'}}';
                                }

                                $campo = str_replace('$VALOR$', $valor, $campo);

                            }

                        }

                        $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '</div>' . "\n";

                    }

                    //para detalle
                    $valorPorDefecto = '';

                    if ($columna->isCampoDeTextoGuardar() || $columna->isConjuntoDeValoresGuardar()) {
                        $valorPorDefecto = "$(\"#$nombreColumna\").val()";
                    } else if ($columna->isAutomaticoGuardar()) {
                        $valorPorDefecto = "'" . $columna->getValorAutomaticoGuardar() . "'";
                    } else if ($columna->isFormulaGuardar()) {
                        $valorPorDefecto = "calcular_" . $columna->getNombreColumna() . "()";
                    } else {
                        $valorPorDefecto = 'null';
                    }

                    $this->definicionVariablesJsDetalle .= str_repeat("\t", 4) . "var $nombreColumna = ${valorPorDefecto};" . "\n";
                    $this->asignacionVariablesJsDetalle .= str_repeat("\t", 6) . "$nombreColumna: $nombreColumna,\n";

                    if ($columna->isCampoMostrable()) {
                        $this->cabeceraTablaDetalle .= str_repeat("\t", 12) . '<th>' . $nombreNatural . '</th>' . "\n";

                        if ($nombreColumna === $idTablaAuxiliar) {
                            $nombreFilaTabla = 'campo_referente';
                        } else {
                            $nombreFilaTabla = $nombreColumna;
                        }

                        $this->filasTablaDetalle .= str_repeat("\t", 5) . "\"<td>\" + detalles[i].$nombreFilaTabla + \"</td>\" +" . "\n";
                    }


                }

                if (count($columnas) - ($i += 1) <= 0) {
                    break;
                }

                $columna = $columnas[$i];
                $bloque = $columna->getJsonInputGuardar();

            }

            $campo .= str_repeat("\t", $cantidadIndentacion) . '</div>' . "\n";
        }

        if (!$tabla->isMaestro() && !$tabla->isDetalle()) {
            $campo .= str_repeat("\t", $cantidadIndentacion) . '<div class="form-row">' . "\n";
            $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '<div class="form-group col-sm-12">' . "\n";
            $campo .= str_repeat("\t", $cantidadIndentacion + 2) . '<button type="submit" form="form" id="boton_submit" class="btn btn-primary">Aceptar</button>' . "\n";
            $campo .= str_repeat("\t", $cantidadIndentacion + 2) . '<a href="{{url(\'' . $nombreTabla . '\')}}" class="btn btn-default">Cancelar</a>' . "\n";
            $campo .= str_repeat("\t", $cantidadIndentacion + 1) . '</div>' . "\n";
            $campo .= str_repeat("\t", $cantidadIndentacion) . '</div>' . "\n";
        }

        return $campo;
    }

    /**
     * @return string
     */
    public function getContenidoLimpiarCamposDetalle(): string {
        return $this->contenidoLimpiarCamposDetalle;
    }

    /**
     * @return string
     */
    public function getDefinicionVariablesJsDetalle(): string {
        return $this->definicionVariablesJsDetalle;
    }

    /**
     * @return string
     */
    public function getAsignacionVariablesJsDetalle(): string {
        return $this->asignacionVariablesJsDetalle;
    }

    /**
     * @return string
     */
    public function getCabeceraTablaDetalle(): string {
        return $this->cabeceraTablaDetalle;
    }

    /**
     * @return string
     */
    public function getFilasTablaDetalle(): string {
        return $this->filasTablaDetalle;
    }

}
