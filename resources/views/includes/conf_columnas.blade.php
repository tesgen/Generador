<div class="card">
    <div class="card-header">
        Columnas
    </div>
    <div class="card-body">
        <table class="table table-sm table-bordered table-striped tablaColumnas">
            <thead>
            <tr>
                <th>Nombre Columna</th>
                <th>Tipo</th>
                <th>Foraneo</th>
                <th>Nombre Natural</th>
                <th>Referente</th>
            </tr>
            </thead>
            <tbody class="tablaColumnasBody">
            </tbody>
        </table>
        <div class="card">
            <div class="card-header">
                Configuraciones de columnas
            </div>
            <div class="card-body conf-columna">
                <nav>
                    <div class="nav nav-tabs" role="tablist">

                        <a class="nav-item nav-link" id="nav-tabla-{{$tipoTabla}}-tab" data-toggle="tab"
                           href="#nav-tabla-{{$tipoTabla}}" role="tab"
                           aria-controls="nav-tabla-{{$tipoTabla}}" aria-selected="false">Tabla HTML</a>

                        <a class="nav-item nav-link active" id="nav-formulario-guardar-{{$tipoTabla}}-tab"
                           data-toggle="tab"
                           href="#nav-formulario-guardar-{{$tipoTabla}}" role="tab"
                           aria-controls="nav-formulario-guardar-{{$tipoTabla}}" aria-selected="true">Formulario
                            Guardar</a>

{{--                        @if($tipoTabla == 'principal')--}}

                            <a @if($tipoTabla == 'detalle') hidden @endif class="nav-item nav-link" id="nav-formulario-actualizar-{{$tipoTabla}}-tab"
                               data-toggle="tab"
                               href="#nav-formulario-actualizar-{{$tipoTabla}}" role="tab"
                               aria-controls="nav-formulario-actualizar-{{$tipoTabla}}" aria-selected="true">Formulario
                                Actualizar</a>
{{--                        @endif--}}
                    </div>
                </nav>
                <div class="tab-content">
                    <br>
                    <div class="tab-pane show " id="nav-tabla-{{$tipoTabla}}" role="tabpanel"
                         aria-labelledby="nav-tabla-{{$tipoTabla}}-tab">

                        <table class="table table-sm table-bordered table-striped tablaColumnasTablaHtml">
                            <thead>
                            <tr>
                                <th>Nombre Columna</th>
                                <th>Visible en Tabla</th>
                                <th>Visible en Reporte</th>
                            </tr>
                            </thead>

                            <tbody class="tablaColumnasTablaHtmlBody">
                            </tbody>

                        </table>
                    </div>

                    <div class="tab-pane active nav-formulario-guardar-{{$tipoTabla}}"
                         id="nav-formulario-guardar-{{$tipoTabla}}" role="tabpanel"
                         aria-labelledby="nav-formulario-guardar-{{$tipoTabla}}-tab">

                        <div class="grid-stack guardar-{{$tipoTabla}}"></div>
                        <br>

                        {{--                        <input type="text" id="id-campo-auxiliar-guardar-{{$tipoTabla}}"><button onclick="agregarItemAuxiliar('{{$tipoTabla}}', true)">Agregar</button>--}}
                        {{--                        <br>--}}
                        <div class="form-row">
                            <div class="form-group col-sm-12">
                                <label>Campos no visibles:</label>
                                <select id="campos-no-visibles-guardar-{{$tipoTabla}}"
                                        class="form-control selectpicker">

                                </select>
                            </div>
                            <button class="btn btn-info btnAgregarFormularioGuardar" onclick="agregarNoVisibleAForm('{{$tipoTabla}}', true)">
                                Agregar a Formulario
                            </button>
                            &nbsp;
                            <button
                                data-toggle="modal" data-target="#{{'modal-guardar-' . $tipoTabla}}"
                                class="btn btn-light btnOpcionesCampoGuardar" onclick="mostrarConfigsColumnaEliminada('{{$tipoTabla}}', true)">
                                Opciones
                            </button>
                        </div>
                        @include('tesgen::includes.conf_columna_guardar', ["tipoTabla' => '{{$tipoTabla}}"])


                    </div>
{{--                    @if($tipoTabla == 'principal')--}}
                        <div @if($tipoTabla == 'detalle') hidden @endif class="tab-pane nav-formulario-actualizar-{{$tipoTabla}}"
                             id="nav-formulario-actualizar-{{$tipoTabla}}" role="tabpanel"
                             aria-labelledby="nav-formulario-actualizar-{{$tipoTabla}}-tab">

                            <div class="form-row">
                                <div class="form-group col-sm-12">
                                    <input id="checkIgualQueFormGuardar" type="checkbox"
                                           class="checkIgualQueFormGuardar"
                                           onclick="habilitarDeshabilitarFormActualizar('{{$tipoTabla}}')">
                                    <label for="checkIgualQueFormGuardar">Igual que Formulario Guardar</label>
                                </div>
                            </div>

                            <div class="formActualizar">

                                <div class="grid-stack actualizar-{{$tipoTabla}}"></div>
                                <br>
{{--                                <input type="text" id="id-campo-auxiliar-actualizar-{{$tipoTabla}}">--}}
{{--                                <button onclick="agregarItemAuxiliar('{{$tipoTabla}}', false)">Agregar</button>--}}
{{--                                jl;ksadjf;lkasdfasdfasdflaksasdjdf--}}
                                <br>
                                <div class="form-row">
                                    <div class="form-group col-sm-12">
                                        <label>Campos no visibles:</label>
                                        <select id="campos-no-visibles-actualizar-{{$tipoTabla}}"
                                                class="form-control selectpicker">

                                        </select>
                                    </div>
                                    <button class="btn btn-info btnAgregarFormularioActualizar"
                                            onclick="agregarNoVisibleAForm('{{$tipoTabla}}', false)">
                                        Agregar a Formulario
                                    </button>
                                    &nbsp;
                                    <button
                                        data-toggle="modal" data-target="#{{'modal-actualizar-' . $tipoTabla}}"
                                        class="btn btn-light btnOpcionesCampoActualizar" onclick="mostrarConfigsColumnaEliminada('{{$tipoTabla}}', false)">
                                        Opciones
                                    </button>
                                </div>
                                @include('tesgen::includes.conf_columna_actualizar', ["tipoTabla' => '{{$tipoTabla}}"])
                            </div>
                        </div>
{{--                    @endif--}}
                </div>
                <br>
            </div>
        </div>
    </div>
</div>


