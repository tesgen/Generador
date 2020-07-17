<?php


namespace TesGen\Generador\Generador;


use TesGen\Generador\Modelo\Columna;
use TesGen\Generador\Modelo\Tabla;

class CreadorRulesCampo {

    /**
     * @var string
     */
    private $contenidoReturn;

    /**
     * @var string
     */
    private $contenidoMessages;

    /**
     * CreadorRulesCampo constructor.
     */
    public function __construct(Tabla $tabla, bool $esParaGuardar) {
        $this->setContenido($tabla, $esParaGuardar);
    }

    private function setContenido(Tabla $tabla, bool $esParaGuardar) {

        $contenidoReturn = "";
        $contenidoMessages = "";

        foreach ($tabla->getColumnas() as $columna) {

            if ($columna->isAutoincrementalGuardar()) {
                continue;
            }

            if ($esParaGuardar || (!$esParaGuardar && $tabla->isFormActualizarIgualQueGuardar())) {
                if (($columna->getNombreColumna() === $tabla->getClavePrimaria() && $columna->isAutoIncrement())
                    || !$columna->isCampoMostrable()) {
                    continue;
                }
            } else {
                if ($columna->getNombreColumna() === $tabla->getClavePrimaria()
                    || !$columna->isVisibleEnFormularioActualizar() || !$columna->isCampoActualizable()) {
                    continue;
                }
            }

            $validaciones = $this->getReglasCampo($tabla, $columna, $esParaGuardar);

            $contenidoReturn .= "            '" . $columna->getNombreColumna() . "' => \"$validaciones\",\n";

            $messages = $this->getMensajesCampo($tabla, $columna, $esParaGuardar);

            if (strlen($messages) > 0) {
                $contenidoMessages .= $messages . ",\n";
            }

        }

        $this->contenidoReturn = $contenidoReturn;
        $this->contenidoMessages = $contenidoMessages;
    }

    private function getReglasCampo(Tabla $tabla, Columna $columna, bool $esParaGuardar) {
        if ($esParaGuardar || (!$esParaGuardar && $tabla->isFormActualizarIgualQueGuardar())) {
            $validacion = $columna->getValidacionGuardar();
        } else {
            $validacion = $columna->getValidacionActualizar();
        }

        $validacionesArray = [];

        if ($validacion->isRequerido()) {
            $validacionesArray[] = 'required';
        }

        if ($validacion->isLongitudMinima()) {
            $longitudMinima = $validacion->getValorLongitudMinima();
            $longitudMinima = $longitudMinima === null ? 5 : $longitudMinima;
            $validacionesArray[] = 'min:' . $longitudMinima;
        }

        if ($validacion->isLongitudMaxima()) {
            $longitudMaxima = $validacion->getValorLongitudMaxima();
            $longitudMaxima = $longitudMaxima === null ? 255 : $longitudMaxima;
            $validacionesArray[] = 'max:' . $longitudMaxima;
        }

        if ($validacion->isUnico()) {

            $validacionUnico = 'unique:' . $tabla->getNombreTabla() . ',' . $columna->getNombreColumna();
            if (!$esParaGuardar) {
                $validacionUnico .= ',$id,' . $tabla->getClavePrimaria();
            }
            $validacionesArray[] = $validacionUnico;
        }

        $validacion = implode($validacionesArray, '|');

        return $validacion;
    }

    private function getMensajesCampo(Tabla $tabla, Columna $columna, bool $esParaGuardar) {

        if ($esParaGuardar || (!$esParaGuardar && $tabla->isFormActualizarIgualQueGuardar())) {
            $validacion = $columna->getValidacionGuardar();
        } else {
            $validacion = $columna->getValidacionActualizar();
        }

        $validacionesArray = [];

        $nombreColumna = $columna->getNombreColumna();
        $nombreNatural = $columna->getNombreNatural();

        if ($validacion->isRequerido()) {
            $validacionesArray[] = "            '${nombreColumna}.required' => 'El campo ${nombreNatural} es obligatorio.'";
        }

        if ($validacion->isLongitudMinima()) {
            $longitudMinima = $validacion->getValorLongitudMinima();
            $longitudMinima = $longitudMinima === null ? 5 : $longitudMinima;
            $validacionesArray[] = "            '${nombreColumna}.min' => 'El campo ${nombreNatural} debe tener una longitud mínima de ${longitudMinima}.'";
        }

        if ($validacion->isLongitudMaxima()) {
            $longitudMaxima = $validacion->getValorLongitudMaxima();
            $longitudMaxima = $longitudMaxima === null ? 255 : $longitudMaxima;
            $validacionesArray[] = "            '${nombreColumna}.max' => 'El campo ${nombreNatural} debe tener una longitud máxima de ${longitudMaxima}.'";
        }

        if ($validacion->isUnico()) {
            $validacionesArray[] = "            '${nombreColumna}.unique' => 'El dato ingresado para ${nombreNatural} ya existe.'";
        }

        $validacionesConcatenacion = implode($validacionesArray, ",\n");

        return $validacionesConcatenacion;

    }

    /**
     * @return string
     */
    public function getContenidoReturn(): string {
        return $this->contenidoReturn;
    }

    /**
     * @return string
     */
    public function getContenidoMessages(): string {
        return $this->contenidoMessages;
    }

}
