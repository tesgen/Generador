<?php

namespace TesGen\Generador\Controllers;

//use App\Role;
//use App\Usuario;
use DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use TesGen\Generador\Generador\GeneradorAutenticacion;
use TesGen\Generador\Generador\GeneradorControlador;
use TesGen\Generador\Generador\GeneradorCreateRequest;
use TesGen\Generador\Generador\GeneradorMenu;
use TesGen\Generador\Generador\GeneradorModelo;
use TesGen\Generador\Generador\GeneradorRutas;
use TesGen\Generador\Generador\GeneradorUpdateRequest;
use TesGen\Generador\Generador\GeneradorVista;
use TesGen\Generador\Modelo\Auntenticacion;
use TesGen\Generador\Modelo\Columna;
use TesGen\Generador\Modelo\Relacion;
use TesGen\Generador\Modelo\Tabla;
use TesGen\Generador\Modelo\Validacion;
use TesGen\Generador\Utils\ArchivoUtil;
use TesGen\Generador\Utils\Mapeador;
use Illuminate\Support\Facades\Route;

class BaseController extends Controller {

    /**
     * @var Tabla
     */
    protected $tablaActual;

    /**
     * @var Tabla[]
     */
    protected $tablas;

    /**
     * @var Mapeador
     */
    private $mapeador;

    /**
     * BaseController constructor.
     */
    public function __construct() {
        $this->mapeador = new Mapeador();
        $this->mapeador->crearArchivoMapSiNoExiste();
    }

    /**
     * @return Factory|View
     */
    public function index() {
//        $lista_roles = Role::all();
        $jsonMap = $this->obtenerJsonMapTablas();
        $jsonMapAutenticacion = $this->obtenerJsonMapAutenticacion();
        return view('tesgen::home')
            ->with('autenticacion_json_string', json_encode($jsonMapAutenticacion))
            ->with('tablas_json_string', json_encode($jsonMap))
//            ->with('lista_roles', json_encode($lista_roles))
            ->with('nombre_bd', json_encode(DB::getDatabaseName()));
    }

//    public function crud() {
//        $jsonMap = $this->obtenerJsonMapTablas();
//        return view('tesgen::crud')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
////        return view('tesgen::nuevo.crud2')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
//    }
//
//    public function md() {
//        $jsonMap = $this->obtenerJsonMapTablas();
//        return view('tesgen::md')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
////        return view('tesgen::nuevo.md2')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
//    }


    public function crud2() {
        $jsonMap = $this->obtenerJsonMapTablas();
//        return view('tesgen::crud')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
        return view('tesgen::crud')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
    }

    public function md2() {
        $jsonMap = $this->obtenerJsonMapTablas();
//        return view('tesgen::md')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
        return view('tesgen::md')->with('array_tablas', $jsonMap)->with('tablas_json_string', json_encode($jsonMap));
    }

    public function usuarios() {
        $jsonMap = $this->obtenerJsonMapAutenticacion();
        return view('tesgen::usuarios')->with('array_autenticacion', $jsonMap)->with('autenticacion_json_string', json_encode($jsonMap));
    }

    public function form() {
        $jsonMap = $this->obtenerJsonMapTablas();
        return view('tesgen::form')->with('tablas_json_string', json_encode($jsonMap));
    }

    public function permisos() {
        $jsonMapTablas = $this->obtenerJsonMapTablas();
        $jsonMapAutenticacion = $this->obtenerJsonMapAutenticacion();

        $rutas = array();

        $names = [];

        foreach (Route::getRoutes()->getRoutes() as $key => $route) {
            $action = $route->getActionname();

            $start = "App\Http\Controllers";

            //si empieza con App\Http\Controllers y es una ruta web
            if (substr($action, 0, strlen($start)) === $start &&
                in_array('web', $route->gatherMiddleware())) {
                if (strlen($route->getName()) > 0) {

                    $actionName = str_replace("\\", "/", explode('@', $action)[0]);
                    $name = explode('.', $route->getName())[0];

                    if (!in_array($name, $names)) {

                        $names[] = $name;

                        $rutas[] = array(
                            'controlador' => $actionName,
                            'nombre' => $name
                        );
                    }
                }
            }
        }

        return view('tesgen::permisos')
            ->with('tablas_json_string', json_encode($jsonMapTablas))
            ->with('autenticacion_json_string', json_encode($jsonMapAutenticacion))
            ->with('rutas', json_encode($rutas));
    }

    public function guardarYGenerar(Request $request) {
        $this->guardar($request);
        $this->generar($request);
    }

    public function guardar(Request $request) {

        $arraysTablas = json_decode($request->getContent(), true);

        foreach ($arraysTablas as $arrayTabla) {
            $this->guardarDatosTabla($arrayTabla);
        }

    }

    public function guardarDatosTabla($arrayTabla) {

        $nombreTabla = $arrayTabla['nombreTabla'];
        $nombreClase = $arrayTabla['nombreClase'];
        $nombreNatural = $arrayTabla['nombreNatural'];
        $nombrePlural = $arrayTabla['nombrePlural'];
        $datosColumnas = $arrayTabla['columnas'];
        $tablaMaestro = $arrayTabla['tablaMaestro'];
        $tablaDetalle = $arrayTabla['tablaDetalle'];
        $tablaAuxiliar = $arrayTabla['tablaAuxiliar'];
        $esMaestro = $arrayTabla['esMaestro'];
        $esDetalle = $arrayTabla['esDetalle'];
        $generado = $arrayTabla['generado'];
        $generarApi = $arrayTabla['generarApi'];
        $jsonInputsGuardarAuxiliar = $arrayTabla['jsonInputsGuardarAuxiliar'];
        $jsonInputsActualizarAuxiliar = $arrayTabla['jsonInputsActualizarAuxiliar'];
        $formActualizarIgualQueGuardar = $arrayTabla['formActualizarIgualQueGuardar'];

        $arrayTablas = $this->obtenerJsonMapTablas();

        foreach ($arrayTablas as $key => $t) {

            if ($t["nombreTabla"] == $nombreTabla) {

                $arrayTablas[$key]['nombreClase'] = $nombreClase;
                $arrayTablas[$key]['nombreNatural'] = $nombreNatural;
                $arrayTablas[$key]['nombrePlural'] = $nombrePlural;
                $arrayTablas[$key]['generado'] = $generado;
                $arrayTablas[$key]['tablaMaestro'] = $tablaMaestro;
                $arrayTablas[$key]['tablaDetalle'] = $tablaDetalle;
                $arrayTablas[$key]['tablaAuxiliar'] = $tablaAuxiliar;
                $arrayTablas[$key]['esMaestro'] = $esMaestro;
                $arrayTablas[$key]['esDetalle'] = $esDetalle;
                $arrayTablas[$key]['generarApi'] = $generarApi;
                $arrayTablas[$key]['jsonInputsGuardarAuxiliar'] = $jsonInputsGuardarAuxiliar;
                $arrayTablas[$key]['jsonInputsActualizarAuxiliar'] = $jsonInputsActualizarAuxiliar;
                $arrayTablas[$key]['formActualizarIgualQueGuardar'] = $formActualizarIgualQueGuardar;

                foreach ($t['columnas'] as $keyC => $c) {

                    foreach ($datosColumnas as $datoColumna) {
                        if ($c['nombreColumna'] === $datoColumna['nombreColumna']) {

                            $columna = $arrayTablas[$key]['columnas'][$keyC];

                            $columna['nombreNatural'] = $datoColumna['nombreNatural'];
                            $columna['visibleEnTabla'] = $datoColumna['visibleEnTabla'];
                            $columna['referente'] = $datoColumna['referente'];
                            $columna['visibleEnReporte'] = $datoColumna['visibleEnReporte'];
                            $columna['campoGuardable'] = $datoColumna['campoGuardable'];
                            $columna['visibleEnFormularioActualizar'] = $datoColumna['visibleEnFormularioActualizar'];
                            $columna['campoActualizable'] = $datoColumna['campoActualizable'];
                            $columna['campoDeTextoGuardar'] = $datoColumna['campoDeTextoGuardar'];
                            $columna['automaticoGuardar'] = $datoColumna['automaticoGuardar'];
                            $columna['conjuntoDeValoresGuardar'] = $datoColumna['conjuntoDeValoresGuardar'];
                            $columna['formulaGuardar'] = $datoColumna['formulaGuardar'];
                            $columna['valorAutomaticoGuardar'] = $datoColumna['valorAutomaticoGuardar'];
                            $columna['valorConjuntoDeValoresGuardar'] = $datoColumna['valorConjuntoDeValoresGuardar'];
                            $columna['valorFormulaGuardar'] = $datoColumna['valorFormulaGuardar'];
                            $columna['campoDeTextoActualizar'] = $datoColumna['campoDeTextoActualizar'];
                            $columna['automaticoActualizar'] = $datoColumna['automaticoActualizar'];
                            $columna['conjuntoDeValoresActualizar'] = $datoColumna['conjuntoDeValoresActualizar'];
                            $columna['formulaActualizar'] = $datoColumna['formulaActualizar'];
                            $columna['valorAutomaticoActualizar'] = $datoColumna['valorAutomaticoActualizar'];
                            $columna['valorConjuntoDeValoresActualizar'] = $datoColumna['valorConjuntoDeValoresActualizar'];
                            $columna['valorFormulaActualizar'] = $datoColumna['valorFormulaActualizar'];
                            $columna['ordenTablaHtml'] = $datoColumna['ordenTablaHtml'];
                            $columna['jsonInputGuardar'] = $datoColumna['jsonInputGuardar'];
                            $columna['jsonInputActualizar'] = $datoColumna['jsonInputActualizar'];

                            $columna['validacionesGuardar']['requerido'] = $datoColumna['validacionesGuardar']['requerido'];
                            $columna['validacionesGuardar']['longitudMinima'] = $datoColumna['validacionesGuardar']['longitudMinima'];
                            $columna['validacionesGuardar']['valorLongitudMinima'] = $datoColumna['validacionesGuardar']['valorLongitudMinima'];
                            $columna['validacionesGuardar']['longitudMaxima'] = $datoColumna['validacionesGuardar']['longitudMaxima'];
                            $columna['validacionesGuardar']['valorLongitudMaxima'] = $datoColumna['validacionesGuardar']['valorLongitudMaxima'];
                            $columna['validacionesGuardar']['unico'] = $datoColumna['validacionesGuardar']['unico'];

                            $columna['validacionesActualizar']['requerido'] = $datoColumna['validacionesActualizar']['requerido'];
                            $columna['validacionesActualizar']['longitudMinima'] = $datoColumna['validacionesActualizar']['longitudMinima'];
                            $columna['validacionesActualizar']['valorLongitudMinima'] = $datoColumna['validacionesActualizar']['valorLongitudMinima'];
                            $columna['validacionesActualizar']['longitudMaxima'] = $datoColumna['validacionesActualizar']['longitudMaxima'];
                            $columna['validacionesActualizar']['valorLongitudMaxima'] = $datoColumna['validacionesActualizar']['valorLongitudMaxima'];
                            $columna['validacionesActualizar']['unico'] = $datoColumna['validacionesActualizar']['unico'];

                            $arrayTablas[$key]['columnas'][$keyC] = $columna;
                        }
                    }

                }

                break;
            }
        }

        //si encuentra una tabla con nombre igual al detalle de la tabla actual
        foreach ($arrayTablas as $key => $t) {

            if ($t["nombreTabla"] === $tablaDetalle) {
                $arrayTablas[$key]['tablaMaestro'] = $nombreTabla;
                $arrayTablas[$key]['esDetalle'] = true;

                //pone en null tablaMaestro y pone en falso a esDetalle a las demas tablas
                foreach ($arrayTablas as $key2 => $t2) {

                    if ($t2["nombreTabla"] === $tablaDetalle) {
                        continue;
                    }

                    if ($t2["tablaMaestro"] === $nombreTabla) {
                        $arrayTablas[$key2]['tablaMaestro'] = null;
                        $arrayTablas[$key2]['esDetalle'] = false;
                    }
                }

                break;
            }
        }

        //si encuentra una tabla con tabla maestro igual al nombre de la tabla actual
        foreach ($arrayTablas as $key => $t) {

            if ($t["tablaMaestro"] === $nombreTabla && $tablaDetalle === null) {
                $arrayTablas[$key]['tablaMaestro'] = null;
                $arrayTablas[$key]['esDetalle'] = false;
                break;
            }
        }

        $this->mapeador->guardarConfiguracionTablas($arrayTablas);
    }

    public function generar(Request $request) {

        $input = json_decode($request->getContent(), true)[0];

        $nombreTabla = $input['nombreTabla'];

        $this->generarConNombreTabla($nombreTabla, true);

        $esMaestro = $input['esMaestro'];

        if ($esMaestro) {
            $nombreTablaDetalle = $input['tablaDetalle'];
            $this->generarConNombreTabla($nombreTablaDetalle, false);
        }
    }

    public function generarConNombreTabla(string $nombreTabla, bool $generarVista) {

        $this->establecerDatosTablas();
        $this->tablaActual = $this->obtenerTablaActual($nombreTabla);

        //Generar modelo
        $generadorModelo = new GeneradorModelo();
        $generadorModelo->generar($this->tablaActual, $this->tablas);

        //Generar controlador
        $generadorControlador = new GeneradorControlador();
        $generadorControlador->generar($this->tablaActual, $this->tablas, false);

        if ($this->tablaActual->isGenerarApi()) {
            //Generar controlador
            $generadorControladorApi = new GeneradorControlador();
            $generadorControladorApi->generar($this->tablaActual, $this->tablas, true);
        }

        //Generar Create Request
        $generadorCreateRequest = new GeneradorCreateRequest();
        $generadorCreateRequest->generar($this->tablaActual);

        //Generar Update Request
        $generadorUpdateRequest = new GeneradorUpdateRequest();
        $generadorUpdateRequest->generar($this->tablaActual);

        if ($generarVista) {
            //Generar vista
            $generadorVista = new GeneradorVista();
            $generadorVista->generar($this->tablaActual, $this->tablas);

            $jsonMap = $this->mapeador->obtenerJsonMap();

            $this->generarMenuYRutas();
        }
    }

    public function obtenerJsonMapTablas() {
        $jsonMap = $this->mapeador->obtenerJsonMap();

        $tablas = $jsonMap['tablas'];

        return $tablas;
    }

    public function obtenerJsonMapAutenticacion() {
        $jsonMap = $this->mapeador->obtenerJsonMap();

        $tablas = $jsonMap['autenticacion'];

        return $tablas;
    }

    public function agregarElemento() {
        $arrayTablas = $this->obtenerJsonMapTablas();

        foreach ($arrayTablas as $key => $t) {

            $arrayTablas[$key]['formActualizarIgualQueGuardar'] = true;

//            $ordenTablaHtml = 0;

//            $arrayTablas[$key]['generarApi'] = false;

//            $validaciones = array(
//                'requerido' => false,
//                'longitudMinima' => false,
//                'valorLongitudMinima' => null,
//                'longitudMaxima' => false,
//                'valorLongitudMaxima' => null,
//                'unico' => false,
//            );

//            $i = -1;
//
//            $arrayTablas[$key]['jsonInputsGuardarAuxiliar'] = array();
//            $arrayTablas[$key]['jsonInputsActualizarAuxiliar'] = array();
//
//            $arrayCampoNoAgregables = ['created_at', 'updated_at'];
//
//            foreach ($t['columnas'] as $keyC => $c) {
//
//                $visible = !$c['autoIncrement'] && !in_array($c['nombreColumna'], $arrayCampoNoAgregables);
//
//                if ($visible) {
//                    $i++;
//                }
//
//                $jsonInput = array(
//                    'x' => $i,
//                    'y' => $i,
//                    'width' => 12,
//                    'height' => 1,
//                    'id' => $c['nombreColumna'],
//                    'visible' => $visible,
//                    'agregableAForm' => $visible,
//                    'tipo' => 'nativo'
//                );
//
////                $arrayTablas[$key]['columnas'][$keyC]['ordenTablaHtml'] = $ordenTablaHtml++;
////                $arrayTablas[$key]['columnas'][$keyC]['jsonInputGuardar'] = str_replace('"', "\\\"", json_encode($jsonInputGuardar));
//                $arrayTablas[$key]['columnas'][$keyC]['jsonInputGuardar'] = $jsonInput;
//                $arrayTablas[$key]['columnas'][$keyC]['jsonInputActualizar'] = $jsonInput;
////                $arrayTablas[$key]['columnas'][$keyC]['validacionesGuardar'] = $validaciones;
////                $arrayTablas[$key]['columnas'][$keyC]['validacionesActualizar'] = $validaciones;
////                $arrayTablas[$key]['columnas'][$keyC]['validacionesGuardar'] = [];
////                $arrayTablas[$key]['columnas'][$keyC]['validacionesActualizar'] = [];
//            }
        }

        $this->mapeador->guardarConfiguracionTablas($arrayTablas);

    }

    public function quitarElemento() {
        $arrayTablas = $this->obtenerJsonMapTablas();

        foreach ($arrayTablas as $key => $t) {
            foreach ($t['columnas'] as $keyC => $c) {
                unset($arrayTablas[$key]['columnas'][$keyC]['validacionesGuardar']['longitudMinima']);
                unset($arrayTablas[$key]['columnas'][$keyC]['validacionesGuardar']['longitudMaxima']);
                unset($arrayTablas[$key]['columnas'][$keyC]['validacionesActualizar']['longitudMinima']);
                unset($arrayTablas[$key]['columnas'][$keyC]['validacionesActualizar']['longitudMaxima']);
            }

        }

        $this->mapeador->guardarConfiguracionTablas($arrayTablas);
    }

    public function generarAutenticacion() {

        $jsonMap = $this->mapeador->obtenerJsonMap();
        $arrayAutenticacion = $jsonMap['autenticacion'];

        $autenticacion = new Auntenticacion();
        $autenticacion->setLongitudMinimaUsuario($arrayAutenticacion['longitudMinimaUsuario']);
        $autenticacion->setLongitudMaximaUsuario($arrayAutenticacion['longitudMaximaUsuario']);
        $autenticacion->setLongitudMinimaContrasena($arrayAutenticacion['longitudMinimaContrasena']);

        $auth = new GeneradorAutenticacion();
        $auth->generar($autenticacion);

        $this->generarMenuYRutas();

    }

    public function eliminarAutenticacion() {

        $auth = new GeneradorAutenticacion();
        $auth->eliminar();

        $this->generarMenuYRutas();

    }

    public function guardarAutenticacion(Request $request) {
        $input = json_decode($request->getContent(), true);

        $arrayAutenticacion = $this->obtenerJsonMapAutenticacion();

        $arrayAutenticacion['generado'] = true;
        $arrayAutenticacion['longitudMinimaUsuario'] = $input['longitudMinimaUsuario'];
        $arrayAutenticacion['longitudMaximaUsuario'] = $input['longitudMaximaUsuario'];
        $arrayAutenticacion['longitudMinimaContrasena'] = $input['longitudMinimaContrasena'];

        $this->mapeador->guardarConfiguracionAutenticacion($arrayAutenticacion);

    }

    public function guardarYGenerarAutenticacion(Request $request) {
        $this->guardarAutenticacion($request);
        $this->generarAutenticacion();
    }

    public function guardarPermisos(Request $request) {

        $arraysPermisos = json_decode($request->getContent(), true);

        $nombresTablasPermisos = [];

        foreach ($arraysPermisos as $permiso) {

            $nombresTablasPermisos[] = $permiso['tabla'];

            $p = DB::table('permissions')->where('table', '=', $permiso['tabla'])->first();
            if ($p === null) {
                DB::table('permissions')->insert(
                    [
                        'table' => $permiso['tabla'],
                        'description' => $permiso['nombreNatural'],
                        'controller' => $permiso['controlador']
                    ]
                );
            }
        }

        $permisosExistentesEnBd = DB::table('permissions')->get();

        $noExistentes = [];

        foreach ($permisosExistentesEnBd as $permisoExistente) {

            $nombreTablaExistente = $permisoExistente->table;

            if (!in_array($nombreTablaExistente, $nombresTablasPermisos)) {
                $noExistentes[] = $nombreTablaExistente;
                DB::table('permissions')->where('table', $nombreTablaExistente)->delete();
            }
        }

        return $noExistentes;
    }

    public function obtenerTablaActual($nombreTabla): Tabla {

        $tabla = new Tabla();

        foreach ($this->tablas as $t) {
            if ($t->getNombreTabla() === $nombreTabla) {
                $tabla = $t;
            }
        }
        return $tabla;
    }

    public function establecerDatosTablas(): void {

        $arrayTablas = $this->obtenerJsonMapTablas();

        $this->tablas = [];

        foreach ($arrayTablas as $t) {

            $tablaAux = new Tabla();
            $tablaAux->setNombreTabla($t['nombreTabla']);
            $tablaAux->setClavePrimaria($t['clavePrimaria']);
            $tablaAux->setNombreClase($t['nombreClase']);
            $tablaAux->setNombreNatural($t['nombreNatural']);
            $tablaAux->setNombrePlural($t['nombrePlural']);
            $tablaAux->setGenerado($t['generado']);
            $tablaAux->setTablaMaestro($t['tablaMaestro'] == null ? '' : $t['tablaMaestro']);
            $tablaAux->setTablaDetalle($t['tablaDetalle'] == null ? '' : $t['tablaDetalle']);
            $tablaAux->setTablaAuxiliar($t['tablaAuxiliar'] == null ? '' : $t['tablaAuxiliar']);
            $tablaAux->setDetalle($t['esDetalle']);
            $tablaAux->setMaestro($t['esMaestro']);
            $tablaAux->setGenerarApi($t['generarApi']);
            $tablaAux->setJsonInputsGuardarAuxiliar($t['jsonInputsGuardarAuxiliar']);
            $tablaAux->setJsonInputsActualizarAuxiliar($t['jsonInputsActualizarAuxiliar']);
            $tablaAux->setFormActualizarIgualQueGuardar($t['formActualizarIgualQueGuardar']);

            $columnas = array();
            foreach ($t['columnas'] as $c) {
                $columna = new Columna();
                $columna->setNombreColumna($c['nombreColumna']);
                $columna->setTipo($c['tipo']);
                $columna->setLongitud($c['longitud'] == null ? 0 : $c['longitud']);
                $columna->setDefault($c['default'] == null ? '' : $c['default']);
                $columna->setAutoIncrement($c['autoIncrement']);
                $columna->setNotNull($c['notNull']);
                $columna->setClaveForanea($c['claveForanea']);
                $columna->setReferente($c['referente']);
                $columna->setNombreNatural($c['nombreNatural']);
                $columna->setVisibleEnTabla($c['visibleEnTabla']);
                $columna->setVisibleEnReporte($c['visibleEnReporte']);
                $columna->setCampoGuardable($c['campoGuardable']);
                $columna->setVisibleEnFormularioActualizar($c['visibleEnFormularioActualizar']);
                $columna->setCampoActualizable($c['campoActualizable']);
                $columna->setCampoDeTextoGuardar($c['campoDeTextoGuardar']);
                $columna->setAutomaticoGuardar($c['automaticoGuardar']);
                $columna->setConjuntoDeValoresGuardar($c['conjuntoDeValoresGuardar']);
                $columna->setFormulaGuardar($c['formulaGuardar']);
                $columna->setValorAutomaticoGuardar($c['valorAutomaticoGuardar']);
                $columna->setValorConjuntoDeValoresGuardar($c['valorConjuntoDeValoresGuardar']);
                $columna->setValorFormulaGuardar($c['valorFormulaGuardar']);
                $columna->setCampoDeTextoActualizar($c['campoDeTextoActualizar']);
                $columna->setAutomaticoActualizar($c['automaticoActualizar']);
                $columna->setConjuntoDeValoresActualizar($c['conjuntoDeValoresActualizar']);
                $columna->setFormulaActualizar($c['formulaActualizar']);
                $columna->setValorAutomaticoActualizar($c['valorAutomaticoActualizar']);
                $columna->setValorConjuntoDeValoresActualizar($c['valorConjuntoDeValoresActualizar']);
                $columna->setValorFormulaActualizar($c['valorFormulaActualizar']);
                $columna->setOrdenTablaHtml($c['ordenTablaHtml']);
                $columna->setJsonInputGuardar($c['jsonInputGuardar']);
                $columna->setJsonInputActualizar($c['jsonInputActualizar']);

                $validacionGuardar = new Validacion();
                $validacionGuardar->setRequerido($c['validacionesGuardar']['requerido']);
                $validacionGuardar->setLongitudMinima($c['validacionesGuardar']['longitudMinima']);
                $validacionGuardar->setValorLongitudMinima($c['validacionesGuardar']['valorLongitudMinima'] === null ? 0 : $c['validacionesGuardar']['valorLongitudMinima']);
                $validacionGuardar->setLongitudMaxima($c['validacionesGuardar']['longitudMaxima']);
                $validacionGuardar->setValorLongitudMaxima($c['validacionesGuardar']['valorLongitudMaxima'] === null ? 0 : $c['validacionesGuardar']['valorLongitudMaxima']);
                $validacionGuardar->setUnico($c['validacionesGuardar']['unico']);

                $validacionActualizar = new Validacion();
                $validacionActualizar->setRequerido($c['validacionesActualizar']['requerido']);
                $validacionActualizar->setLongitudMinima($c['validacionesActualizar']['longitudMinima']);
                $validacionActualizar->setValorLongitudMinima($c['validacionesActualizar']['valorLongitudMinima'] === null ? 0 : $c['validacionesActualizar']['valorLongitudMinima']);
                $validacionActualizar->setLongitudMaxima($c['validacionesActualizar']['longitudMaxima']);
                $validacionActualizar->setValorLongitudMaxima($c['validacionesActualizar']['valorLongitudMaxima'] === null ? 0 : $c['validacionesActualizar']['valorLongitudMaxima']);
                $validacionActualizar->setUnico($c['validacionesActualizar']['unico']);

                $columna->setValidacionGuardar($validacionGuardar);
                $columna->setValidacionActualizar($validacionActualizar);

                $columnas[] = $columna;
            }

            $relaciones = array();
            foreach ($t['clavesForaneas'] as $r) {
                $relacion = new Relacion();
                $relacion->setNombreClaveForanea($r['nombreClaveForanea']);
                $relacion->setNombreIdTablaRelacion($r['nombreIdTablaRelacion']);
                $relacion->setNombreTablaRelacion($r['tabla']);
                $relaciones[] = $relacion;
            }

            $tablaAux->setColumnas($columnas);
            $tablaAux->setRelaciones($relaciones);
            $this->tablas[] = $tablaAux;
        }
    }

    /**
     * @return Tabla[]
     */
    public function getTablas(): array {
        return $this->tablas;
    }

    public function eliminarArchivosGenerados() {

        $directorioModelo = app_path();
        $directorioControlador = app_path() . '\\Http\\Controllers';
        $directorioControladorApi = app_path() . '\\Http\\Controllers\\API';
        $directorioRequest = app_path() . '\\Http\\Requests';
        $directorioVista = resource_path() . '\\views';

        $mapeador = new Mapeador();
        $arrayTablas = $this->obtenerJsonMapTablas();

        foreach ($arrayTablas as $t) {

            if ($t['generado'] === false) {
                continue;
            }

            $nombreTabla = $t['nombreTabla'];
            $nombreClase = $t['nombreClase'];

            $modelo = $nombreClase . '.php';
            $controlador = $nombreClase . 'Controller.php';
            $createRequest = $nombreClase . 'CreateRequest.php';
            $updateRequest = $nombreClase . 'UpdateRequest.php';

            ArchivoUtil::deleteFile($directorioModelo, $modelo);
            ArchivoUtil::deleteFile($directorioControlador, $controlador);
            ArchivoUtil::deleteFile($directorioControladorApi, $controlador);
            ArchivoUtil::deleteFile($directorioRequest, $createRequest);
            ArchivoUtil::deleteFile($directorioRequest, $updateRequest);

            ArchivoUtil::deleteFolderWithFiles($directorioVista . '\\' . $nombreTabla);

            $t['generado'] = false;
            $t['generarApi'] = false;

            $this->guardarDatosTabla($t);
        }

        $generadorAuntenticacion = new GeneradorAutenticacion();
        $generadorAuntenticacion->eliminar();

        $autenticacion = $this->obtenerJsonMapAutenticacion();

        $this->generarMenuYRutas();

    }

    public function generarMenuYRutas() {

        $autenticacion = $this->obtenerJsonMapAutenticacion();

        $this->establecerDatosTablas();

        //Generar items menu
        $generadorMenu = new GeneradorMenu();
        $generadorMenu->generar($this->tablas, $autenticacion);

        //Generar rutas
        $generadorRutas = new GeneradorRutas();
        $generadorRutas->generar($this->tablas, $autenticacion);
    }

    public function generarFormulario(Request $request) {

        $arrayBloques = json_decode($request->getContent(), true);

        $directorioAGenerar = resource_path() . '\\views';
        $archivoAGenerar = 'formulario.html';

        $divs = '';

        $i = 0;

        while (count($arrayBloques) - $i > 0) {

            $divs .= '        <div class="form-row">' . "\n";

            $bloque = $arrayBloques[$i];

            $y = $bloque['y'];

            while ($y === $bloque['y']) {

                $divs .= '            <div class="form-group col-md-' . $bloque['width'] . '">' . "\n";
                $divs .= '                <label for="input_' . $bloque['id'] . '">Input ' . $bloque['id'] . '</label>' . "\n";
                $divs .= '                <input type="text" id="input_' . $bloque['id'] . '" class="form-control">' . "\n";
                $divs .= '            </div>' . "\n";

                if (count($arrayBloques) - ($i += 1) > 0) {
                    $bloque = $arrayBloques[$i];
                } else {
                    break;
                }

            }

            $divs .= '        </div>' . "\n";

        }

        $directorio_pantilla = base_path('vendor\\tesgen\\generador\\plantillas\\form.txt');

        $contenidoPlantilla = file_get_contents($directorio_pantilla);
        $contenidoPlantilla = str_replace('$INPUTS$', $divs, $contenidoPlantilla);

        ArchivoUtil::createFile($directorioAGenerar, $archivoAGenerar, $contenidoPlantilla);

    }

}
