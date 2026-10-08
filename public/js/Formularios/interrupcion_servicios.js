var base_url = $("#txt_base_url").val();
var dt_interrupciones;

// Filtro por rango de fechas aplicado a la columna "Fecha Inicio" (Índice 3)
$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'grid_interrupciones') {
            return true;
        }

        var fDesde = $("#f_desde").val();
        var fHasta = $("#f_hasta").val();

        var fechaColumnaRaw = data[3] || "";
        if (!fechaColumnaRaw) return true;

        var fechaColumna = "";
        if (fechaColumnaRaw.includes('/')) {
            var partes = fechaColumnaRaw.split('/');
            fechaColumna = `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
        } else {
            fechaColumna = fechaColumnaRaw;
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

function listar_interrupciones() {
    dt_interrupciones = $("#grid_interrupciones").DataTable({
        destroy: true,
        dom: 'Bfrtip',
        ajax: {
            url: base_url + "/Formularios/Ctrl_interrupcion_servicios/datatable_interrupciones",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { data: "id" },
            { data: "tipo_interrupcion" },
            { data: "nivel_afectacion" },
            { data: "fecha_inicio" },
            { data: "hora_inicio" },
            { data: "fecha_termino" },
            { data: "hora_termino" },
            { data: "cant_usuarios_afectados" },
            { data: "diametro_matriz" },
            { data: "material_matriz" },
            { data: "motivo_interrupcion" },
            { data: "ubicacion_falla" },
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
                title: "Informe de Interrupción de Servicios",
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger btn-sm mr-1',
                title: "Informe de Interrupción de Servicios",
                orientation: 'landscape',
                pageSize: 'TABLOID',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info btn-sm',
                title: "Informe de Interrupción de Servicios",
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
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
    $("#id_interrupcion").val("");
    $("#id_display").val("");
    $("#form_interrupcion")[0].reset();

    var hoy = new Date().toISOString().split('T')[0];
    $("#fecha_inicio").val(hoy);
}

function convertirFechaParaInput(fechaDDMMYYYY) {
    if (!fechaDDMMYYYY) return "";
    var partes = fechaDDMMYYYY.split('/');
    if (partes.length === 3) {
        return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
    }
    return fechaDDMMYYYY;
}

function guardar_interrupcion() {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_interrupcion_servicios/guardar_interrupcion",
        type: "POST",
        data: $("#form_interrupcion").serialize(),
        success: function (respuesta) {
            if (respuesta == 1) {
                Swal.fire("Éxito", "Registro guardado correctamente", "success");
                limpiar_form();
                dt_interrupciones.ajax.reload(null, false);
            } else {
                Swal.fire("Error", respuesta, "error");
            }
        }
    });
}

$(document).ready(function () {
    limpiar_form();
    listar_interrupciones();

    // Filtro Fechas
    $("#btn_filtrar_fechas").on("click", function () {
        dt_interrupciones.draw();
    });

    $("#btn_limpiar_fechas").on("click", function () {
        $("#f_desde").val("");
        $("#f_hasta").val("");
        dt_interrupciones.draw();
    });

    // Guardar
    $("#btn_guardar").on("click", function () {
        if ($("#form_interrupcion")[0].checkValidity()) {
            guardar_interrupcion();
        } else {
            $("#form_interrupcion")[0].reportValidity();
        }
    });

    // Limpiar
    $("#btn_limpiar").on("click", function () {
        limpiar_form();
    });

    // Evento Editar
    $("#grid_interrupciones tbody").on("click", ".btn-editar", function () {
        var row = $(this).closest("tr");
        var data = dt_interrupciones.row(row).data();

        if (!data) return;

        $("#id_interrupcion").val(data.id);
        $("#id_display").val(data.id);
        $("#tipo_interrupcion").val(data.tipo_interrupcion);
        $("#nivel_afectacion").val(data.nivel_afectacion);

        $("#fecha_inicio").val(convertirFechaParaInput(data.fecha_inicio));
        $("#hora_inicio").val(data.hora_inicio);

        $("#fecha_termino").val(convertirFechaParaInput(data.fecha_termino));
        $("#hora_termino").val(data.hora_termino);

        $("#motivo_interrupcion").val(data.motivo_interrupcion);
        $("#cant_usuarios_afectados").val(data.cant_usuarios_afectados);
        $("#ubicacion_falla").val(data.ubicacion_falla);
        $("#diametro_matriz").val(data.diametro_matriz);
        $("#material_matriz").val(data.material_matriz);

        // Desplazamiento suave al formulario
        $('html, body').animate({
            scrollTop: $("#form_interrupcion").offset().top - 80
        }, 500);
    });

    // Evento Eliminar
    $("#grid_interrupciones tbody").on("click", ".btn-eliminar", function () {
        var row = $(this).closest("tr");
        var data = dt_interrupciones.row(row).data();

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
                    url: base_url + "/Formularios/Ctrl_interrupcion_servicios/eliminar_interrupcion",
                    type: "POST",
                    data: { id: data.id },
                    success: function (resp) {
                        if (resp == 1) {
                            Swal.fire("Eliminado", "Registro eliminado correctamente", "success");
                            limpiar_form();
                            dt_interrupciones.ajax.reload(null, false);
                        } else {
                            Swal.fire("Error", resp, "error");
                        }
                    }
                });
            }
        });
    });
});