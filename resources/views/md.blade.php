@extends('tesgen::layouts.vista_base_crud')

@section('titulo')
    Generador Maestro-Detalle
@endsection

@section('texto-titulo')
    <i class="nav-icon fa fa-window-restore" aria-hidden="true"></i><span> Generador Maestro-Detalle</span>
@endsection

@section('contenido_propio')
    <div class="card" id="card-detalle">
        <div class="card-header">
            <strong>Tabla Detalle</strong>
        </div>
        <div class="card-body">

            @include('tesgen::includes.conf_tabla', [
            'tipoTabla' => 'detalle'
            ])

            @include('tesgen::includes.conf_columnas', [
            'tipoTabla' => 'detalle'
            ])

        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong>Tabla Auxiliar</strong>
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <label>Seleccionar:</label>
                    <select name="comboTablaAuxiliar" id="comboTablaAuxiliar" class="form-control selectpicker">
                        @foreach($array_tablas as $tabla)
                            <option>{{$tabla['nombreTabla']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts_propios')

    <script>

        $(function () {
            mostrarDatosTabla('principal');
            mostrarDatosTabla('detalle');
        });

        function guardar() {
            if (verificarDatos()) {
                guardarBase(true);
            }
        }

        function generar() {
            if (verificarDatos()) {
                generarBase(true);
            }
        }

        function verificarDatos() {
            if (!datosDeTablaSonValidos('principal')) {
                mostrarMensajeAdvertencia('Ingrese todos los datos de la tabla principal', 0, null);
                console.log('no valido principal');
                return false;
            }
            if (!datosDeTablaSonValidos('detalle')) {
                mostrarMensajeAdvertencia('Ingrese todos los datos de la tabla detalle', 0, null);
                console.log('no valido detalle');
                return false;
            }
            if (!nombreDeClaseEsValido('principal')) {
                mostrarMensajeAdvertencia('El nombre de la clase principal no es válido', 0, null);
                return false;
            }
            if (!nombreDeClaseEsValido('detalle')) {
                mostrarMensajeAdvertencia('El nombre de la clase detalle no es válido', 0, null);
                return false;
            }
            if (!tablasSeleccionadasParaMdSonValidas()) {
                mostrarMensajeAdvertencia('Las tablas seleccionadas deben ser todas diferentes', 0, null);
                return false;
            }

            if (!puedeSerTablaDetalle()) {
                mostrarMensajeAdvertencia('La tabla principal seleccionada no es maestra de la tabla detalle seleccionada', 0, null);
                return false;
            }

            if (!puedeSerTablaAuxiliar()) {
                mostrarMensajeAdvertencia('La tabla auxiliar seleccionada no esta relacionada con la tabla detalle seleccionada', 0, null);
                return false;
            }

            if (!tablaAuxiliarYaGenerada()) {
                mostrarMensajeAdvertencia('La tabla auxiliar aun no ha sido generada', 0, null);
                return false;
            }

            return true;
        }

        function puedeSerTablaDetalle() {

            var nombreTablaPrincipal = $(`#combo-tabla-principal option:selected`).text();
            var nombreTablaDetalle = $(`#combo-tabla-detalle option:selected`).text();

            var tablaTemp;
            var idTablaPrincipal;

            var i = 0;

            for (i = 0; i < tablas.length; i++) {
                tablaTemp = tablas[i];

                if (tablaTemp.nombreTabla === nombreTablaPrincipal) {
                    idTablaPrincipal = tablaTemp.clavePrimaria;
                    break;
                }
            }

            var tablaDetalle;

            for (i = 0; i < tablas.length; i++) {
                tablaTemp = tablas[i];

                if (tablaTemp.nombreTabla === nombreTablaDetalle) {
                    tablaDetalle = tablaTemp;
                    break;
                }
            }

            for (i = 0; i < tablaDetalle.clavesForaneas.length; i++) {

                if (tablaDetalle.clavesForaneas[i].nombreIdTablaRelacion === idTablaPrincipal) {
                    return true;
                }
            }
            return false;
        }

        function puedeSerTablaAuxiliar() {
            var nombreTablaAuxiliar = $("#comboTablaAuxiliar option:selected").text();
            var nombreTablaDetalle = $(`#combo-tabla-detalle option:selected`).text();

            var tablaTemp;
            var idTablaAuxiliar;

            var i = 0;

            for (i = 0; i < tablas.length; i++) {
                tablaTemp = tablas[i];
                if (tablaTemp.nombreTabla === nombreTablaAuxiliar) {
                    idTablaAuxiliar = tablaTemp.clavePrimaria;
                    break;
                }
            }

            var tablaDetalle;

            for (i = 0; i < tablas.length; i++) {
                tablaTemp = tablas[i];

                if (tablaTemp.nombreTabla === nombreTablaDetalle) {
                    tablaDetalle = tablaTemp;
                    break;
                }
            }

            for (i = 0; i < tablaDetalle.clavesForaneas.length; i++) {

                if (tablaDetalle.clavesForaneas[i].nombreIdTablaRelacion === idTablaAuxiliar) {
                    return true;
                }
            }
            return false;
        }

        function tablaAuxiliarYaGenerada() {

            var nombreTablaAuxiliar = $("#comboTablaAuxiliar option:selected").text();

            var tablaTemp;

            for (var i = 0; i < tablas.length; i++) {
                tablaTemp = tablas[i];

                if (tablaTemp.nombreTabla === nombreTablaAuxiliar && tablaTemp.generado === true) {
                    return true;
                }
            }

            return false;
        }

    </script>
@endsection
