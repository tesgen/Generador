function mostrarMensajeExito(mensaje, tiempo, redireccion) {
    mostrarMensaje('success', mensaje, tiempo, redireccion);
}

function mostrarMensajeError(mensaje, tiempo, redireccion) {
    mostrarMensaje('error', mensaje, tiempo, redireccion);
}

function mostrarMensajeAdvertencia(mensaje, tiempo, redireccion) {
    mostrarMensaje('warning', mensaje, tiempo, redireccion);
}

function mostrarMensaje(tipo, mensaje, tiempo, redireccion) {
    $('#spinnerModal').hide();
    swal({
        title: mensaje,
        timer: tiempo,
        icon: tipo,
        button: "Ok",
    }).then(
        function () {
            if (redireccion !== null) {
                window.location.replace(redireccion);
            }
        },
    );
}

function confirmarEliminar(e) {
    e.preventDefault();
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
            $(e.target).closest('form').submit();
        }
    });
}
