const idioma =
    {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copyTitle": 'Informacion copiada',
            "copyKeys": 'Use your keyboard or menu to select the copy command',
            "copySuccess": {
                "_": '%d filas copiadas al portapapeles',
                "1": '1 fila copiada al portapapeles'
            },

            "pageLength": {
                "_": "Mostrar %d filas",
                "-1": "Mostrar Todo"
            }
        }
    };

$(document).ready(function () {

    const lengthMenu = [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Mostrar Todo"]];

    $('#tabla').DataTable({

        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "language": idioma,
        "lengthMenu": lengthMenu,
        dom: 'Bfrt<"col-md-6 inline"i> <"col-md-6 inline"p>',
        "order": [],
        buttons: {

            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    title: typeof tituloReporte === 'undefined' ? 'Lista' : tituloReporte,
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-danger btn-app export pdf',
                    exportOptions: {
                        columns: typeof columnasVisiblesReporte === 'undefined' ? [0, 1, 2] : columnasVisiblesReporte
                    },
                    customize: function (doc) {

                        doc.styles.title = {
                            color: '#4c8aa0',
                            fontSize: '30',
                            alignment: 'center'
                        };
                        doc.styles['td:nth-child(2)'] = {
                            width: '100px',
                            'max-width': '100px'
                        };
                        doc.styles.tableHeader = {
                            fillColor: '#4c8aa0',
                            color: 'white',
                            alignment: 'center'
                        };
                        doc.content[1].margin = [100, 0, 100, 0];

                    }

                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    title: tituloReporte,
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success btn-app export excel',
                    exportOptions: {
                        columns: typeof columnasVisiblesReporte === 'undefined' ? [0, 1, 2] : columnasVisiblesReporte
                    },
                },
                {
                    extend: 'pageLength',
                    titleAttr: 'Registros a mostrar',
                    className: 'selectTable btn-primary'
                }
            ], dom: {
                container: {
                    tag: 'div',
                    className: 'flexcontent'
                },
                buttonLiner: {
                    tag: null
                }
            },

        }

    });

    $(".flexcontent").appendTo("#tabla_filter");
    $("#tabla_filter").addClass("row");
    $("#tabla_filter > label").addClass("col-md-6");
    $(".flexcontent").addClass("col-md-6");

    // $(".dataTables_wrapper").prepend("<div class='' id='contenedor-filtro'></div>");
    // $(".dataTables_filter > label").appendTo(".dataTables_filter");
    // $(".flexcontent").appendTo(".dataTables_filter");
    // $(".dataTables_filter").appendTo("#contenedor-filtro");
    // $(".dataTables_filter").addClass("form-inline");
    // $(".dataTables_filter > label").addClass("col-md-6");
    // $(".flexcontent").addClass("col-md-6");


});
