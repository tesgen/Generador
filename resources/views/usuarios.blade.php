
@extends('tesgen::layouts.base')

@section('titulo')
    Generador de Autenticación y Usuario
@endsection

@section('content')

    <div class="container-fluid">
        <h1 class="text-center">Generador de Autenticación y Usuario</h1>
        <div class="card" id="card-principal">
            <div class="card-header">
                <strong>Datos para Logueo</strong>
            </div>
            <div class="card-body">

                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="longitudMinimaUsuario">Longitud Mínima Usuario:</label>
                        <input type="text" id="longitudMinimaUsuario" class="form-control longitudMinimaUsuario">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="longitudMaximaUsuario">Longitud Máxima Usuario: </label>
                        <input type="text" id="longitudMaximaUsuario" class="form-control longitudMaximaUsuario">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="longitudMinimaContrasena">Longitud Mínima Contraseña:</label>
                        <input type="text" id="longitudMinimaContrasena" class="form-control longitudMinimaContrasena">
                    </div>
                </div>

                <div class="modal" tabindex="-1" role="dialog" id="spinnerModal"
                     style="background-color: rgba(0, 0, 0, .5) !important;">
                    <div class="modal-dialog modal-dialog-centered text-center" role="document">
                        <span class="fa fa-circle-o-notch fa-spin fa-3x w-100"></span>
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <button id="generar" onclick="generar()" class="btn btn-success">Generar</button>
        <button onclick="guardar()" class="btn btn-primary">Guardar</button>
        <button onclick="eliminar()" class="btn btn-danger" style="float: right">Eliminar Autenticación</button>
        <hr>
    </div>

@endsection

@section('scripts')

    <script>

        var arrayAutenticacion = [];

        $(function () {
            arrayAutenticacion = JSON.parse('{!! $autenticacion_json_string !!}');
            mostrarConfiguraciones();
        });

        function mostrarConfiguraciones() {
            $("#longitudMinimaUsuario").val(arrayAutenticacion['longitudMinimaUsuario']);
            $("#longitudMaximaUsuario").val(arrayAutenticacion['longitudMaximaUsuario']);
            $("#longitudMinimaContrasena").val(arrayAutenticacion['longitudMinimaContrasena']);
        }

        function datosSonValidos() {
            var longitudMinimaUsuario = $("#longitudMinimaUsuario").val().toString();
            var longitudMaximaUsuario = $("#longitudMaximaUsuario").val().toString();
            var longitudMinimaContrasena = $("#longitudMinimaContrasena").val().toString();

            if (longitudMinimaUsuario === '' || longitudMaximaUsuario === '' || longitudMinimaContrasena === '') {
                mostrarMensajeAdvertencia('Debes ingresar todos los campos', 0, null);
                return false;
            }

            if (isNaN(longitudMinimaUsuario) || isNaN(longitudMaximaUsuario) || isNaN(longitudMinimaContrasena)) {
                mostrarMensajeAdvertencia('Todos los campos deben ser números', 0, null);
                return false;
            }

            longitudMinimaUsuario = parseInt(longitudMinimaUsuario);
            longitudMaximaUsuario = parseInt(longitudMaximaUsuario);
            longitudMinimaContrasena = parseInt(longitudMinimaContrasena);

            if (!Number.isInteger(longitudMinimaUsuario) || !Number.isInteger(longitudMaximaUsuario) || !Number.isInteger(longitudMinimaContrasena)) {
                mostrarMensajeAdvertencia('Todos los campos deben ser números enteros', 0, null);
                return false;
            }


            if ((longitudMinimaUsuario < 1 || longitudMinimaUsuario > 255)) {
                mostrarMensajeAdvertencia('La longitud mínima del nombre de usuario debe ser un número entre 1 y 255', 0, null);
                return false;
            }

            if ((longitudMaximaUsuario < 1 || longitudMaximaUsuario > 255)) {
                mostrarMensajeAdvertencia('La longitud máxima del nombre de usuario debe ser un número entre 1 y 255', 0, null);
                return false;
            }

            if (longitudMinimaUsuario > longitudMaximaUsuario) {
                mostrarMensajeAdvertencia('La longitud mínima del nombre de usuario no debe ser mayor a la longitud máxima', 0, null);
                return false;
            }

            if (longitudMinimaContrasena < 1) {
                mostrarMensajeAdvertencia('La longitud mínima de la contraseña debe ser un número', 0, null);
                return false;
            }

            return true;

        }

        function generar() {

            if (!datosSonValidos()) {
                return;
            }

            $.ajax({
                url: '{{url('/generador/guardar_y_generar_autenticacion')}}',
                type: 'POST',
                data: getData(),
                // data: JSON.stringify(getData()),
                beforeSend: function () {
                    $('#spinnerModal').show();
                },
                success: function () {
                    $('#spinnerModal').hide();
                    mostrarMensajeExito('Generado correctamente', 1000, null);
                },
                error: function (e) {
                    $('#spinnerModal').hide();
                    mostrarMensajeError('Error al generar', 2000, null);
                    console.log('Error al generar');
                    console.log(e);
                }
            });
        }

        function getData() {

            var longitudMinimaUsuario = $("#longitudMinimaUsuario").val();
            var longitudMaximaUsuario = $("#longitudMaximaUsuario").val();
            var longitudMinimaContrasena = $("#longitudMinimaContrasena").val();

            return JSON.stringify({
                longitudMinimaUsuario: parseInt(longitudMinimaUsuario),
                longitudMaximaUsuario: parseInt(longitudMaximaUsuario),
                longitudMinimaContrasena: parseInt(longitudMinimaContrasena)
            });

        }

        function guardar() {

            if (!datosSonValidos()) {
                return;
            }

            $.ajax({
                url: '{{url('/generador/guardar_autenticacion')}}',
                type: 'POST',
                data: getData(),
                beforeSend: function () {
                    $('#spinnerModal').show();
                },
                success: function () {
                    $('#spinnerModal').hide();
                    mostrarMensajeExito('Guardado correctamente', 1000, null);
                },
                error: function (e) {
                    $('#spinnerModal').hide();
                    mostrarMensajeError('Error al guardar', 2000, null);
                    console.log('Error al guardar');
                    console.log(e);
                }
            });
        }

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
                        url: '{{url('/generador/eliminar_autenticacion')}}',
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






