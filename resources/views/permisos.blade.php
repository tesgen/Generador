
@extends('tesgen::layouts.base')

@section('titulo')
    Generador de Permisos
@endsection

@section('content')

    <div class="container-fluid">
        <h1 class="text-center">Generador de Permisos</h1>

        <div class="card" id="card-principal">
            <div class="card-header">
                <strong>Datos de Tablas</strong>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-striped" id="tablaPermisos">
                    <thead>
                    <tr>
                        <th>Tabla</th>
                        <th>Nombre Natural</th>
                        <th>Controlador</th>
                    </tr>
                    </thead>

                    <tbody id="tablaPermisosBody">
                    </tbody>

                </table>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="spinnerModal"
             style="background-color: rgba(0, 0, 0, .5) !important;">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <span class="fa fa-circle-o-notch fa-spin fa-3x w-100"></span>
            </div>
        </div>

        <hr>
        <button id="generar" onclick="guardarPermisos()" class="btn btn-success">Generar Permisos</button>
        <hr>
    </div>

@endsection

@section('scripts')

    <script>

        var tablas = [];
        // var rutas = [];
        var autenticacion_json_string = [];

        $(function () {

            var tableBody = $("#tablaPermisosBody")[0];

            tablas = JSON.parse('{!! $tablas_json_string !!}');
            rutas = JSON.parse('{!! $rutas !!}');
            autenticacion_json_string = JSON.parse('{!! $autenticacion_json_string !!}');

            var contenidoTabla = '';

            for (var i = 0; i < tablas.length; i++) {
                var tabla = tablas[i];
                if (tabla.generado && !tabla.esDetalle) {
                    var nombreTabla = tabla.nombreTabla;
                    var controlador = rutas.find(o => o.nombre === nombreTabla).controlador;
                    var re = /\//g;
                    controlador = controlador.replace(re, "\\");
                    contenidoTabla += `<tr><td>${tabla.nombreTabla}</td><td>${tabla.nombreNatural}</td><td>${controlador}</td></tr>`;
                }
            }

            if (autenticacion_json_string.generado) {
                contenidoTabla += `<tr><td>users</td><td>Usuario</td><td>App\\Http\\Controllers\\UsuarioController</td></tr>`;
                contenidoTabla += `<tr><td>roles</td><td>Rol</td><td>App\\Http\\Controllers\\RoleController</td></tr>`;
            }

            $(`#tablaPermisos > tbody:last-child`).empty().html(contenidoTabla);

        });

        function guardarPermisos() {

            if (!autenticacion_json_string.generado) {
                mostrarMensajeError('La autenticación aun no ha sido generada, ' +
                    'no puede generarse los permisos', 0, null);
            }

            var tableBody = $("#tablaPermisosBody")[0];

            var permisos = [];

            for (var i = 0, row; (row = tableBody.rows[i]); i++) {
                var tabla = row.cells[0].innerText;
                var nombreNatural = row.cells[1].innerText;
                var controlador = row.cells[2].innerText;
                permisos.push({tabla: tabla, nombreNatural: nombreNatural, controlador: controlador});
            }

            $.ajax({
                type: 'POST',
                data: JSON.stringify(permisos),
                url: '{{url('/generador/guardar_permisos')}}',
                beforeSend: function () {
                    $('#spinnerModal').show();
                },
                success: function (data) {
                    // $('#spinnerModal').hide();
                    // obtenerTablas();
                    mostrarMensajeExito('Guardado correctamente', 1000, null);
                    // console.log('Guardado correctamente');
                    console.log(data);
                },
                error: function (error) {
                    console.log('Error al guardar');
                    console.log(error);
                    $('#spinnerModal').hide();
                    mostrarMensajeError('Error al generar', 2000, null);
                }
            });
        }

    </script>
@endsection
