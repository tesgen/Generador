<?php

namespace TesGen\Generador\Utils;

use DB;
use Doctrine\DBAL\Schema\Table;
use TesGen\Generador\Modelo\Columna;
use TesGen\Generador\Modelo\Tabla;

class Mapeador {

    /**
     * Nombre de la base de datos
     *
     * @var string
     */
    protected $nombreArchivo;

    /**
     * @var string
     */
    protected $directorioCarpetaMap;

    /**
     * @var
     */
    protected $directorioArchivoMap;

    /**
     * @var string
     */
    private $archivoJson;

    private $tablasNoMapeables = ["migrations", "oauth_access_tokens", "oauth_auth_codes", "oauth_clients",
        "oauth_personal_access_clients", "oauth_refresh_tokens", "permission_role", "permissions", "roles",
        "users"
    ];

    /**
     * Mapeador constructor.
     */
    public function __construct() {

        $this->nombreArchivo = DB::getDatabaseName();
        $directorioParcialMap = 'resources/map/';
        $this->directorioCarpetaMap = base_path($directorioParcialMap);
        $this->directorioArchivoMap = base_path($directorioParcialMap . $this->nombreArchivo . '.json');

        $this->archivoJson = $this->directorioCarpetaMap . $this->nombreArchivo . '.json';

    }

    /**
     * Obtener la lista de tablas de la base de datos.
     *
     * @return Table[]
     */
    public static function getTablas() {
        $tables = DB::getDoctrineSchemaManager()->listTables();
        return $tables;
    }

    /**
     * Crea el archivo que contiene el archivo de mapeado json de la base de datos
     *
     * @return void
     */
    public function crearArchivoMapSiNoExiste(): void {

        ArchivoUtil::crearDirectorioSiNoExiste($this->directorioCarpetaMap);

        if (file_exists($this->archivoJson)) {
            return;
        }

        $listaTablas = self::getTablas();

        $arrayTablas = array();

        $i = 0;

        foreach ($listaTablas as $table) {

            $arrayColumnas = array();
            $arrayClavesForaneas = array();

            if (in_array($table->getName(), $this->tablasNoMapeables)) {
                continue;
            }

            $ordenTablaHtml = 0;

            $i = -1;

            $arrayCampoNoAgregables = ['created_at', 'updated_at'];

            foreach ($table->getColumns() as $column) {

                $visible = !$column->getAutoincrement() && !in_array($column->getName(), $arrayCampoNoAgregables);

                if ($visible) {
                    $i++;
                }

                $jsonInput = array(
                    'x' => $i,
                    'y' => $i,
                    'width' => 12,
                    'height' => 1,
                    'id' => $column->getName(),
                    'visible' => $visible,
                    'agregableAForm' => $visible,
                    'tipo' => 'nativo',
                );

                $arrayColumnas[] = array(
                    'nombreColumna' => $column->getName(),
                    'tipo' => $column->getType()->getName(),
                    'longitud' => $column->getLength(),
                    'default' => $column->getDefault(),
                    'autoIncrement' => $column->getAutoincrement(),
                    'notNull' => $column->getNotnull(),
                    'claveForanea' => false,
                    'referente' => false,
                    'nombreNatural' => '',
                    'visibleEnTabla' => true,
                    'visibleEnReporte' => true,
                    'campoGuardable' => true,
                    'visibleEnFormularioActualizar' => true,
                    'campoActualizable' => true,
                    'campoDeTextoGuardar' => true,
                    'automaticoGuardar' => false,
                    'conjuntoDeValoresGuardar' => false,
                    'formulaGuardar' => false,
                    'valorAutomaticoGuardar' => '',
                    'valorConjuntoDeValoresGuardar' => '',
                    'valorFormulaGuardar' => '',
                    'campoDeTextoActualizar' => true,
                    'automaticoActualizar' => false,
                    'conjuntoDeValoresActualizar' => false,
                    'formulaActualizar' => false,
                    'valorAutomaticoActualizar' => '',
                    'valorConjuntoDeValoresActualizar' => '',
                    'valorFormulaActualizar' => '',
                    'ordenTablaHtml' => $ordenTablaHtml++,
                    'jsonInputGuardar' => $jsonInput,
                    'jsonInputActualizar' => $jsonInput,
                    'formActualizarIgualQueGuardar' => true,

                    'validacionesGuardar' => array(
                        'requerido' => false,
                        'longitudMinima' => false,
                        'valorLongitudMinima' => null,
                        'longitudMaxima' => false,
                        'valorLongitudMaxima' => null,
                        'unico' => false,
                    ),
                    'validacionesActualizar' => array(
                        'requerido' => false,
                        'longitudMinima' => false,
                        'valorLongitudMinima' => null,
                        'longitudMaxima' => false,
                        'valorLongitudMaxima' => null,
                        'unico' => false,
                    )
                );
            }

            foreach ($table->getForeignKeys() as $claveForanea) {
                $arrayClavesForaneas[] = array(
                    'nombreClaveForanea' => $claveForanea->getColumns()[0],
                    'nombreIdTablaRelacion' => $claveForanea->getForeignColumns()[0],
                    'tabla' => $claveForanea->getForeignTableName()
                );
            }

            $arrayTablas[] = array(
                'nombreTabla' => $table->getName(),
                'clavePrimaria' => $table->getPrimaryKey() === null ? null : $table->getPrimaryKey()->getColumns()[0],
                'columnas' => $arrayColumnas,
                'clavesForaneas' => $arrayClavesForaneas,
                'nombreClase' => '',
                'nombreNatural' => '',
                'nombrePlural' => '',
                'generado' => false,
                'esMaestro' => false,
                'tablaMaestro' => null,
                'tablaDetalle' => null,
                'tablaAuxiliar' => null,
                'esDetalle' => false,
                'generarApi' => false,
                'jsonInputsGuardarAuxiliar' => [],
                'jsonInputsActualizarAuxiliar' => [],
            );

        }

        foreach ($arrayTablas as $keyT => $t) {

            foreach ($t['columnas'] as $keyC => $c) {

                if ($t['clavePrimaria'] === $c['nombreColumna']) {
                    continue;
                }

                foreach ($t['clavesForaneas'] as $r) {
                    if ($r['nombreClaveForanea'] === $c['nombreColumna']) {
                        $arrayTablas[$keyT]['columnas'][$keyC]['claveForanea'] = true;
                        break;
                    }
                }

            }
        }

        $contenidoJson = array(
            'autenticacion' => array(
                'generado' => false,
                'longitudMinimaUsuario' => 5,
                'longitudMaximaUsuario' => 255,
                'longitudMinimaContrasena' => 5,
            ),
            'tablas' => $arrayTablas
        );

        $this->guardarConfiguracion($contenidoJson);

    }

    private function guardarConfiguracion($contenidoJson) {
        $jsonString = json_encode($contenidoJson, JSON_PRETTY_PRINT);
        file_put_contents($this->archivoJson, stripslashes($jsonString));
    }

    public function guardarConfiguracionTablas($arrayTablas) {

        $jsonMap = $this->obtenerJsonMap();
        //establecer solo la configuracion de las tablas
        $jsonMap['tablas'] = $arrayTablas;

        //guardar la configuracion
        $this->guardarConfiguracion($jsonMap);

    }

    public function guardarConfiguracionAutenticacion($arrayAutenticacion) {

        $jsonMap = $this->obtenerJsonMap();
        $jsonMap['autenticacion'] = $arrayAutenticacion;

        //guardar la configuracion
        $this->guardarConfiguracion($jsonMap);
    }

    public function obtenerJsonMap() {
        return json_decode(file_get_contents($this->archivoJson), true);
    }

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     * @return Tabla
     */
    public static function getTablaDetalle(Tabla $tabla, $tablas) {

        $nombreDetalle = $tabla->getTablaDetalle();

        foreach ($tablas as $t) {

            if ($nombreDetalle === $t->getNombreTabla()) {
                return $t;
            }
        }

        return null;

    }

    /**
     * @param Tabla $tabla
     * @param Columna $columna
     * @return mixed|\TesGen\Generador\Modelo\Relacion|null
     */
    public static function getTablaRelacion(Tabla $tabla, Columna $columna) {

        foreach ($tabla->getRelaciones() as $relacion) {
            if ($columna->getNombreColumna() === $relacion->getNombreClaveForanea()) {
                return $relacion;
            }
        }
        return null;
    }

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     */
    public static function getTablaMaestro(Tabla $tabla, $tablas) {

        $nombreTablaMaestro = $tabla->getTablaMaestro();

        foreach ($tablas as $t) {

            if ($nombreTablaMaestro === $t->getNombreTabla()) {
                return $t;
            }
        }

        return null;
    }

    /**
     * @param Tabla $tabla
     * @param Tabla[] $tablas
     */
    public static function getTablaAuxiliar(Tabla $tabla, $tablas) {

        $nombreTablaAuxiliar = $tabla->getTablaAuxiliar();

        foreach ($tablas as $t) {

            if ($nombreTablaAuxiliar === $t->getNombreTabla()) {
                return $t;
            }
        }

        return null;
    }

}

