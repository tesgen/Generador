<?php

namespace TesGen\Generador\Modelo;

class Relacion {

    /**
     * @var string
     */
    private $nombreClaveForanea;

    /**
     * @var string
     */
    private $nombreIdTablaRelacion;

    /**
     * @var Tabla[]
     */
    private $tablasRelaciones;

    /**
     * @var string
     */
    private $nombreTablaRelacion;

    /**
     * @return string
     */
    public function getNombreClaveForanea(): string {
        return $this->nombreClaveForanea;
    }

    /**
     * @param string $nombreClaveForanea
     */
    public function setNombreClaveForanea(string $nombreClaveForanea): void {
        $this->nombreClaveForanea = $nombreClaveForanea;
    }

    /**
     * @return string
     */
    public function getNombreIdTablaRelacion(): string {
        return $this->nombreIdTablaRelacion;
    }

    /**
     * @param string $nombreIdTablaRelacion
     */
    public function setNombreIdTablaRelacion(string $nombreIdTablaRelacion): void {
        $this->nombreIdTablaRelacion = $nombreIdTablaRelacion;
    }

    /**
     * @return Tabla[]
     */
    public function getTablasRelaciones(): array {
        return $this->tablasRelaciones;
    }

    /**
     * @param Tabla[] $tablasRelaciones
     */
    public function setTablasRelaciones(array $tablasRelaciones): void {
        $this->tablasRelaciones = $tablasRelaciones;
    }

    /**
     * @return string
     */
    public function getNombreTablaRelacion(): string {
        return $this->nombreTablaRelacion;
    }

    /**
     * @param string $nombreTablaRelacion
     */
    public function setNombreTablaRelacion(string $nombreTablaRelacion): void {
        $this->nombreTablaRelacion = $nombreTablaRelacion;
    }

}