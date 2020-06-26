<?php


namespace TesGen\Generador\Generador\vista;

use TesGen\Generador\Modelo\Columna;
use TesGen\Generador\Modelo\Tabla;

class CreadorContenidoFuncionesFormula {

    /**
     * @param Tabla $tabla
     * @param bool $esParaGuardar
     * @return string
     */
    public static function getFunciones(Tabla $tabla, bool $esParaGuardar) {

        $contenido = '';

        foreach ($tabla->getColumnas() as $columna) {

            if ($esParaGuardar) {

                if ($columna->isFormulaGuardar()) {
                    $contenido .= self::getFuncion($columna, true);
                }

            } else {
                if ($columna->isFormulaActualizar()) {
                    $contenido .= self::getFuncion($columna, false);
                }
            }

        }

        return $contenido;
    }

    private static function getFuncion(Columna $columna, bool $esParaGuardar) {

        $nombreColumna = $columna->getNombreColumna();

        $funcion = "        function calcular_" . $nombreColumna . "() {\n\n";

        if ($esParaGuardar) {
            $formula = $columna->getValorFormulaGuardar();
        } else {
            $formula = $columna->getValorFormulaActualizar();
        }

        $variables = self::getVariables($formula);

        foreach ($variables as $variable) {
            $funcion .= "            var ${variable} = Number($(\"#${variable}\").val());\n";
        }

        $funcion .= "\n";

        $funcion .= "            var " . $nombreColumna . " = 0;\n";

        $funcion .= "\n";

        $condiciones = [];

        foreach ($variables as $variable) {
            $condiciones[] = "!isNaN(${variable})";
        }

        $condicionCompleta = implode($condiciones, " && ");


        $funcion .= "            if (${condicionCompleta}) {\n";
        $funcion .= "                " . $nombreColumna . " = " . self::getFormulaSinCorchetes($formula) . ";\n";
        $funcion .= "            }\n";

        $funcion .= "\n";

        $funcion .= "            $(\"#" . $nombreColumna . "\").val(" . $nombreColumna . ").trigger('change');\n";

        $funcion .= "        }\n\n";

        return $funcion;

    }

    /**
     * @param $formula
     * @return array
     */
    private static function getVariables($formula) {
        preg_match_all('/\[(.*?)]/s', $formula, $matches);

        $variables = array_unique($matches[1]);

        return $variables;
    }

    /**
     * @param $formula
     * @return string
     */
    private static function getFormulaSinCorchetes($formula) {

        $formula = str_replace(["[", "]"], "", $formula);

        return $formula;
    }

    /**
     * @param Tabla $tabla
     * @param bool $esParaGuardar
     * @return string
     */
    public static function getContenidoOnReady(Tabla $tabla, bool $esParaGuardar) {

        $contenido = '';

        foreach ($tabla->getColumnas() as $columna) {

            if ($esParaGuardar) {

                if ($columna->isFormulaGuardar()) {
                    $contenido .= self::getOnkeyUp($columna, true);
                }

            } else {
                if ($columna->isFormulaActualizar()) {
                    $contenido .= self::getOnkeyUp($columna, false);
                }
            }


        }

        return $contenido;
    }

    private static function getOnkeyUp(Columna $columna, bool $esParaGuardar) {

        $nombreColumna = $columna->getNombreColumna();

        if ($esParaGuardar) {
            $formula = $columna->getValorFormulaGuardar();
        } else {
            $formula = $columna->getValorFormulaActualizar();
        }

        $variables = self::getVariables($formula);

        $variablesArray = [];

        foreach ($variables as $variable) {
            $variablesArray[] = "#${variable}";
        }

        $variablesConcatenados = implode($variablesArray, ", ");

        $contenido = "            $(\"${variablesConcatenados}\").on(\"change keyup\", function () {
                calcular_${nombreColumna}();
            });\n\n";

        return $contenido;

    }

}
