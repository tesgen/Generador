
<script type="text/javascript">

    var tablas = [];
    var columnaSeleccionada = null;
    var tablaSeleccionada;

    $(function () {
        tablas = JSON.parse('{!! $tablas_json_string !!}');
        $('.tablaColumnasTablaHtmlBody').sortable();
    });

    function datosDeTablaSonValidos(tipoTabla) {

        var divConfTabla = $(`#card-${tipoTabla}`);

        var nombreClase = divConfTabla.find(".nombreClase").val();
        var nombreNatural = divConfTabla.find(".nombreNatural").val();
        var nombrePlural = divConfTabla.find(".nombrePlural").val();

        if (nombreClase === '' || nombreNatural === '' || nombrePlural === '') {
            return false;
        }

        return true;
    }

    function nombreDeClaseEsValido(tipoTabla) {

        var nombreNoValidos = ['__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch',
            'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty',
            'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final',
            'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof',
            'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private', 'protected',
            'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use',
            'var', 'while', 'xor', 'int', 'float', 'bool', 'string', 'true', 'false', 'null', 'void', 'iterable', 'object'];

        var divConfTabla = $(`#card-${tipoTabla}`);

        var nombreClase = divConfTabla.find(".nombreClase").val();

        return nombreClase.match(/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/) !== null &&
            nombreNoValidos.indexOf(nombreClase) < 0;
    }

    function tablasSeleccionadasParaMdSonValidas() {

        var tablaPrincipal = $(`#combo-tabla-principal option:selected`).text();
        var tablaDetalle = $(`#combo-tabla-detalle option:selected`).text();
        var tablaAuxiliar = $("#comboTablaAuxiliar option:selected").text();

        if (tablaPrincipal === tablaDetalle || tablaPrincipal === tablaAuxiliar
            || tablaDetalle === tablaAuxiliar) {
            return false;
        }
        return true;

    }

    function obtenerTablas() {
        $.ajax({
            type: 'GET',
            url: "{{url('/generador/getTablas')}}",
            success: function (data) {
                tablas = data;
            },
            error: function (error) {
                console.log(error.responseJSON.message);
            }
        });
    }

    function mostrarDatosTabla(tipoTabla) {

        var nombreTabla = $(`#combo-tabla-${tipoTabla} option:selected`).text();

        var tabla = obtenerTablaSeleccionada(`combo-tabla-${tipoTabla}`);

        var divConfTabla = $(`#card-${tipoTabla}`);

        divConfTabla.find(".nombreClase").val(tabla.nombreClase);
        divConfTabla.find(".nombreNatural").val(tabla.nombreNatural);
        divConfTabla.find(".nombrePlural").val(tabla.nombrePlural);

        divConfTabla.find(".checkGenerarApi").prop('checked', tabla.generarApi);

        mostrarTablaDatosColumna(tabla, tipoTabla);

        mostrarGridGuardar(tabla, tipoTabla);
        mostrarGridActualizar(tabla, tipoTabla);

        mostrarCamposNoVisibles(tabla, tipoTabla, true);
        mostrarCamposNoVisibles(tabla, tipoTabla, false);
    }

    var nestOptions = {
        acceptWidgets: '.grid-stack-item.sub',
        dragOut: false,
        resizable: {
            handles: 'e'
        },
        animate: true,
        cellHeight: '50px',
        // verticalMargin: '10px',
        float: true
    };

    var itemsGrid = [];

    var grid;
    var gridGuardar;
    var gridGuardarDetalle;

    function mostrarGridGuardar(tabla, tipoTabla) {

        mostrarGrid(tabla, tipoTabla, true);
    }

    var gridActualizar;
    var gridActualizarDetalle;

    function mostrarGridActualizar(tabla, tipoTabla) {

        mostrarGrid(tabla, tipoTabla, false);
    }

    function mostrarGrid(tabla, tipoTabla, esParaGuardar) {

        establecerCamposAuxiliares(tabla, tipoTabla, esParaGuardar);

        var div = esParaGuardar ? '.grid-stack.guardar-' + tipoTabla : '.grid-stack.actualizar-' + tipoTabla

        grid = GridStack.init(nestOptions, div);
        grid.column(12);

        itemsGrid = [];

        grid.removeAll();
        grid.batchUpdate();

        for (i = 0; i < tabla.columnas.length; i++) {

            var col = tabla.columnas[i];
            itemsGrid.push(esParaGuardar ? col.jsonInputGuardar : col.jsonInputActualizar);
        }

        for (var i = 0; i < itemsGrid.length; i++) {

            var n = itemsGrid[i];

            if (n.tipo === 'nativo' && !n.visible) {
                continue;
            }

            agregarNodoGrid(tipoTabla, esParaGuardar, grid, n);
        }

        grid.commit();

        darValorAGrid(tipoTabla, esParaGuardar, grid);
    }

    function darValorAGrid(tipoTabla, esParaGuardar, grid) {
        if (esParaGuardar) {

            if (tipoTabla === 'principal') {
                gridGuardar = grid;
            } else {
                gridGuardarDetalle = grid;
            }

        } else {
            if (tipoTabla === 'principal') {
                gridActualizar = grid;
            } else {
                gridActualizarDetalle = grid;
            }
        }
    }

    function agregarNodoGrid(tipoTabla, esParaGuardar, grid, n) {

        var idModal = esParaGuardar ? 'modal-guardar-' + tipoTabla : 'modal-actualizar-' + tipoTabla;

        grid.addWidget('' +
            '<div class="grid-stack-item sub">' +
            '   <div style="" class="grid-stack-item-content">' + n.id + '' +
            '       <button data-toggle="modal" data-target="#' + idModal + '" ' +
            '       onclick="mostrarConfigsColumna(\'' + tipoTabla + '\', \'' + n.id + '\')">Opciones</button>' +
            '       <button style="float: right;" onClick="removeWidget(\'' + tipoTabla + '\', ' +
                        esParaGuardar + ', this.parentNode.parentNode)"><span aria-hidden="true">&times;</span></button>' +
            '   </div>' +
            '</div>', n);
    }

    function agregarNoVisibleAForm(tipoTabla, esParaGuardar) {

        var card = $(`#card-${tipoTabla}`);

        var combo;

        var grid;

        var nombreIdCombo;

        if (esParaGuardar) {
            if (tipoTabla === 'principal') {
                grid = gridGuardar;
            } else {
                grid = gridGuardarDetalle;
            }
            nombreIdCombo = 'campos-no-visibles-guardar-' + tipoTabla;

        } else {
            if (tipoTabla === 'principal') {
                grid = gridActualizar;
            } else {
                grid = gridActualizarDetalle;
            }
            nombreIdCombo = 'campos-no-visibles-actualizar-' + tipoTabla;
        }

        var id = $(`#${nombreIdCombo}`).find('option:selected').attr('id');

        if (id === undefined) {
            return;
        }

        let n = {
            x: 0,
            width: 12,
            height: 1,
            id: id
        };

        agregarNodoGrid(tipoTabla, esParaGuardar, grid, n);

        darValorAGrid(tipoTabla, esParaGuardar, grid);

        $('#' + nombreIdCombo).find('option:selected').remove();

    }

    function mostrarCamposNoVisibles(tabla, tipoTabla, esParaGuardar) {

        var card = $(`#card-${tipoTabla}`);

        var combo;

        if (esParaGuardar) {
            combo = card.find('#campos-no-visibles-guardar-' + tipoTabla);
        } else {
            combo = card.find('#campos-no-visibles-actualizar-' + tipoTabla);
        }

        combo.empty();

        for (i = 0; i < tabla.columnas.length; i++) {

            let columna = tabla.columnas[i];

            if (esParaGuardar) {
                if (!columna.jsonInputGuardar.visible && columna.jsonInputGuardar.agregableAForm) {
                    combo.append($('<option>', {
                        id: columna.nombreColumna,
                        text: columna.nombreColumna
                    }));
                }
            } else {
                if (!columna.jsonInputActualizar.visible && columna.jsonInputActualizar.agregableAForm) {
                    combo.append($('<option>', {
                        id: columna.nombreColumna,
                        text: columna.nombreColumna
                    }));
                }
            }

        }
    }

    function removeWidget(tipoTabla, esParaGuardar, parentNode) {

        if (esParaGuardar) {

            if (tipoTabla === 'principal') {
                gridGuardar.removeWidget(parentNode);
            } else {
                gridGuardarDetalle.removeWidget(parentNode);
            }

        } else {
            if (tipoTabla === 'principal') {
                gridActualizar.removeWidget(parentNode);
            } else {
                gridActualizarDetalle.removeWidget(parentNode);
            }
        }

        var eliminado = parentNode.attributes['data-gs-id'].nodeValue;

        var card = $(`#card-${tipoTabla}`);

        var combo;

        if (esParaGuardar) {
            combo = card.find('#campos-no-visibles-guardar-' + tipoTabla);
        } else {
            combo = card.find('#campos-no-visibles-actualizar-' + tipoTabla);
        }

        combo.append($('<option>', {
            id: eliminado,
            text: eliminado
        }));

    }

    var auxiliaresGuardar = [];
    var auxiliaresGuardarDetalle = [];
    var auxiliaresActualizar = [];
    var auxiliaresActualizarDetalle = [];

    function establecerCamposAuxiliares(tabla, tipoTabla, esParaGuardar) {

        if (esParaGuardar) {

            if (tipoTabla === 'principal') {

                tabla.jsonInputsGuardarAuxiliar.forEach(function (item) {
                    auxiliaresGuardar.push(item.id);
                });

            } else {
                tabla.jsonInputsGuardarAuxiliar.forEach(function (item) {
                    auxiliaresGuardarDetalle.push(item.id);
                });
            }

        } else {
            if (tipoTabla === 'principal') {
                tabla.jsonInputsActualizarAuxiliar.forEach(function (item) {
                    auxiliaresActualizar.push(item.id);
                });
            } else {
                tabla.jsonInputsActualizarAuxiliar.forEach(function (item) {
                    auxiliaresActualizarDetalle.push(item.id);
                });
            }
        }
    }

    function agregarWidgetAuxiliar(tipoTabla, esParaGuardar, grid, n) {
        var idModal = esParaGuardar ? 'modal-guardar-' + tipoTabla : 'modal-actualizar-' + tipoTabla;

        grid.addWidget('' +
            '<div class="grid-stack-item sub">' +
            '   <div style="" class="grid-stack-item-content widget-auxiliar">' + n.id + '' +
            '       <button data-toggle="modal" data-target="#' + idModal + '" ' +
            '       onclick="mostrarConfigsColumna(\'' + tipoTabla + '\', \'' + n.id + '\')">Opciones</button>' +
            '   </div>' +
            '</div>', n);
    }

    function habilitarDeshabilitarGuardable(tipoTabla) {
        var card = $(`#card-${tipoTabla}`);
        var habilitado = card.find('.checkCampoGuardable').prop('checked');
        card.find(".confColumnasGuardar :input").prop("disabled", !habilitado);
    }

    function habilitarDeshabilitarValorActualizable(tipoTabla) {
        var card = $(`#card-${tipoTabla}`);
        var visibleActualizarHabilitado = card.find('.checkVisibleActualizar').prop('checked');

        card.find(".checkCampoActualizable").prop("disabled", !visibleActualizarHabilitado);

        habilitarDeshabilitarActualizable(tipoTabla);

    }

    function habilitarDeshabilitarActualizable(tipoTabla) {

        var card = $(`#card-${tipoTabla}`);

        var habilitado = card.find('.checkVisibleActualizar').prop('checked') &&
            card.find('.checkCampoActualizable').prop('checked') && !card.find('.checkCampoActualizable').prop('disabled');

        card.find(".confColumnasActualizar :input").prop("disabled", !habilitado);
    }

    function habilitarDeshabilitarFormActualizar(tipoTabla) {

        var card = $(`#card-${tipoTabla}`);

        var igualQueFormGuardar = card.find('.checkIgualQueFormGuardar').prop('checked');

        if (igualQueFormGuardar) {
            card.find(".formActualizar").hide();
        } else {
            card.find(".formActualizar").show();
        }

    }

    function mostrarTablaDatosColumna(tabla, tipoTabla) {

        var contenidoTablaColumnas = '';

        for (i = 0; i < tabla.columnas.length; i++) {

            var columna = tabla.columnas[i];

            if (columna.nombreColumna === 'created_at' || columna.nombreColumna === 'updated_at') {
                continue;
            }

            contenidoTablaColumnas += '    <tr>' +
                '      <td>' + columna.nombreColumna + '</td>' +
                '      <td>' + columna.tipo + '</td>' +
                '      <td>' + (columna.claveForanea ? "SI" : "NO") + '</td>' +
                '      <td contenteditable="true">' + columna.nombreNatural + '</td>' +
                '      <td> <input type="checkbox" class="checkReferente" ' + (columna.referente ? 'checked' : '') + '/></td>' +
                '    </tr>';
        }

        $(`#card-${tipoTabla}`).find('.tablaColumnas > tbody:last-child').empty().html(contenidoTablaColumnas);
        mostrarDatosColumnaTablaHtml(tabla, tipoTabla);

        columnaSeleccionada = null;

        // habilitarDeshabilitarAceptar(tipoTabla);
    }

    function mostrarDatosColumnaTablaHtml(tabla, tipoTabla) {

        var contenidoTablaColumnasHtml = '';

        var columnasOrdenadas = tabla.columnas.sort(function (a, b) {
            return a.ordenTablaHtml - b.ordenTablaHtml;
        });

        for (i = 0; i < columnasOrdenadas.length; i++) {

            var columna = columnasOrdenadas[i];

            if (columna.nombreColumna === 'created_at' || columna.nombreColumna === 'updated_at') {
                continue;
            }

            contenidoTablaColumnasHtml += '    <tr>' +
                '      <td>' + columna.nombreColumna + '</td>' +
                '      <td> <input type="checkbox" class="checkVisibleTabla" ' + (columna.visibleEnTabla ? 'checked' : '') + '/></td>' +
                '      <td> <input type="checkbox" class="checkVisibleReporte" ' + (columna.visibleEnReporte ? 'checked' : '') + '/></td>' +
                '    </tr>';

        }

        $(`#card-${tipoTabla}`).find('.tablaColumnasTablaHtml > tbody:last-child').empty().html(contenidoTablaColumnasHtml);

        $(`#card-${tipoTabla}`).find(".checkIgualQueFormGuardar").prop('checked', tabla.formActualizarIgualQueGuardar);
        habilitarDeshabilitarFormActualizar(tipoTabla);
    }

    function mostrarConfigsColumna(tipoTabla, nombreColumna) {

        columnaSeleccionada = nombreColumna;

        var nombreTabla = $(`#combo-tabla-${tipoTabla} option:selected`).text();

        var tabla = obtenerTablaSeleccionada(`combo-tabla-${tipoTabla}`);

        for (var j = 0; j < tabla.columnas.length; j++) {

            var columna = tabla.columnas[j];

            var validacionesGuardar = columna.validacionesGuardar;
            var validacionesActualizar = columna.validacionesActualizar;

            if (nombreColumna === columna.nombreColumna) {

                var confColumna = $(`#card-${tipoTabla}`).find(".conf-columna");

                //Guardar
                confColumna.find(".checkCampoGuardable").prop('checked', columna.campoGuardable);
                confColumna.find(".radioCampoDeTextoGuardar").prop('checked', columna.campoDeTextoGuardar);
                confColumna.find(".radioAutomaticoGuardar").prop('checked', columna.automaticoGuardar);
                confColumna.find(".radioConjuntoDeValoresGuardar").prop('checked', columna.conjuntoDeValoresGuardar);
                confColumna.find(".radioFormulaGuardar").prop('checked', columna.formulaGuardar);
                confColumna.find(".valorAutomaticoGuardar").val(columna.valorAutomaticoGuardar);
                confColumna.find(".valorConjuntoDeValoresGuardar").val(columna.valorConjuntoDeValoresGuardar);
                confColumna.find(".valorFormulaGuardar").val(columna.valorFormulaGuardar);
                //Validaciones Guardar
                confColumna.find(".checkRequeridoGuardar").prop('checked', validacionesGuardar.requerido);
                confColumna.find(".checkLongitudMinimaGuardar").prop('checked', validacionesGuardar.longitudMinima);
                confColumna.find(".valorLongitudMinimaGuardar").val(validacionesGuardar.valorLongitudMinima);
                confColumna.find(".checkLongitudMaximaGuardar").prop('checked', validacionesGuardar.longitudMaxima);
                confColumna.find(".valorLongitudMaximaGuardar").val(validacionesGuardar.valorLongitudMaxima);
                confColumna.find(".checkUnicoGuardar").prop('checked', validacionesGuardar.unico);

                //Actualizar
                confColumna.find(".checkVisibleActualizar").prop('checked', columna.visibleEnFormularioActualizar);
                confColumna.find(".checkCampoActualizable").prop('checked', columna.campoActualizable);
                confColumna.find(".radioCampoDeTextoActualizar").prop('checked', columna.campoDeTextoActualizar);
                confColumna.find(".radioAutomaticoActualizar").prop('checked', columna.automaticoActualizar);
                confColumna.find(".radioConjuntoDeValoresActualizar").prop('checked', columna.conjuntoDeValoresActualizar);
                confColumna.find(".radioFormulaActualizar").prop('checked', columna.formulaActualizar);
                confColumna.find(".valorAutomaticoActualizar").val(columna.valorAutomaticoActualizar);
                confColumna.find(".valorConjuntoDeValoresActualizar").val(columna.valorConjuntoDeValoresActualizar);
                confColumna.find(".valorFormulaActualizar").val(columna.valorFormulaActualizar);
                //Validaciones Actualizar
                confColumna.find(".checkRequeridoActualizar").prop('checked', validacionesActualizar.requerido);
                confColumna.find(".checkLongitudMinimaActualizar").prop('checked', validacionesActualizar.longitudMinima);
                confColumna.find(".valorLongitudMinimaActualizar").val(validacionesActualizar.valorLongitudMinima);
                confColumna.find(".checkLongitudMaximaActualizar").prop('checked', validacionesActualizar.longitudMaxima);
                confColumna.find(".valorLongitudMaximaActualizar").val(validacionesActualizar.valorLongitudMaxima);
                confColumna.find(".checkUnicoActualizar").prop('checked', validacionesActualizar.unico);

                break;
            }
        }

        habilitarDeshabilitarGuardable(tipoTabla);
        habilitarDeshabilitarValorActualizable(tipoTabla);
    }

    function establecerDatosBase(tipoTabla) {

        tablaSeleccionada = obtenerTablaSeleccionada(`combo-tabla-${tipoTabla}`);

        var card = $(`#card-${tipoTabla}`);

        tablaSeleccionada.nombreClase = card.find(".nombreClase").val();
        tablaSeleccionada.nombreNatural = card.find(".nombreNatural").val();
        tablaSeleccionada.nombrePlural = card.find(".nombrePlural").val();
        tablaSeleccionada.generarApi = card.find(".checkGenerarApi").prop('checked');
        tablaSeleccionada.generado = true;
        tablaSeleccionada.formActualizarIgualQueGuardar = card.find(".checkIgualQueFormGuardar").prop('checked');

        var tableBody = card.find(".tablaColumnasBody")[0];

        var columnaNombreColumna = 0;
        var columnaNombreNatural = 3;

        for (let j = 0; j < tablaSeleccionada.columnas.length; j++) {

            for (var i = 0, row; (row = tableBody.rows[i]); i++) {

                var checkBoxesReferente = tableBody.getElementsByClassName("checkReferente");

                var nombreColumna = row.cells[columnaNombreColumna].innerText;
                var nombreNatural = row.cells[columnaNombreNatural].innerText;
                nombreNatural = nombreNatural.replace(/\u00A0/, " ").trim();

                if (tablaSeleccionada.columnas[j].nombreColumna === nombreColumna) {

                    var referente = checkBoxesReferente[i].checked;

                    tablaSeleccionada.columnas[j].nombreNatural = nombreNatural;
                    tablaSeleccionada.columnas[j].referente = referente;

                    break;
                }
            }

        }

        establecerGridGuardar(tipoTabla);
        establecerGridActualizar(tipoTabla);

    }

    function establecerGridGuardar(tipoTabla) {

        establecerGrid(tipoTabla, true);
    }

    function establecerGridActualizar(tipoTabla) {

        establecerGrid(tipoTabla, false);
    }

    function establecerGrid(tipoTabla, esParaGuardar) {

        let columnasGridNativas = [];
        let auxiliares;

        var grid;

        if (esParaGuardar) {

            if (tipoTabla === 'principal') {
                grid = gridGuardar;
                auxiliares = auxiliaresGuardar;
            } else {
                grid = gridGuardarDetalle;
                auxiliares = auxiliaresGuardarDetalle;
            }

        } else {
            if (tipoTabla === 'principal') {
                grid = gridActualizar;
                auxiliares = auxiliaresActualizar;
            } else {
                grid = gridActualizarDetalle;
                auxiliares = auxiliaresActualizarDetalle;
            }
        }

        grid.engine.nodes = GridStack.Utils.sort(grid.engine.nodes, 1);

        let jsonInputsAuxiliar = [];

        grid.engine.nodes.forEach(function (node) {

            let n = {
                x: node.x,
                y: node.y,
                width: node.width,
                height: node.height,
                id: node.id,
                visible: true,
                tipo: auxiliares.includes(node.id) ? 'auxiliar' : 'nativo',
                agregableAForm: true
            };

            if (auxiliares.includes(node.id)) {
                jsonInputsAuxiliar.push(n);
            } else {
                columnasGridNativas.push(n);
            }

        });

        for (let i = 0; i < tablaSeleccionada.columnas.length; i++) {

            var columna = tablaSeleccionada.columnas[i];

            for (let j = 0; j < columnasGridNativas.length; j++) {

                if (esParaGuardar) {
                    tablaSeleccionada.columnas[i].jsonInputGuardar.visible = false;
                    tablaSeleccionada.columnas[i].campoGuardable = false;
                } else {
                    tablaSeleccionada.columnas[i].jsonInputActualizar.visible = false;
                    tablaSeleccionada.columnas[i].visibleEnFormularioActualizar = false;
                }

                if (columnasGridNativas[j].id === columna.nombreColumna) {

                    if (esParaGuardar) {
                        tablaSeleccionada.columnas[i].jsonInputGuardar.visible = true;
                        tablaSeleccionada.columnas[i].campoGuardable = true;
                        tablaSeleccionada.columnas[i].jsonInputGuardar = columnasGridNativas[j];
                    } else {
                        tablaSeleccionada.columnas[i].jsonInputActualizar.visible = true;
                        tablaSeleccionada.columnas[i].visibleEnFormularioActualizar = true;
                        tablaSeleccionada.columnas[i].jsonInputActualizar = columnasGridNativas[j];
                    }

                    break;
                }

            }

        }

    }

    function aceptarConfigColumna(tipoTabla) {

        tablaSeleccionada = obtenerTablaSeleccionada(`combo-tabla-${tipoTabla}`);

        for (let j = 0; j < tablaSeleccionada.columnas.length; j++) {

            if (columnaSeleccionada === tablaSeleccionada.columnas[j].nombreColumna) {

                var confColumna = $(`#card-${tipoTabla}`).find(".conf-columna");

                //Formulario
                //Guardar
                var campoGuardable = confColumna.find(".checkCampoGuardable").prop('checked');
                var campoDeTextoGuardar = confColumna.find(".radioCampoDeTextoGuardar").prop('checked');
                var automaticoGuardar = confColumna.find(".radioAutomaticoGuardar").prop('checked');
                var conjuntoDeValoresGuardar = confColumna.find(".radioConjuntoDeValoresGuardar").prop('checked');
                var formulaGuardar = confColumna.find(".radioFormulaGuardar").prop('checked');

                var valorAutomaticoGuardar = confColumna.find(".valorAutomaticoGuardar").val();
                var valorConjuntoDeValoresGuardar = confColumna.find(".valorConjuntoDeValoresGuardar").val();
                var valorFormulaGuardar = confColumna.find(".valorFormulaGuardar").val();

                tablaSeleccionada.columnas[j].campoGuardable = campoGuardable;
                tablaSeleccionada.columnas[j].campoDeTextoGuardar = campoDeTextoGuardar;
                tablaSeleccionada.columnas[j].automaticoGuardar = automaticoGuardar;
                tablaSeleccionada.columnas[j].conjuntoDeValoresGuardar = conjuntoDeValoresGuardar;
                tablaSeleccionada.columnas[j].formulaGuardar = formulaGuardar;

                tablaSeleccionada.columnas[j].valorAutomaticoGuardar = valorAutomaticoGuardar;
                tablaSeleccionada.columnas[j].valorConjuntoDeValoresGuardar = valorConjuntoDeValoresGuardar;
                tablaSeleccionada.columnas[j].valorFormulaGuardar = valorFormulaGuardar;

                //Validaciones Guardar
                var requeridoGuardar = confColumna.find(".checkRequeridoGuardar").prop('checked');
                var longitudMinimaGuardar = confColumna.find(".checkLongitudMinimaGuardar").prop('checked');
                var valorLongitudMinimaGuardar = confColumna.find(".valorLongitudMinimaGuardar").val();
                var longitudMaximaGuardar = confColumna.find(".checkLongitudMaximaGuardar").prop('checked');
                var valorLongitudMaximaGuardar = confColumna.find(".valorLongitudMaximaGuardar").val();
                var unicoGuardar = confColumna.find(".checkUnicoGuardar").prop('checked');

                tablaSeleccionada.columnas[j].validacionesGuardar.requerido = requeridoGuardar;
                tablaSeleccionada.columnas[j].validacionesGuardar.longitudMinima = longitudMinimaGuardar;
                tablaSeleccionada.columnas[j].validacionesGuardar.valorLongitudMinima = parseInt(valorLongitudMinimaGuardar);
                tablaSeleccionada.columnas[j].validacionesGuardar.longitudMaxima = longitudMaximaGuardar;
                tablaSeleccionada.columnas[j].validacionesGuardar.valorLongitudMaxima = parseInt(valorLongitudMaximaGuardar);
                tablaSeleccionada.columnas[j].validacionesGuardar.unico = unicoGuardar;

                //Actualizar
                var visibleEnFormularioActualizar = confColumna.find(".checkVisibleActualizar").prop('checked');
                var campoActualizable = confColumna.find(".checkCampoActualizable").prop('checked');
                var campoDeTextoActualizar = confColumna.find(".radioCampoDeTextoActualizar").prop('checked');
                var automaticoActualizar = confColumna.find(".radioAutomaticoActualizar").prop('checked');
                var conjuntoDeValoresActualizar = confColumna.find(".radioConjuntoDeValoresActualizar").prop('checked');
                var formulaActualizar = confColumna.find(".radioFormulaActualizar").prop('checked');

                var valorAutomaticoActualizar = confColumna.find(".valorAutomaticoActualizar").val();
                var valorConjuntoDeValoresActualizar = confColumna.find(".valorConjuntoDeValoresActualizar").val();
                var valorFormulaActualizar = confColumna.find(".valorFormulaActualizar").val();

                tablaSeleccionada.columnas[j].visibleEnFormularioActualizar = visibleEnFormularioActualizar;
                tablaSeleccionada.columnas[j].campoActualizable = campoActualizable;
                tablaSeleccionada.columnas[j].campoDeTextoActualizar = campoDeTextoActualizar;
                tablaSeleccionada.columnas[j].automaticoActualizar = automaticoActualizar;
                tablaSeleccionada.columnas[j].conjuntoDeValoresActualizar = conjuntoDeValoresActualizar;
                tablaSeleccionada.columnas[j].formulaActualizar = formulaActualizar;

                tablaSeleccionada.columnas[j].valorAutomaticoActualizar = valorAutomaticoActualizar;
                tablaSeleccionada.columnas[j].valorConjuntoDeValoresActualizar = valorConjuntoDeValoresActualizar;
                tablaSeleccionada.columnas[j].valorFormulaActualizar = valorFormulaActualizar;

                //Validaciones Actualizar
                var requeridoActualizar = confColumna.find(".checkRequeridoActualizar").prop('checked');
                var longitudMinimaActualizar = confColumna.find(".checkLongitudMinimaActualizar").prop('checked');
                var valorLongitudMinimaActualizar = confColumna.find(".valorLongitudMinimaActualizar").val();
                var longitudMaximaActualizar = confColumna.find(".checkLongitudMaximaActualizar").prop('checked');
                var valorLongitudMaximaActualizar = confColumna.find(".valorLongitudMaximaActualizar").val();
                var unicoActualizar = confColumna.find(".checkUnicoActualizar").prop('checked');

                tablaSeleccionada.columnas[j].validacionesActualizar.requerido = requeridoActualizar;
                tablaSeleccionada.columnas[j].validacionesActualizar.longitudMinima = longitudMinimaActualizar;
                tablaSeleccionada.columnas[j].validacionesActualizar.valorLongitudMinima = parseInt(valorLongitudMinimaActualizar);
                tablaSeleccionada.columnas[j].validacionesActualizar.longitudMaxima = longitudMaximaActualizar;
                tablaSeleccionada.columnas[j].validacionesActualizar.valorLongitudMaxima = parseInt(valorLongitudMaximaActualizar);
                tablaSeleccionada.columnas[j].validacionesActualizar.unico = unicoActualizar;

                break;
            }
        }

    }

    function getDatos(esParaMd) {

        var tablasSeleccionadas = [];

        if (esParaMd) {
            establecerDatosBase('principal');
            tablaSeleccionada.tablaMaestro = null;
            tablaSeleccionada.tablaDetalle = $(`#combo-tabla-detalle option:selected`).text();
            tablaSeleccionada.tablaAuxiliar = $("#comboTablaAuxiliar option:selected").text();
            tablaSeleccionada.esMaestro = true;
            tablaSeleccionada.esDetalle = false;
            guardarConfiguracionesTablaHtml('principal');
            tablasSeleccionadas.push(tablaSeleccionada);

            establecerDatosBase('detalle');
            tablaSeleccionada.tablaMaestro = $(`#combo-tabla-principal option:selected`).text();
            tablaSeleccionada.tablaDetalle = null;
            tablaSeleccionada.tablaAuxiliar = null;
            tablaSeleccionada.esMaestro = false;
            tablaSeleccionada.esDetalle = true;
            guardarConfiguracionesTablaHtml('detalle');
            tablasSeleccionadas.push(tablaSeleccionada);

        } else {
            establecerDatosBase('principal');
            tablaSeleccionada.tablaMaestro = null;
            tablaSeleccionada.tablaDetalle = null;
            tablaSeleccionada.tablaAuxiliar = null;
            tablaSeleccionada.esMaestro = false;
            tablaSeleccionada.esDetalle = false;
            guardarConfiguracionesTablaHtml('principal');
            tablasSeleccionadas.push(tablaSeleccionada);
        }

        return JSON.stringify(tablasSeleccionadas);
    }

    function guardarBase(esParaMd) {

        $.ajax({
            type: 'POST',
            data: getDatos(esParaMd),
            url: '{{url('/generador/guardar')}}',
            beforeSend: function () {
                $('#spinnerModal').show();
            },
            success: function (data) {
                $('#spinnerModal').hide();
                obtenerTablas();
                mostrarMensajeExito('Guardado correctamente', 1000, null);
            },
            error: function (error) {
                console.log(error);
                $('#spinnerModal').hide();
                mostrarMensajeError('Error al guardar', 2000, null);
            }
        });

    }

    function obtenerTablaSeleccionada(nombreComboTabla) {

        for (let i = 0; i < tablas.length; i++) {

            var tablaSeleccionada = $(`#${nombreComboTabla} option:selected`).text();

            if (tablas[i].nombreTabla === tablaSeleccionada) {
                return tablas[i];
            }

        }
        return null;
    }

    function generarBase(esParaMd) {

        $.ajax({
            type: 'POST',
            data: getDatos(esParaMd),
            url: '{{url('/generador/guardar_y_generar')}}',
            beforeSend: function () {
                $('#spinnerModal').show();
            },
            success: function (data) {
                $('#spinnerModal').hide();
                mostrarMensajeExito('Generado correctamente', 1000, null);
            },
            error: function (error) {
                console.log(error);
                $('#spinnerModal').hide();
                mostrarMensajeError('Error al generar', 2000, null);
            }
        });
    }

    function guardarConfiguracionesTablaHtml(tipoTabla) {

        tablaSeleccionada = obtenerTablaSeleccionada(`combo-tabla-${tipoTabla}`);

        var card = $(`#card-${tipoTabla}`);

        var tableBody = card.find(".tablaColumnasTablaHtmlBody")[0];

        for (var i = 0, row; (row = tableBody.rows[i]); i++) {

            var nombreColumna = row.cells[0].innerText;

            for (let j = 0; j < tablaSeleccionada.columnas.length; j++) {

                var checkVisibleEnTabla = tableBody.getElementsByClassName("checkVisibleTabla");
                var checkVisibleEnReporte = tableBody.getElementsByClassName("checkVisibleReporte");

                if (tablaSeleccionada.columnas[j].nombreColumna === nombreColumna) {

                    var visibleEnTabla = checkVisibleEnTabla[i].checked;
                    var visibleEnReporte = checkVisibleEnReporte[i].checked;

                    tablaSeleccionada.columnas[j].visibleEnTabla = visibleEnTabla;
                    tablaSeleccionada.columnas[j].visibleEnReporte = visibleEnReporte;
                    tablaSeleccionada.columnas[j].ordenTablaHtml = i;
                    break;
                }
            }
        }

    }

</script>
