@extends('tesgen::layouts.vista_base')

@section('titulo')
    Generador CRUD
@endsection
@section('contenido_propio')

@endsection
@section('scripts_propios')

    <script>

        $(function () {
            mostrarDatosTabla('principal');
        });

        function guardar() {

            if (verficarDatos()) {
                guardarBase(false);
            }

        }

        function verficarDatos() {
            if (!datosDeTablaSonValidos('principal')) {
                mostrarMensajeAdvertencia('Ingrese todos los datos de la tabla', 0, null);
                return false;
            }
            if (!nombreDeClaseEsValido('principal')) {
                mostrarMensajeAdvertencia('El nombre de la clase no es válido', 0, null);
                return false;
            }
            return true;
        }

        function generar() {
            if (verficarDatos()) {
                generarBase(false);
            }

        }

    </script>
@endsection

