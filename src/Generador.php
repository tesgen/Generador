<?php

namespace TesGen\Generador;

use Illuminate\Console\Command;

class Generador extends Command {

    protected $name = 'generador';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $this->info("Prueba de comando en consola");
    }
}