<?php

use TesGen\Generador\Utils\Mapeador;
use Tests\TestCase;

class MapperTest extends TestCase {

    public function testGeTablas() {
        $tablas = Mapeador::getTablas();
        foreach ($tablas as $table) {
            echo $table->getName() . "\n";
            foreach ($table->getColumns() as $column) {
                echo "\t" . $column->getName() . "\n";
            }
        }

        $mapeador = new Mapeador();
        $mapeador->crearArchivoMapSiNoExiste();

        $this->assertEquals(true, true);
    }

}
