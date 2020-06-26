<?php

namespace TesGen\Generador\Modelo;

class Tabla {

    /**
     * @var string
     */
    private $nombreTabla;

    /**
     * @var string
     */
    private $clavePrimaria;

    /**
     * @var Columna[]
     */
    private $columnas = [];

    /**
     * @var Relacion[]
     */
    private $relaciones = [];

    /**
     * @var string
     */
    private $nombreClase;

    /**
     * @var string
     */
    private $nombreNatural;

    /**
     * @var string
     */
    private $nombrePlural;

    /**
     * @var bool
     */
    private $generado;

    /**
     * @var bool
     */
    private $maestro;

    /**
     * @var string
     */
    private $tablaMaestro;

    /**
     * @var string
     */
    private $tablaDetalle;

    /**
     * @var string
     */
    private $tablaAuxiliar;

    /**
     * @var bool
     */
    private $detalle;

    /**
     * @var bool
     */
    private $generarApi;

    /**
     * @var array
     */
    private $jsonInputsGuardarAuxiliar;

    /**
     * @var array
     */
    private $jsonInputsActualizarAuxiliar;

    /**
     * @var boolean
     */
    private $formActualizarIgualQueGuardar;

    /**
     * @return string
     */
    public function getNombreTabla(): string {
        return $this->nombreTabla;
    }

    /**
     * @param string $nombreTabla
     */
    public function setNombreTabla(string $nombreTabla): void {
        $this->nombreTabla = $nombreTabla;
    }

    /**
     * @return string
     */
    public function getClavePrimaria(): string {
        return $this->clavePrimaria;
    }

    /**
     * @param string $clavePrimaria
     */
    public function setClavePrimaria(string $clavePrimaria): void {
        $this->clavePrimaria = $clavePrimaria;
    }

    /**
     * @return Columna[]
     */
    public function getColumnas(): array {
        return $this->columnas;
    }

    /**
     * @param Columna[] $columnas
     */
    public function setColumnas(array $columnas): void {
        $this->columnas = $columnas;
    }

    /**
     * @return Relacion[]
     */
    public function getRelaciones(): array {
        return $this->relaciones;
    }

    /**
     * @param Relacion[] $relaciones
     */
    public function setRelaciones(array $relaciones): void {
        $this->relaciones = $relaciones;
    }

    /**
     * @return string
     */
    public function getNombreClase(): string {
        return $this->nombreClase;
    }

    /**
     * @param string $nombreClase
     */
    public function setNombreClase(string $nombreClase): void {
        $this->nombreClase = $nombreClase;
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
     * @return string
     */
    public function getNombrePlural(): string {
        return $this->nombrePlural;
    }

    /**
     * @param string $nombrePlural
     */
    public function setNombrePlural(string $nombrePlural): void {
        $this->nombrePlural = $nombrePlural;
    }

    /**
     * @return bool
     */
    public function isGenerado(): bool {
        return $this->generado;
    }

    /**
     * @param bool $generado
     */
    public function setGenerado(bool $generado): void {
        $this->generado = $generado;
    }

    /**
     * @return bool
     */
    public function isMaestro(): bool {
        return $this->maestro;
    }

    /**
     * @param bool $maestro
     */
    public function setMaestro(bool $maestro): void {
        $this->maestro = $maestro;
    }

    /**
     * @return string
     */
    public function getTablaMaestro(): string {
        return $this->tablaMaestro;
    }

    /**
     * @param string $tablaMaestro
     */
    public function setTablaMaestro(string $tablaMaestro): void {
        $this->tablaMaestro = $tablaMaestro;
    }

    /**
     * @return string
     */
    public function getTablaDetalle(): string {
        return $this->tablaDetalle;
    }

    /**
     * @param string $tablaDetalle
     */
    public function setTablaDetalle(string $tablaDetalle): void {
        $this->tablaDetalle = $tablaDetalle;
    }

    /**
     * @return string
     */
    public function getTablaAuxiliar(): string {
        return $this->tablaAuxiliar;
    }

    /**
     * @param string $tablaAuxiliar
     */
    public function setTablaAuxiliar(string $tablaAuxiliar): void {
        $this->tablaAuxiliar = $tablaAuxiliar;
    }

    /**
     * @return bool
     */
    public function isDetalle(): bool {
        return $this->detalle;
    }

    /**
     * @param bool $detalle
     */
    public function setDetalle(bool $detalle): void {
        $this->detalle = $detalle;
    }

    /**
     * @return bool
     */
    public function isGenerarApi(): bool {
        return $this->generarApi;
    }

    /**
     * @param bool $generarApi
     */
    public function setGenerarApi(bool $generarApi): void {
        $this->generarApi = $generarApi;
    }

    /**
     * @return array
     */
    public function getJsonInputsGuardarAuxiliar(): array {
        return $this->jsonInputsGuardarAuxiliar;
    }

    /**
     * @param array $jsonInputsGuardarAuxiliar
     */
    public function setJsonInputsGuardarAuxiliar(array $jsonInputsGuardarAuxiliar): void {
        $this->jsonInputsGuardarAuxiliar = $jsonInputsGuardarAuxiliar;
    }

    /**
     * @return array
     */
    public function getJsonInputsActualizarAuxiliar(): array {
        return $this->jsonInputsActualizarAuxiliar;
    }

    /**
     * @param array $jsonInputsActualizarAuxiliar
     */
    public function setJsonInputsActualizarAuxiliar(array $jsonInputsActualizarAuxiliar): void {
        $this->jsonInputsActualizarAuxiliar = $jsonInputsActualizarAuxiliar;
    }

    /**
     * @return bool
     */
    public function isFormActualizarIgualQueGuardar(): bool {
        return $this->formActualizarIgualQueGuardar;
    }

    /**
     * @param bool $formActualizarIgualQueGuardar
     */
    public function setFormActualizarIgualQueGuardar(bool $formActualizarIgualQueGuardar): void {
        $this->formActualizarIgualQueGuardar = $formActualizarIgualQueGuardar;
    }

}
