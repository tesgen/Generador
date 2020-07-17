<div class="modal fade bd-example-modal-lg" id="modal-guardar-{{$tipoTabla}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Opciones Guardar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <strong>Nombre de Campo: </strong> <span class="nombreCampo"></span>
                <br>
{{--                Valor Guardable: --}}
                <input style="display: none" type="checkbox"
                                        onclick="habilitarDeshabilitarGuardable('{{$tipoTabla}}')"
                                        id="checkCampoGuardable"
                                        class="checkCampoGuardable"><br>
                Obtener por:<br>
                <div class="confColumnasGuardar">
                    <div class="form-group col-sm-12">
                        <input type="radio" class="radioCampoDeTextoGuardar"
                               name="radioValorGuardar">
                        <label>&nbsp;<div class="labelCampoDeTexto">Campo de texto</div></label>
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="radio" class="radioAutomaticoGuardar"
                               name="radioValorGuardar">
                        <label>&nbsp;Automático:</label>
                        <input type="text" class="form-control valorAutomaticoGuardar">
                    </div>
                    <div class="form-group col-sm-12 conjuntoDeValores">
                        <input type="radio" class="radioConjuntoDeValoresGuardar"
                               name="radioValorGuardar"><label>&nbsp;Conjunto de
                            valores:</label>
                        <input type="text" class="form-control valorConjuntoDeValoresGuardar">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="radio" class="radioFormulaGuardar"
                               name="radioValorGuardar"><label>&nbsp;Fórmula:</label>
                        <input type="text" class="form-control valorFormulaGuardar">
                    </div>
                    <div class="form-group col-sm-12 autoIncremental">
                        <input type="radio" class="radioAutoincrementalGuardar"
                               name="radioValorGuardar">
                        <label>&nbsp;Autoincremental</label>
                    </div>
                    <div class="validaciones">
                        <hr>
                        Validaciones:
                        <div class="form-group col-sm-12">
                            <input type="checkbox" class="checkRequeridoGuardar">
                            <label>&nbsp;Requerido</label>
                        </div>
                        <div class="form-group col-sm-12">
                            <input type="checkbox" class="checkLongitudMinimaGuardar">
                            <label>&nbsp;Longitud Mínima:</label>
                            <input type="text" class="form-control valorLongitudMinimaGuardar">
                        </div>
                        <div class="form-group col-sm-12">
                            <input type="checkbox" class="checkLongitudMaximaGuardar">
                            <label>&nbsp;Longitud Máxima:</label>
                            <input type="text" class="form-control valorLongitudMaximaGuardar">
                        </div>
                        <div class="form-group col-sm-12">
                            <input type="checkbox" class="checkUnicoGuardar">
                            <label>&nbsp;Único</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"
                        onclick="aceptarConfigColumna('{{$tipoTabla}}')">Aceptar
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
