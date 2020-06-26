<?php


namespace TesGen\Generador\Modelo;


class Auntenticacion {

    /**
     * @var bool
     */
    private $generado;

    /**
     * @var int
     */
    private $longitudMinimaContrasena;

    /**
     * @var int
     */
    private $longitudMinimaUsuario;

    /**
     * @var int
     */
    private $longitudMaximaUsuario;

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
     * @return int
     */
    public function getLongitudMinimaContrasena(): int {
        return $this->longitudMinimaContrasena;
    }

    /**
     * @param int $longitudMinimaContrasena
     */
    public function setLongitudMinimaContrasena(int $longitudMinimaContrasena): void {
        $this->longitudMinimaContrasena = $longitudMinimaContrasena;
    }

    /**
     * @return int
     */
    public function getLongitudMinimaUsuario(): int {
        return $this->longitudMinimaUsuario;
    }

    /**
     * @param int $longitudMinimaUsuario
     */
    public function setLongitudMinimaUsuario(int $longitudMinimaUsuario): void {
        $this->longitudMinimaUsuario = $longitudMinimaUsuario;
    }

    /**
     * @return int
     */
    public function getLongitudMaximaUsuario(): int {
        return $this->longitudMaximaUsuario;
    }

    /**
     * @param int $longitudMaximaUsuario
     */
    public function setLongitudMaximaUsuario(int $longitudMaximaUsuario): void {
        $this->longitudMaximaUsuario = $longitudMaximaUsuario;
    }

}
