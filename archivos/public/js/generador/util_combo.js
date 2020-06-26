function seleccionarCombos(json, datosCombos, value) {

    var array = datosCombos.map((item) => {
        return item.nombreArray;
    });

    var key = datosCombos.map((item) => {
        return item.id;
    }).pop();

    var result = getPath(json, array, key, value);

    if (result.length === 0) {
        return;
    } else if (result.length === 1) {
        result = new Array(array.length).fill(0);
    }

    var padre = json;

    for (let i = 0; i < datosCombos.length; i++) {

        var nombreArray = datosCombos[i].nombreArray;
        var idCombo = datosCombos[i].idCombo;
        var id = datosCombos[i].id;
        var descripcion = datosCombos[i].descripcion;

        cargarCombo(padre, nombreArray, idCombo, id, descripcion);

        var idASeleccionar = padre[nombreArray][result[i]][id];
        $("#" + idCombo).val(idASeleccionar);

        padre = padre[nombreArray][result[i]];
    }

}

function getPath(object, [array, ...arrays], key, value, path = []) {
    object[array].some((o, i) => {
        if (o[key] === value || arrays.length && path.length < getPath(o, arrays, key, value, path).length) {
            return path.unshift(i);
        }
    });
    return path;
}

function agregarListenersOnChange(json, datosCombos) {

    function generate_handler(idCombo) {
        return function () {
            cargarCombos(json, datosCombos, idCombo);
        };
    }

    for (var i = 0; i < datosCombos.length; i++) {
        if (datosCombos.length - i > 1) {
            $("#" + datosCombos[i].idCombo).change(generate_handler(datosCombos[i + 1].idCombo));
        }
    }
}

function cargarCombos(json, datosCombos, idComboInicial) {

    var padreActual = json;

    var encontradoComboInicial = false;

    for (var i = 0; i < datosCombos.length; i++) {

        var datoCombo = datosCombos[i];

        if (!encontradoComboInicial && idComboInicial === datoCombo.idCombo) {
            encontradoComboInicial = true;
        }

        if (encontradoComboInicial) {
            cargarCombo(padreActual, datoCombo.nombreArray, datoCombo.idCombo, datoCombo.id, datoCombo.descripcion);
        }

        if (datosCombos.length - i > 1) {
            var listaPadre = padreActual[datoCombo.nombreArray];
            padreActual = obtenerSeleccionadoEnCombo(listaPadre, datoCombo.idCombo, datoCombo.descripcion);
        }

    }
}

/**
 *
 * @param arrayDelObjeto la lista de objeto
 * @param idCombo el id del combo
 * @param descripcion
 * @returns {*} el objeto seleccionado
 */
function obtenerSeleccionadoEnCombo(arrayDelObjeto, idCombo, descripcion) {

    for (let i = 0; i < arrayDelObjeto.length; i++) {
        if ($("#" + idCombo + " option:selected").text() === arrayDelObjeto[i][descripcion]) {
            return arrayDelObjeto[i];
        }
    }

    return null;
}

/**
 *
 * @param objetoPadre El objeto padre del combo
 * @param nombreArray el nombre de la tabla hija
 * @param idCombo
 * @param id el id del combo y a la vez el nombre del id
 * @param descripcion
 */
function cargarCombo(objetoPadre, nombreArray, idCombo, id, descripcion) {

    $('#' + idCombo).empty();

    if (objetoPadre === null) {
        return;
    }

    let childs = objetoPadre[nombreArray];

    for (let i = 0; i < childs.length; i++) {
        $("#" + idCombo).append($("<option />").val(childs[i][id]).text(childs[i][descripcion]));
    }
}
