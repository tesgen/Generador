@extends('tesgen::layouts.base')

@section('titulo')
    Generador
@endsection

@section('content')
    <div class="container-fluid">
        <h1 class="text-center">Generador Laravel 5.8</h1>

        <div class="card" id="card-principal">
            <div class="card-header">
                Base de Datos
            </div>
            <div class="card-body">
                <div id="nombreBd"></div>
                <h5 class="text-center">Tablas</h5>
                <table class="table table-sm table-bordered table-striped" id="tablaTablas">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nombre Natural</th>
                        <th>Nombre Clase</th>
                        <th>Generado</th>
                        <th>Tipo</th>
                        <th>API</th>
                    </tr>
                    </thead>

                    <tbody id="tablaTablasBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" id="card-principal">
            <div class="card-header">
                Autenticación y Roles
            </div>
            <div class="card-body">

                <div id="autenticacionGenerada"></div>

                <h5 class="text-center">Roles</h5>
                <table class="table table-sm table-bordered table-striped" id="tablaRoles">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Rol</th>
                    </tr>
                    </thead>

                    <tbody id="tablaRolesBody">
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <button class="btn btn-success">Volver a Mapear Base de Datos</button>
        <button onclick="eliminar()" class="btn btn-danger" style="float: right">Eliminar archivos generados</button>
        <hr>

    </div>
@endsection

@section('scripts')

    <script>
        $(function () {
            var tablas = JSON.parse('{!! $tablas_json_string !!}');
            var autenticacion = JSON.parse('{!! $autenticacion_json_string !!}');

            {{--var roles = JSON.parse('{!! $lista_roles !!}');--}}
            var nombreBd = JSON.parse('{!! $nombre_bd !!}');
            // console.log(roles);

            var tableBody = $("#tablaTablasBody")[0];

            var contenidoTablaTablas = '';

            $('#nombreBd').html(`<strong>Base de Datos actual</strong>: ${nombreBd}`);
            $('#autenticacionGenerada').html(`<strong>Autenticación Generada</strong>: ${autenticacion.generado}`);

            for (var i = 0; i < tablas.length; i++) {
                var tabla = tablas[i];

                var tipo;

                if (tabla.esDetalle) {
                    tipo = "Detalle";
                } else if (tabla.esMaestro) {
                    tipo = "Maestro";
                } else {
                    tipo = "CRUD";
                }

                contenidoTablaTablas += `<tr>
                                    <td>${tabla.nombreTabla}</td>
                                    <td>${tabla.nombreNatural}</td>
                                    <td>${tabla.nombreClase}</td>
                                    <td>${tabla.generado}</td>
                                    <td>${tipo}</td>
                                    <td>${tabla.generarApi}</td>
                                </tr>`;
            }

            $(`#tablaTablas > tbody:last-child`).empty().html(contenidoTablaTablas);

            // var contenidoTablaRoles = '';
            //
            // for (var j = 0; j < roles.length; j++) {
            //     var rol = roles[j];
            //     var idRol = rol.id;
            //     var nombreRol = rol.name;
            //
            //     contenidoTablaRoles += `<tr>
            //                         <td>${idRol}</td>
            //                         <td>${nombreRol}</td>
            //                     </tr>`;
            // }
            //
            // $(`#tablaRoles > tbody:last-child`).empty().html(contenidoTablaRoles);
        });

        function eliminar() {

            swal({
                title: '¿Estás seguro de eliminar?',
                input: 'checkbox',
                content: 0,
                icon: "warning",
                buttons: [
                    'Cancelar',
                    'Si, continuar'
                ],
                dangerMode: true
            }).then(function (result) {
                if (result) {
                    $.ajax({
                        url: '{{url('/generador/elimimar_archivos_generados')}}',
                        type: 'POST',
                        beforeSend: function () {
                            $('#spinnerModal').show();
                        },
                        success: function () {
                            $('#spinnerModal').hide();
                            mostrarMensajeExito('Eliminado correctamente', 1000, null);
                        },
                        error: function (e) {
                            $('#spinnerModal').hide();
                            mostrarMensajeError('Error al eliminar', 2000, null);
                            console.log('Error al eliminar');
                            console.log(e);
                        }
                    });
                }
            });

        }

    </script>

@endsection
