var base_url = $("#txt_base_url").val();
var dt_atenciones;
var dt_socios_modal;

// Registrador del filtro personalizado por rango de fechas para DataTables
$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        // Solo aplica este filtro a la tabla 'grid_atenciones'
        if (settings.nTable.id !== 'grid_atenciones') {
            return true;
        }

        var fDesde = $("#f_desde").val(); // Formato YYYY-MM-DD
        var fHasta = $("#f_hasta").val(); // Formato YYYY-MM-DD

        // Obtiene el valor de la columna 'fecha_atencion' (Índice 1 en la tabla)
        var fechaColumnaRaw = data[1] || "";
        if (!fechaColumnaRaw) return true;

        // Normalizar fecha de la tabla a formato ejecutable YYYY-MM-DD
        var fechaColumna = "";
        if (fechaColumnaRaw.includes('/')) {
            var partes = fechaColumnaRaw.split('/'); // Si viene como DD/MM/YYYY
            fechaColumna = `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
        } else {
            fechaColumna = fechaColumnaRaw; // Si ya viene como YYYY-MM-DD
        }

        if (
            (fDesde === "" && fHasta === "") ||
            (fDesde === "" && fechaColumna <= fHasta) ||
            (fDesde <= fechaColumna && fHasta === "") ||
            (fDesde <= fechaColumna && fechaColumna <= fHasta)
        ) {
            return true;
        }
        return false;
    }
);

function obtener_siguiente_atencion() {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_atenciones/obtener_siguiente_atencion",
        type: "GET",
        success: function (numero) {
            $("#numero_atencion").val(numero);
        }
    });
}

function listar_atenciones() {
    dt_atenciones = $("#grid_atenciones").DataTable({
        destroy: true,
        async: false,
        dom: 'Bfrtip',
        ajax: {
            url: base_url + "/Formularios/Ctrl_atenciones/datatable_atenciones",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { data: "numero_atencion" },
            { data: "fecha_atencion" },
            { data: "numero_contrato" },
            { data: "nombre_usuario" },
            { data: "atencion_requerida" },
            { data: "fecha_respuesta" },
            { data: "respuesta_ssr" },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-warning btn-sm btn-editar" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-eliminar" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Atenciones",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Excluye la columna de botones (Acciones)
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Atenciones",
                orientation: 'landscape',
                pageSize: 'TABLOID',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Atenciones",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
    });

    dt_atenciones.buttons().container().appendTo('#grid_atenciones_wrapper .col-md-6:eq(0)');
}

function listar_socios_modal() {
    dt_socios_modal = $("#grid_socios_modal").DataTable({
        destroy: true,
        ajax: {
            url: base_url + "/Formularios/Ctrl_atenciones/datatable_socios",
            type: "POST",
            dataSrc: "data"
        },
        columns: [
            { data: "rut" },
            { data: "rol" },
            { data: "nombre_completo" },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-success btn-sm btn-seleccionar-socio" type="button">
                            <i class="fas fa-check"></i> Seleccionar
                        </button>
                    `;
                }
            }
        ]
    });
}

function limpiar_form() {
    $("#id_atencion").val("");
    $("#id_socio").val("");
    $("#form_atencion")[0].reset();

    var hoy = new Date().toISOString().split('T')[0];
    $("#fecha_atencion").val(hoy);

    obtener_siguiente_atencion();
}

function guardar_atencion() {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_atenciones/guardar_atencion",
        type: "POST",
        data: $("#form_atencion").serialize(),
        success: function (respuesta) {
            if (respuesta == 1) {
                Swal.fire("Éxito", "Atención guardada correctamente", "success");
                limpiar_form();
                dt_atenciones.ajax.reload();
            } else {
                Swal.fire("Error", respuesta, "error");
            }
        }
    });
}

$(document).ready(function () {
    limpiar_form();
    listar_atenciones();

    // Eventos para el Filtro por Fechas
    $("#btn_filtrar_fechas").on("click", function () {
        dt_atenciones.draw(); // Redibuja la tabla ejecutando el $.fn.dataTable.ext.search
    });

    $("#btn_limpiar_fechas").on("click", function () {
        $("#f_desde").val("");
        $("#f_hasta").val("");
        dt_atenciones.draw(); // Redibuja mostrando todas las filas
    });

    // Abrir Modal
    $("#btn_buscar_socio").on("click", function () {
        $("#modal_buscar_socio").modal("show");
        listar_socios_modal();
    });

    // Seleccionar socio desde Modal
    $("#grid_socios_modal tbody").on("click", ".btn-seleccionar-socio", function () {
        var data = dt_socios_modal.row($(this).parents("tr")).data();

        $("#id_socio").val(data.id_socio);
        $("#numero_contrato").val(data.rol);
        $("#nombre_usuario").val(data.nombre_completo);

        $("#modal_buscar_socio").modal("hide");
    });

    // Guardar
    $("#btn_guardar").on("click", function () {
        if ($("#form_atencion")[0].checkValidity()) {
            guardar_atencion();
        } else {
            $("#form_atencion")[0].reportValidity();
        }
    });

    // Limpiar
    $("#btn_limpiar").on("click", function () {
        limpiar_form();
    });

    // Editar
    $("#grid_atenciones tbody").on("click", ".btn-editar", function () {
        var data = dt_atenciones.row($(this).parents("tr")).data();

        $("#id_atencion").val(data.id);
        $("#id_socio").val(data.id_socio);
        $("#numero_atencion").val(data.numero_atencion);
        $("#fecha_atencion").val(data.fecha_atencion.split('/').reverse().join('-'));
        $("#numero_contrato").val(data.numero_contrato);
        $("#nombre_usuario").val(data.nombre_usuario);
        $("#atencion_requerida").val(data.atencion_requerida);
        $("#respuesta_ssr").val(data.respuesta_ssr);

        if (data.fecha_respuesta) {
            $("#fecha_respuesta").val(data.fecha_respuesta.split('/').reverse().join('-'));
        } else {
            $("#fecha_respuesta").val("");
        }
    });

    // Eliminar
    $("#grid_atenciones tbody").on("click", ".btn-eliminar", function () {
        var data = dt_atenciones.row($(this).parents("tr")).data();

        Swal.fire({
            title: "¿Eliminar atención?",
            text: "N° Atención: " + data.numero_atencion,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url + "/Formularios/Ctrl_atenciones/eliminar_atencion",
                    type: "POST",
                    data: { id: data.id },
                    success: function (resp) {
                        if (resp == 1) {
                            Swal.fire("Eliminado", "Atención eliminada", "success");
                            limpiar_form();
                            dt_atenciones.ajax.reload();
                        } else {
                            Swal.fire("Error", resp, "error");
                        }
                    }
                });
            }
        });
    });
});