<?php

namespace TesGen\Generador\Facades;

use Illuminate\Support\Facades\Facade;

class Generador extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'generador';
    }
}
