@extends('tesgen::layouts.vista_base_crud')

@section('titulo')
    Generador CRUD
@endsection

@section('texto-titulo')
    <i class="nav-icon fa fa-list-alt" aria-hidden="true"></i><span> Generador CRUD</span>
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
                if (todasLasTablasForaneasEstanGeneradas('principal')) {
                    generarBase(false);
                } else {
                    mostrarMensajeAdvertencia('Debes generar primero el CRUD de todas las tablas foráneas', 0, null);
                }
            }
        }

    </script>
@endsection

