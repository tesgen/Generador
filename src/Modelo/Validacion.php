<?php


namespace TesGen\Generador\Modelo;


class Validacion {

    /**
     * @var bool
     */
    private $requerido;

    /**
     * @var bool
     */
    private $longitudMinima;

    /**
     * @var int
     */
    private $valorLongitudMinima;

    /**
     * @var bool
     */
    private $longitudMaxima;

    /**
     * @var int
     */
    private $valorLongitudMaxima;

    /**
     * @var bool
     */
    private $unico;

    /**
     * @return bool
     */
    public function isRequerido(): bool {
        return $this->requerido;
    }

    /**
     * @param bool $requerido
     */
    public function setRequerido(bool $requerido): void {
        $this->requerido = $requerido;
    }

    /**
     * @return bool
     */
    public function isLongitudMinima(): bool {
        return $this->longitudMinima;
    }

    /**
     * @param bool $longitudMinima
     */
    public function setLongitudMinima(bool $longitudMinima): void {
        $this->longitudMinima = $longitudMinima;
    }

    /**
     * @return int
     */
    public function getValorLongitudMinima(): int {
        return $this->valorLongitudMinima;
    }

    /**
     * @param int $valorLongitudMinima
     */
    public function setValorLongitudMinima(int $valorLongitudMinima): void {
        $this->valorLongitudMinima = $valorLongitudMinima;
    }

    /**
     * @return bool
     */
    public function isLongitudMaxima(): bool {
        return $this->longitudMaxima;
    }

    /**
     * @param bool $longitudMaxima
     */
    public function setLongitudMaxima(bool $longitudMaxima): void {
        $this->longitudMaxima = $longitudMaxima;
    }

    /**
     * @return int
     */
    public function getValorLongitudMaxima(): int {
        return $this->valorLongitudMaxima;
    }

    /**
     * @param int $valorLongitudMaxima
     */
    public function setValorLongitudMaxima(int $valorLongitudMaxima): void {
        $this->valorLongitudMaxima = $valorLongitudMaxima;
    }

    /**
     * @return bool
     */
    public function isUnico(): bool {
        return $this->unico;
    }

    /**
     * @param bool $unico
     */
    public function setUnico(bool $unico): void {
        $this->unico = $unico;
    }

}
