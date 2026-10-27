var base_url = $("#txt_base_url").val();
var dt_obstrucciones;

// Filtro por rango de fechas aplicado a la columna "Fecha Inicio" (Índice 1)
$.fn.dataTable.ext.search.push(
    function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'grid_obstrucciones') {
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

function calcularDuracion() {
    var fInicio = $("#fecha_inicio").val();
    var hInicio = $("#hora_inicio").val();
    var fTermino = $("#fecha_termino").val();
    var hTermino = $("#hora_termino").val();

    if (fInicio && hInicio && fTermino && hTermino) {
        var start = new Date(`${fInicio}T${hInicio}`);
        var end = new Date(`${fTermino}T${hTermino}`);

        if (end >= start) {
            var diffMs = end - start;
            var diffMins = Math.floor(diffMs / 60000);
            var hrs = Math.floor(diffMins / 60);
            var mins = diffMins % 60;

            var strHrs = hrs < 10 ? '0' + hrs : hrs;
            var strMins = mins < 10 ? '0' + mins : mins;

            $("#duracion").val(`${strHrs}:${strMins}`);
        } else {
            $("#duracion").val("");
        }
    } else {
        $("#duracion").val("");
    }
}

function listar_obstrucciones() {
    dt_obstrucciones = $("#grid_obstrucciones").DataTable({
        destroy: true,
        dom: 'Bfrtip',
        ajax: {
            url: base_url + "/Formularios/Ctrl_obstruccion/datatable_obstrucciones",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { data: "id" },
            { data: "fecha_inicio" },
            { data: "hora_inicio" },
            { data: "fecha_termino" },
            { data: "hora_termino" },
            { data: "duracion" },
            { data: "cant_clientes_afectados" },
            { data: "afloramiento" },
            { data: "tipo_infraestructura" },
            { data: "curso_agua" },
            { data: "diametro_colector" },
            { data: "material_colector" },
            { data: "motivo_obstruccion" },
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
                title: "Informe de Obstrucciones Alcantarillado",
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger btn-sm mr-1',
                title: "Informe de Obstrucciones Alcantarillado",
                orientation: 'landscape',
                pageSize: 'TABLOID',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info btn-sm',
                title: "Informe de Obstrucciones Alcantarillado",
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] }
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
    $("#id_obstruccion").val("");
    $("#id_display").val("");
    $("#form_obstruccion")[0].reset();

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

function guardar_obstruccion() {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_obstruccion/guardar_obstruccion",
        type: "POST",
        data: $("#form_obstruccion").serialize(),
        success: function (respuesta) {
            if (respuesta == 1) {
                Swal.fire("Éxito", "Registro guardado correctamente", "success");
                limpiar_form();
                dt_obstrucciones.ajax.reload(null, false);
            } else {
                Swal.fire("Error", respuesta, "error");
            }
        }
    });
}

$(document).ready(function () {
    limpiar_form();
    listar_obstrucciones();

    // Eventos para cálculo automático de duración
    $("#fecha_inicio, #hora_inicio, #fecha_termino, #hora_termino").on("change keyup", function () {
        calcularDuracion();
    });

    // Filtros de fecha
    $("#btn_filtrar_fechas").on("click", function () {
        dt_obstrucciones.draw();
    });

    $("#btn_limpiar_fechas").on("click", function () {
        $("#f_desde").val("");
        $("#f_hasta").val("");
        dt_obstrucciones.draw();
    });

    // Guardar
    $("#btn_guardar").on("click", function () {
        if ($("#form_obstruccion")[0].checkValidity()) {
            guardar_obstruccion();
        } else {
            $("#form_obstruccion")[0].reportValidity();
        }
    });

    // Limpiar
    $("#btn_limpiar").on("click", function () {
        limpiar_form();
    });

    // Editar
    $("#grid_obstrucciones tbody").on("click", ".btn-editar", function () {
        var row = $(this).closest("tr");
        var data = dt_obstrucciones.row(row).data();

        if (!data) return;

        $("#id_obstruccion").val(data.id);
        $("#id_display").val(data.id);
        $("#fecha_inicio").val(convertirFechaParaInput(data.fecha_inicio));
        $("#hora_inicio").val(data.hora_inicio);
        $("#fecha_termino").val(convertirFechaParaInput(data.fecha_termino));
        $("#hora_termino").val(data.hora_termino);
        $("#duracion").val(data.duracion);
        $("#motivo_obstruccion").val(data.motivo_obstruccion);
        $("#cant_clientes_afectados").val(data.cant_clientes_afectados);
        $("#ubicacion_falla").val(data.ubicacion_falla);
        $("#afloramiento").val(data.afloramiento);
        $("#tipo_infraestructura").val(data.tipo_infraestructura);
        $("#curso_agua").val(data.curso_agua);
        $("#diametro_colector").val(data.diametro_colector);
        $("#material_colector").val(data.material_colector);

        $('html, body').animate({
            scrollTop: $("#form_obstruccion").offset().top - 80
        }, 500);
    });

    // Eliminar
    $("#grid_obstrucciones tbody").on("click", ".btn-eliminar", function () {
        var row = $(this).closest("tr");
        var data = dt_obstrucciones.row(row).data();

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
                    url: base_url + "/Formularios/Ctrl_obstruccion/eliminar_obstruccion",
                    type: "POST",
                    data: { id: data.id },
                    success: function (resp) {
                        if (resp == 1) {
                            Swal.fire("Eliminado", "Registro eliminado correctamente", "success");
                            limpiar_form();
                            dt_obstrucciones.ajax.reload(null, false);
                        } else {
                            Swal.fire("Error", resp, "error");
                        }
                    }
                });
            }
        });
    });
});