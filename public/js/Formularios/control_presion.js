var base_url = $("#txt_base_url").val();
var dt_presiones;

// Filtro por rango de fechas aplicado a la columna "Fecha" (Índice 1)
$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'grid_presiones') {
            return true;
        }

        var fDesde = $("#f_desde").val();
        var fHasta = $("#f_hasta").val();

        if (!fDesde && !fHasta) {
            return true;
        }

        var fechaColumnaRaw = data[1] || "";
        if (!fechaColumnaRaw) return false;

        var fechaColumna = "";
        if (fechaColumnaRaw.includes('/')) {
            var partes = fechaColumnaRaw.split('/');
            fechaColumna = `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
        } else {
            fechaColumna = fechaColumnaRaw;
        }

        if (fDesde && fechaColumna < fDesde) return false;
        if (fHasta && fechaColumna > fHasta) return false;

        return true;
    }
);

function listar_presiones() {
    dt_presiones = $("#grid_presiones").DataTable({
        destroy: true,
        dom: 'Bfrtip',
        ajax: {
            url: base_url + "/Formularios/Ctrl_control_presion/datatable_presiones",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { data: "id" },
            { data: "fecha_medicion" },
            { data: "punto_medicion" },
            { data: "tipo_punto" },
            { data: "valor_medido" },
            { data: "observacion" },
            {
                data: null,
                orderable: false,
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
                className: 'btn btn-success btn-sm mr-1',
                title: "Informe Control de Presiones",
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger btn-sm mr-1',
                title: "Informe Control de Presiones",
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info btn-sm',
                title: "Informe Control de Presiones",
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            }
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información disponible",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(Filtrado de _MAX_ registros totales)",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron coincidencias",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
    });
}

function limpiar_form() {
    $("#id_presion").val("");
    $("#id_display").val("");
    $("#form_presion")[0].reset();

    var hoy = new Date().toISOString().split('T')[0];
    $("#fecha_medicion").val(hoy);
}

function convertirFechaParaInput(fechaDDMMYYYY) {
    if (!fechaDDMMYYYY) return "";
    var partes = fechaDDMMYYYY.split('/');
    if (partes.length === 3) {
        return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
    }
    return fechaDDMMYYYY;
}

function guardar_presion() {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_control_presion/guardar_presion",
        type: "POST",
        data: $("#form_presion").serialize(),
        success: function (respuesta) {
            if (respuesta == 1) {
                Swal.fire("Éxito", "Medición registrada correctamente", "success");
                limpiar_form();
                dt_presiones.ajax.reload(null, false);
            } else {
                Swal.fire("Error", respuesta, "error");
            }
        }
    });
}

$(document).ready(function () {
    limpiar_form();
    listar_presiones();

    // Filtros por fecha
    $("#btn_filtrar_fechas").on("click", function () {
        dt_presiones.draw();
    });

    $("#btn_limpiar_fechas").on("click", function () {
        $("#f_desde").val("");
        $("#f_hasta").val("");
        dt_presiones.draw();
    });

    // Guardar
    $("#btn_guardar").on("click", function () {
        if ($("#form_presion")[0].checkValidity()) {
            guardar_presion();
        } else {
            $("#form_presion")[0].reportValidity();
        }
    });

    // Limpiar
    $("#btn_limpiar").on("click", function () {
        limpiar_form();
    });

    // Editar
    $("#grid_presiones tbody").on("click", ".btn-editar", function () {
        var row = $(this).closest("tr");
        var data = dt_presiones.row(row).data();

        if (!data) return;

        $("#id_presion").val(data.id);
        $("#id_display").val(data.id);
        $("#fecha_medicion").val(convertirFechaParaInput(data.fecha_medicion));
        $("#punto_medicion").val(data.punto_medicion);
        $("#tipo_punto").val(data.tipo_punto);
        $("#valor_medido").val(data.valor_medido);
        $("#observacion").val(data.observacion);

        $('html, body').animate({
            scrollTop: $("#form_presion").offset().top - 80
        }, 500);
    });

    // Eliminar
    $("#grid_presiones tbody").on("click", ".btn-eliminar", function () {
        var row = $(this).closest("tr");
        var data = dt_presiones.row(row).data();

        if (!data) return;

        Swal.fire({
            title: "¿Eliminar registro?",
            text: "N° Registro: " + data.id,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: base_url + "/Formularios/Ctrl_control_presion/eliminar_presion",
                    type: "POST",
                    data: { id: data.id },
                    success: function (resp) {
                        if (resp == 1) {
                            Swal.fire("Eliminado", "Registro eliminado correctamente", "success");
                            limpiar_form();
                            dt_presiones.ajax.reload(null, false);
                        } else {
                            Swal.fire("Error", resp, "error");
                        }
                    }
                });
            }
        });
    });
});