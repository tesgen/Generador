<?php


namespace TesGen\Generador\Utils;

class Constante {

    const DIRECTORIO_PLANTILLAS = 'vendor\\tesgen\\generador\\plantillas\\';
    const DIRECTORIO_PLANTILLAS_VISTA = self::DIRECTORIO_PLANTILLAS . 'vista\\';
    const ARCHIVO_PLANTILLA_CREATE = 'create';
//    const ARCHIVO_PLANTILLA_CREATE_MD = 'create_md';
    const ARCHIVO_PLANTILLA_CREATE_MD2 = 'create_md2';
    const ARCHIVO_PLANTILLA_EDIT = 'edit';
    const ARCHIVO_PLANTILLA_INDEX = 'index';
    const ARCHIVO_PLANTILLA_SHOW = 'show';
    const ARCHIVO_PLANTILLA_TABLA_HTML = 'tabla_html';

    const TABLAS_NO_MOSTRABLES = ['users', 'migrations'];

}
