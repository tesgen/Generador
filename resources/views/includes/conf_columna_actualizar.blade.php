<div class="modal fade bd-example-modal-lg" id="modal-actualizar-{{$tipoTabla}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Opciones Actualizar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {{--                Visible: --}}
                <input style="display: none" type="checkbox"
                       onclick="habilitarDeshabilitarValorActualizable('{{$tipoTabla}}')"
                       id="checkVisibleActualizar"
                       class="checkVisibleActualizar"><br>
                Valor Actualizable:
                <input type="checkbox"
                       onclick="habilitarDeshabilitarActualizable('{{$tipoTabla}}')"
                       id="checkCampoActualizable"
                       class="checkCampoActualizable"><br>
                <div class="confColumnasActualizar">
                    Obtener por:<br>
                    <div class="form-group col-sm-12">
                        <input type="radio" class="radioCampoDeTextoActualizar"
                               name="radioValorActualizar">
                        <label>&nbsp;Campo de texto</label>
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="radio" class="radioAutomaticoActualizar"
                               name="radioValorActualizar">
                        <label>&nbsp;Automático:</label>
                        <input type="text" class="form-control valorAutomaticoActualizar">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="radio" class="radioConjuntoDeValoresActualizar"
                               name="radioValorActualizar"><label>&nbsp;Conjunto de
                            valores:</label>
                        <input type="text"
                               class="form-control valorConjuntoDeValoresActualizar">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="radio" class="radioFormulaActualizar"
                               name="radioValorActualizar"><label>&nbsp;Fórmula:</label>
                        <input type="text" class="form-control valorFormulaActualizar">
                    </div>
                    <hr>
                    Validaciones:
                    <div class="form-group col-sm-12">
                        <input type="checkbox" class="checkRequeridoActualizar">
                        <label>&nbsp;Requerido</label>
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="checkbox" class="checkLongitudMinimaActualizar">
                        <label>&nbsp;Longitud Mínima:</label>
                        <input type="text" class="form-control valorLongitudMinimaActualizar">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="checkbox" class="checkLongitudMaximaActualizar">
                        <label>&nbsp;Longitud Máxima:</label>
                        <input type="text" class="form-control valorLongitudMaximaActualizar">
                    </div>
                    <div class="form-group col-sm-12">
                        <input type="checkbox" class="checkUnicoActualizar">
                        <label>&nbsp;Único</label>
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


