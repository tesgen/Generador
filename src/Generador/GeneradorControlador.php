<?php

namespace TesGen\Generador\Generador;

use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Utils\ArchivoUtil;
use TesGen\Generador\Utils\Mapeador;

class GeneradorControlador {

    /**
     * @var Tabla
     */
    private $tabla;

    /**
     * @var Tabla[]
     */
    private $tablas;

    private $contenidoImport;
    private $contenidoQueryTablasForaneas;
    private $contenidoWith;
    private $withShow;

    /**
     * @var Tabla[]
     */
    private $tablasRelaciones;

    private $esParaApi;

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     */
    public function generar(Tabla $tabla, $tablas, bool $esParaApi) {

        $this->tabla = $tabla;
        $this->tablas = $tablas;
        $this->withShow = '';
        $this->esParaApi = $esParaApi;

        $nombreClase = $tabla->getNombreClase();
        $nombreTabla = $tabla->getNombreTabla();
        $nombreNatural = $tabla->getNombreNatural();
        $nombrePlural = $tabla->getNombrePlural();

        $archivo = $nombreClase . 'Controller.php';

        if ($tabla->isDetalle()) {
            return;
        }

        if ($esParaApi) {

            $directorio = app_path() . '\\Http\\Controllers\API';

            $nameSpace = "App\Http\Controllers\API";
            $directorio_pantilla = base_path('vendor\\tesgen\\generador\\plantillas\\controlador_api.txt');

            $directorio_pantilla_base = base_path('vendor\\tesgen\\generador\\plantillas\\api\\BaseApiController.txt');
            $contenidoPlantillaBase = file_get_contents($directorio_pantilla_base);

            ArchivoUtil::createFile($directorio, "BaseApiController.php", $contenidoPlantillaBase);

        } else {
            $directorio = app_path() . '\\Http\\Controllers';
            $nameSpace = "App\Http\Controllers";
            $directorio_pantilla = base_path('vendor\\tesgen\\generador\\plantillas\\controlador.txt');
        }

        $contenidoPlantilla = file_get_contents($directorio_pantilla);

        $this->establecerContenidoRelaciones();


        $contenidoPlantilla = str_replace('$QUERY_TABLAS_FORANEAS$', $this->contenidoQueryTablasForaneas, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$WITHS$', $this->contenidoWith, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CONTENIDO_STORE$', $this->obtenerContenidoStore(), $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$CONTENIDO_UPDATE$', $this->obtenerContenidoUpdate(), $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$IMPORTS$', $this->contenidoImport, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$NAME_SPACE$', $nameSpace, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$NOMBRE_MODELO$', $nombreClase, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$NOMBRE_TABLA$', $nombreTabla, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$NOMBRE_NATURAL$', $nombreNatural, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$NOMBRE_PLURAL$', $nombrePlural, $contenidoPlantilla);
        $contenidoPlantilla = str_replace('$WITH_SHOW$', $this->withShow, $contenidoPlantilla);

        if ($esParaApi) {
            $creadorRulesCampoCreate = new CreadorRulesCampo($tabla, true);
            $contenidoReturnCreate = $creadorRulesCampoCreate->getContenidoReturn();
            $contenidoMessagesCreate = $creadorRulesCampoCreate->getContenidoMessages();

            $contenidoPlantilla = str_replace('$CONTENIDO_RULES_STORE$', $contenidoReturnCreate, $contenidoPlantilla);
            $contenidoPlantilla = str_replace('$CONTENIDO_MESSAGES_STORE$', $contenidoMessagesCreate, $contenidoPlantilla);

            $creadorRulesCampoUpdate = new CreadorRulesCampo($tabla, false);
            $contenidoReturnUpdate = $creadorRulesCampoUpdate->getContenidoReturn();
            $contenidoMessagesUpdate = $creadorRulesCampoUpdate->getContenidoMessages();

            $contenidoPlantilla = str_replace('$CONTENIDO_RULES_UPDATE$', $contenidoReturnUpdate, $contenidoPlantilla);
            $contenidoPlantilla = str_replace('$CONTENIDO_MESSAGES_UPDATE$', $contenidoMessagesUpdate, $contenidoPlantilla);
        }

        $contenido = $contenidoPlantilla;

        ArchivoUtil::createFile($directorio, $archivo, $contenido);

    }

    private function establecerContenidoRelaciones() {

        $this->establecerTablasRelaciones();

        $this->contenidoImport = 'use App\$NOMBRE_MODELO$' . ";\n";

        if (!$this->esParaApi) {
            $this->contenidoImport .= 'use App\Http\Requests\\' . $this->tabla->getNombreClase() . "CreateRequest;\n";
            $this->contenidoImport .= 'use App\Http\Requests\\' . $this->tabla->getNombreClase() . "UpdateRequest;\n";
        }

        foreach ($this->tablasRelaciones as $tr) {
            $this->contenidoImport .= 'use App\\' . $tr->getNombreClase() . ";\n";
            $this->contenidoQueryTablasForaneas .= str_repeat("\t", 2) . '$lista_' . $tr->getNombreTabla() . ' = ' . $tr->getNombreClase() . '::all();' . "\n";
            $this->contenidoWith .= "\n" . '            ->with(\'lista_' . $tr->getNombreTabla() . '\', $lista_' . $tr->getNombreTabla() . ')';
        }

        if ($this->tabla->isMaestro()) {

            $tablaDetalle = Mapeador::getTablaDetalle($this->tabla, $this->tablas);

            $nombreTablaAuxiliar = $this->tabla->getTablaAuxiliar();
            $this->withShow = "with('lista_" . $tablaDetalle->getNombreTabla() . "')->";

            foreach ($this->tablas as $tAuxiliar) {

                if ($tAuxiliar->getNombreTabla() === $nombreTablaAuxiliar) {
                    $this->contenidoImport .= 'use App\\' . $tAuxiliar->getNombreClase() . ";\n";
                    $this->contenidoQueryTablasForaneas .= str_repeat("\t", 2) . '$lista_' . $tAuxiliar->getNombreTabla() . ' = ' .
                        $tAuxiliar->getNombreClase() . '::all();' . "\n";
                    $this->contenidoWith .= "\n" . '            ->with(\'lista_' . $tAuxiliar->getNombreTabla() .
                        '\', $lista_' . $tAuxiliar->getNombreTabla() . ')';

                    break;
                }

            }
        }
    }

    private function establecerTablasRelaciones() {

        $this->tablasRelaciones = array();
        $nombresTablasRelaciones = array();

        foreach ($this->tabla->getRelaciones() as $relacion) {
            foreach ($this->tablas as $t) {
                if ($t->getNombreTabla() === $relacion->getNombreTablaRelacion()) {
                    if (!in_array($relacion->getNombreTablaRelacion(), $nombresTablasRelaciones)) {
                        $this->tablasRelaciones[] = $t;
                        $nombresTablasRelaciones[] = $relacion->getNombreTablaRelacion();
                    }
                }
            }
        }
    }

    /**
     *
     */
    private function obtenerContenidoStore(): string {

        $nombreTabla = $this->tabla->getNombreTabla();
        $nombreClase = $this->tabla->getNombreClase();

        $contenidoStore = '';

        if ($this->tabla->isMaestro()) {

            $tablaDetalle = Mapeador::getTablaDetalle($this->tabla, $this->tablas);

//            $contenidoStore .= '        $input = json_decode($request->getContent(), true);' . "\n\n";
            $contenidoStore .= '        $input = $request->all();' . "\n\n";
            $contenidoStore .= '        $' . $nombreTabla . ' = ' . $nombreClase . '::create($input);' . "\n";
//            $contenidoStore .= '        $' . $nombreTabla . ' = new ' . $nombreClase . '();' . "\n";
//            $contenidoStore .= '        $' . $nombreTabla . '->fill($request->all());' . "\n";

//            foreach ($this->tabla->getColumnas() as $columna) {
//                if ($columna->getNombreColumna() === $this->tabla->getClavePrimaria()) {
//                    continue;
//                }
//                $contenidoStore .= '        $' . $nombreTabla . '->' . $columna->getNombreColumna()
//                    . ' = $input[\'' . $columna->getNombreColumna() . '\'];' . "\n";
//            }
//            $contenidoStore .= '        $' . $nombreTabla . '->save();' . "\n";

            $contenidoStore .= "\n";

            $nombreIdDetalle = '';

            foreach ($this->tabla->getColumnas() as $columna) {
                if ($columna->getNombreColumna() === $this->tabla->getClavePrimaria()) {
                    $nombreIdDetalle = $columna->getNombreColumna();
                    $contenidoStore .= '        $' . $nombreIdDetalle .
                        ' = $' . $nombreTabla . '->' . $columna->getNombreColumna() . ';' . "\n\n";
                    break;
                }
            }

//            $contenidoStore .= '        $detalles = $input[\'detalles\'];' . "\n\n";
            if ($this->esParaApi) {
                //json_decode($input['detalles'], true)
                $contenidoStore .= '        $detalles = isset($input[\'detalles\']) ? json_decode($input[\'detalles\'], true) : [];' . "\n\n";
            } else {
                $contenidoStore .= '        $detalles = isset($input[\'detalles\']) ? $input[\'detalles\'] : [];' . "\n\n";
            }

            $contenidoStore .= '        foreach($detalles as $detalle) {' . "\n";
            $contenidoStore .= '            $' . $tablaDetalle->getNombreTabla() . ' = new ' . $tablaDetalle->getNombreClase() . '();' . "\n";
            $this->contenidoImport .= 'use App\\' . $tablaDetalle->getNombreClase() . ";\n";

            foreach ($tablaDetalle->getColumnas() as $columna) {
                if ($columna->getNombreColumna() === $tablaDetalle->getClavePrimaria()) {
                    continue;
                }
                if ($columna->getNombreColumna() === $this->tabla->getClavePrimaria()) {
                    $contenidoStore .= '            $' . $tablaDetalle->getNombreTabla() . '->' . $columna->getNombreColumna() .
                        ' = $' . $columna->getNombreColumna() . ';' . "\n";
                }
//                else {
//                    $contenidoStore .= '            $' . $tablaDetalle->getNombreTabla() . '->' . $columna->getNombreColumna() .
//                        ' = $detalle[\'' . $columna->getNombreColumna() . '\'];' . "\n";
//                }

            }

            $contenidoStore .= '            $' . $tablaDetalle->getNombreTabla() . '->fill($detalle);' . "\n";
            $contenidoStore .= '            $' . $tablaDetalle->getNombreTabla() . '->save();' . "\n";

            $contenidoStore .= '        }' . "\n\n";
//            $contenidoStore .= '        return response()->json($' . $nombreIdDetalle . ');' . "\n";
        } else {

//            $contenidoStore = "        $$nombreTabla = new $nombreClase();\n";
//            $contenidoStore .= '        $' . $nombreTabla . '->fill($request->all());' . "\n";
//            $contenidoStore .= "        $$nombreTabla" . "->save();" . "\n";
            $contenidoStore .= '        ' . $nombreClase . '::create($request->all());' . "\n";

//            $contenidoStore .= "        $nombreClase::insert(\$request->all());" . "\n";
//            $contenidoStore .= "        return response()->json(true);";
        }

        return $contenidoStore;

    }

    private function obtenerContenidoUpdate(): string {

        $nombreTabla = $this->tabla->getNombreTabla();
        $nombreClase = $this->tabla->getNombreClase();
        $nombreId = $this->tabla->getClavePrimaria();

        $contenidoUpdate = '';

//        if ($this->esMaestro) {
//
//        } else {
        if ($this->esParaApi) {
            $contenidoUpdate = '        $' . $nombreTabla . ' = $NOMBRE_MODELO$::find($id);' . "\n";
            $contenidoUpdate .= '        $' . $nombreTabla . '->fill($request->all());' . "\n";
            $contenidoUpdate .= '        $' . $nombreTabla . '->save();' . "\n";
        } else {
            $contenidoUpdate .= "        $nombreClase::where('$nombreId', \$id)->update(\$request->all());" . "\n";
        }
//        $contenidoUpdate .= "        return response()->json(true);";
//        }

        return $contenidoUpdate;

    }

}
