<?php

namespace TesGen\Generador\Modelo;

class Columna {

    /**
     * @var string
     */
    private $nombreColumna;

    /**
     * @var string
     */
    private $tipo;

    /**
     * @var int
     */
    private $longitud;

    /**
     * @var string
     */
    private $default;

    /**
     * @var bool
     */
    private $autoIncrement;

    /**
     * @var bool
     */
    private $notNull;

    /**
     * @var bool
     */
    private $claveForanea;

    /**
     * @var bool
     */
    private $referente;

    /**
     * @var string
     */
    private $nombreNatural;

    /**
     * @var bool
     */
    private $visibleEnTabla;

    /**
     * @var bool
     */
    private $visibleEnReporte;

    /**
     * @var bool
     */
    private $campoGuardable;

    /**
     * @var bool
     */
    private $visibleEnFormularioActualizar;

    /**
     * @var bool
     */
    private $campoActualizable;
    /**
     * @var bool
     */
    private $campoDeTextoGuardar;

    /**
     * @var bool
     */
    private $automaticoGuardar;

    /**
     * @var bool
     */
    private $conjuntoDeValoresGuardar;

    /**
     * @var bool
     */
    private $formulaGuardar;

    /**
     * @var string
     */
    private $valorAutomaticoGuardar;

    /**
     * @var string
     */
    private $valorConjuntoDeValoresGuardar;

    /**
     * @var string
     */
    private $valorFormulaGuardar;

    /**
     * @var bool
     */
    private $autoincrementalGuardar;

    /**
     * @var bool
     */
    private $campoDeTextoActualizar;

    /**
     * @var bool
     */
    private $automaticoActualizar;

    /**
     * @var bool
     */
    private $conjuntoDeValoresActualizar;

    /**
     * @var bool
     */
    private $formulaActualizar;

    /**
     * @var string
     */
    private $valorAutomaticoActualizar;

    /**
     * @var string
     */
    private $valorConjuntoDeValoresActualizar;

    /**
     * @var string
     */
    private $valorFormulaActualizar;

//    /**
//     * @var bool
//     */
//    private $requerido;

    /**
     * @var Validacion
     */
    private $validacionGuardar;

    /**
     * @var Validacion
     */
    private $validacionActualizar;

    /**
     * @var int
     */
    private $ordenTablaHtml;

    /**
     * @var array
     */
    private $jsonInputGuardar;

    /**
     * @var array
     */
    private $jsonInputActualizar;

    /**
     * @return string
     */
    public function getNombreColumna(): string {
        return $this->nombreColumna;
    }

    /**
     * @param string $nombreColumna
     */
    public function setNombreColumna(string $nombreColumna): void {
        $this->nombreColumna = $nombreColumna;
    }

    /**
     * @return string
     */
    public function getTipo(): string {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo(string $tipo): void {
        $this->tipo = $tipo;
    }

    /**
     * @return int
     */
    public function getLongitud(): int {
        return $this->longitud;
    }

    /**
     * @param int $longitud
     */
    public function setLongitud(int $longitud): void {
        $this->longitud = $longitud;
    }

    /**
     * @return string
     */
    public function getDefault(): string {
        return $this->default;
    }

    /**
     * @param string $default
     */
    public function setDefault(string $default): void {
        $this->default = $default;
    }

    /**
     * @return bool
     */
    public function isAutoIncrement(): bool {
        return $this->autoIncrement;
    }

    /**
     * @param bool $autoIncrement
     */
    public function setAutoIncrement(bool $autoIncrement): void {
        $this->autoIncrement = $autoIncrement;
    }

    /**
     * @return bool
     */
    public function isNotNull(): bool {
        return $this->notNull;
    }

    /**
     * @param bool $notNull
     */
    public function setNotNull(bool $notNull): void {
        $this->notNull = $notNull;
    }

    /**
     * @return bool
     */
    public function isClaveForanea(): bool {
        return $this->claveForanea;
    }

    /**
     * @param bool $esClaveForanea
     */
    public function setClaveForanea(bool $esClaveForanea): void {
        $this->claveForanea = $esClaveForanea;
    }

    /**
     * @return bool
     */
    public function isReferente(): bool {
        return $this->referente;
    }

    /**
     * @param bool $referente
     */
    public function setReferente(bool $referente): void {
        $this->referente = $referente;
    }

    /**
     * @return string
     */
    public function getNombreNatural(): string {
        return $this->nombreNatural;
    }

    /**
     * @param string $nombreNatural
     */
    public function setNombreNatural(string $nombreNatural): void {
        $this->nombreNatural = $nombreNatural;
    }

    /**
     * @return bool
     */
    public function isVisibleEnTabla(): bool {
        return $this->visibleEnTabla;
    }

    /**
     * @param bool $visibleEnTabla
     */
    public function setVisibleEnTabla(bool $visibleEnTabla): void {
        $this->visibleEnTabla = $visibleEnTabla;
    }

    /**
     * @return bool
     */
    public function isVisibleEnReporte(): bool {
        return $this->visibleEnReporte;
    }

    /**
     * @param bool $visibleEnReporte
     */
    public function setVisibleEnReporte(bool $visibleEnReporte): void {
        $this->visibleEnReporte = $visibleEnReporte;
    }

    /**
     * @return bool
     */
    public function isCampoGuardable(): bool {
        return $this->campoGuardable;
    }

    /**
     * @param bool $campoGuardable
     */
    public function setCampoGuardable(bool $campoGuardable): void {
        $this->campoGuardable = $campoGuardable;
    }

    /**
     * @return bool
     */
    public function isVisibleEnFormularioActualizar(): bool {
        return $this->visibleEnFormularioActualizar;
    }

    /**
     * @param bool $visibleEnFormularioActualizar
     */
    public function setVisibleEnFormularioActualizar(bool $visibleEnFormularioActualizar): void {
        $this->visibleEnFormularioActualizar = $visibleEnFormularioActualizar;
    }

    /**
     * @return bool
     */
    public function isCampoActualizable(): bool {
        return $this->campoActualizable;
    }

    /**
     * @param bool $campoActualizable
     */
    public function setCampoActualizable(bool $campoActualizable): void {
        $this->campoActualizable = $campoActualizable;
    }

    /**
     * @return bool
     */
    public function isCampoDeTextoGuardar(): bool {
        return $this->campoDeTextoGuardar;
    }

    /**
     * @param bool $campoDeTextoGuardar
     */
    public function setCampoDeTextoGuardar(bool $campoDeTextoGuardar): void {
        $this->campoDeTextoGuardar = $campoDeTextoGuardar;
    }

    /**
     * @return bool
     */
    public function isAutomaticoGuardar(): bool {
        return $this->automaticoGuardar;
    }

    /**
     * @param bool $automaticoGuardar
     */
    public function setAutomaticoGuardar(bool $automaticoGuardar): void {
        $this->automaticoGuardar = $automaticoGuardar;
    }

    /**
     * @return bool
     */
    public function isConjuntoDeValoresGuardar(): bool {
        return $this->conjuntoDeValoresGuardar;
    }

    /**
     * @param bool $conjuntoDeValoresGuardar
     */
    public function setConjuntoDeValoresGuardar(bool $conjuntoDeValoresGuardar): void {
        $this->conjuntoDeValoresGuardar = $conjuntoDeValoresGuardar;
    }

    /**
     * @return bool
     */
    public function isFormulaGuardar(): bool {
        return $this->formulaGuardar;
    }

    /**
     * @param bool $formulaGuardar
     */
    public function setFormulaGuardar(bool $formulaGuardar): void {
        $this->formulaGuardar = $formulaGuardar;
    }

    /**
     * @return string
     */
    public function getValorAutomaticoGuardar(): string {
        return $this->valorAutomaticoGuardar;
    }

    /**
     * @param string $valorAutomaticoGuardar
     */
    public function setValorAutomaticoGuardar(string $valorAutomaticoGuardar): void {
        $this->valorAutomaticoGuardar = $valorAutomaticoGuardar;
    }

    /**
     * @return string
     */
    public function getValorConjuntoDeValoresGuardar(): string {
        return $this->valorConjuntoDeValoresGuardar;
    }

    /**
     * @param string $valorConjuntoDeValoresGuardar
     */
    public function setValorConjuntoDeValoresGuardar(string $valorConjuntoDeValoresGuardar): void {
        $this->valorConjuntoDeValoresGuardar = $valorConjuntoDeValoresGuardar;
    }

    /**
     * @return string
     */
    public function getValorFormulaGuardar(): string {
        return $this->valorFormulaGuardar;
    }

    /**
     * @param string $valorFormulaGuardar
     */
    public function setValorFormulaGuardar(string $valorFormulaGuardar): void {
        $this->valorFormulaGuardar = $valorFormulaGuardar;
    }

    /**
     * @return bool
     */
    public function isAutoincrementalGuardar(): bool {
        return $this->autoincrementalGuardar;
    }

    /**
     * @param bool $autoincrementalGuardar
     */
    public function setAutoincrementalGuardar(bool $autoincrementalGuardar): void {
        $this->autoincrementalGuardar = $autoincrementalGuardar;
    }

    /**
     * @return bool
     */
    public function isCampoDeTextoActualizar(): bool {
        return $this->campoDeTextoActualizar;
    }

    /**
     * @param bool $campoDeTextoActualizar
     */
    public function setCampoDeTextoActualizar(bool $campoDeTextoActualizar): void {
        $this->campoDeTextoActualizar = $campoDeTextoActualizar;
    }

    /**
     * @return bool
     */
    public function isAutomaticoActualizar(): bool {
        return $this->automaticoActualizar;
    }

    /**
     * @param bool $automaticoActualizar
     */
    public function setAutomaticoActualizar(bool $automaticoActualizar): void {
        $this->automaticoActualizar = $automaticoActualizar;
    }

    /**
     * @return bool
     */
    public function isConjuntoDeValoresActualizar(): bool {
        return $this->conjuntoDeValoresActualizar;
    }

    /**
     * @param bool $conjuntoDeValoresActualizar
     */
    public function setConjuntoDeValoresActualizar(bool $conjuntoDeValoresActualizar): void {
        $this->conjuntoDeValoresActualizar = $conjuntoDeValoresActualizar;
    }

    /**
     * @return bool
     */
    public function isFormulaActualizar(): bool {
        return $this->formulaActualizar;
    }

    /**
     * @param bool $formulaActualizar
     */
    public function setFormulaActualizar(bool $formulaActualizar): void {
        $this->formulaActualizar = $formulaActualizar;
    }

    /**
     * @return string
     */
    public function getValorAutomaticoActualizar(): string {
        return $this->valorAutomaticoActualizar;
    }

    /**
     * @param string $valorAutomaticoActualizar
     */
    public function setValorAutomaticoActualizar(string $valorAutomaticoActualizar): void {
        $this->valorAutomaticoActualizar = $valorAutomaticoActualizar;
    }

    /**
     * @return string
     */
    public function getValorConjuntoDeValoresActualizar(): string {
        return $this->valorConjuntoDeValoresActualizar;
    }

    /**
     * @param string $valorConjuntoDeValoresActualizar
     */
    public function setValorConjuntoDeValoresActualizar(string $valorConjuntoDeValoresActualizar): void {
        $this->valorConjuntoDeValoresActualizar = $valorConjuntoDeValoresActualizar;
    }

    /**
     * @return string
     */
    public function getValorFormulaActualizar(): string {
        return $this->valorFormulaActualizar;
    }

    /**
     * @param string $valorFormulaActualizar
     */
    public function setValorFormulaActualizar(string $valorFormulaActualizar): void {
        $this->valorFormulaActualizar = $valorFormulaActualizar;
    }

//    /**
//     * @return bool
//     */
//    public function isRequerido(): bool {
//        return $this->requerido;
//    }
//
//    /**
//     * @param bool $requerido
//     */
//    public function setRequerido(bool $requerido): void {
//        $this->requerido = $requerido;
//    }

    /**
     * @return Validacion
     */
    public function getValidacionGuardar(): Validacion {
        return $this->validacionGuardar;
    }

    /**
     * @param Validacion $validacionGuardar
     */
    public function setValidacionGuardar(Validacion $validacionGuardar): void {
        $this->validacionGuardar = $validacionGuardar;
    }

    /**
     * @return Validacion
     */
    public function getValidacionActualizar(): Validacion {
        return $this->validacionActualizar;
    }

    /**
     * @param Validacion $validacionActualizar
     */
    public function setValidacionActualizar(Validacion $validacionActualizar): void {
        $this->validacionActualizar = $validacionActualizar;
    }

    /**
     * @return int
     */
    public function getOrdenTablaHtml(): int {
        return $this->ordenTablaHtml;
    }

    /**
     * @param int $ordenTablaHtml
     */
    public function setOrdenTablaHtml(int $ordenTablaHtml): void {
        $this->ordenTablaHtml = $ordenTablaHtml;
    }

    /**
     * @return array
     */
    public function getJsonInputGuardar(): array {
        return $this->jsonInputGuardar;
    }

    /**
     * @param array $jsonInputGuardar
     */
    public function setJsonInputGuardar(array $jsonInputGuardar): void {
        $this->jsonInputGuardar = $jsonInputGuardar;
    }

    /**
     * @return array
     */
    public function getJsonInputActualizar(): array {
        return $this->jsonInputActualizar;
    }

    /**
     * @param array $jsonInputActualizar
     */
    public function setJsonInputActualizar(array $jsonInputActualizar): void {
        $this->jsonInputActualizar = $jsonInputActualizar;
    }

}
