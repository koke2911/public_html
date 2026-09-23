var grid_deudores;

$(document).ready(function () {
    cargar_grid();

    $("#cmb_meses_mora, #cmb_tipo_criterio").change(function () {
        grid_deudores.ajax.reload();
    });

    $("#btn_exportar_pdf").click(function () {
        var n_meses = $("#cmb_meses_mora").val();
        var tipo_criterio = $("#cmb_tipo_criterio").val();

        var url = base_url + "/Informes/Ctrl_deudores/exportar_pdf/" + n_meses + "/" + tipo_criterio;

        window.open(url, '_blank');
    });

    
});

function cargar_grid() {
    grid_deudores = $("#grid_deudores").DataTable({
        "destroy": true,
        "pageLength": 10,
        "ajax": {
            "url": base_url + "/Informes/Ctrl_deudores/datatable_informe_afecto_corte",
            "type": "POST",
            "data": function (d) {
                d.n_meses = $("#cmb_meses_mora").val();
                d.tipo_criterio = $("#cmb_tipo_criterio").val();
            },
            "dataSrc": function (json) {
                calcular_metricas(json.data);
                return json.data;
            }
        },
        "columns": [
            { "data": "id_socio" },
            { "data": "rol_socio" },
            { "data": "rut" },
            { "data": "nombre_socio" },
            {
                "data": "meses_pendientes",
                "className": "text-center",
                "render": function (data) {
                    return '<span class="badge badge-danger font-weight-bold" style="font-size:0.9em;">' + data + ' Meses</span>';
                }
            },
            {
                "data": "total_deuda",
                "className": "text-right font-weight-bold text-danger",
                "render": function (data) {
                    return "$ " + parseInt(data || 0).toLocaleString("de-DE");
                }
            },
            {
                "data": null,
                "defaultContent": '<button type="button" class="btn btn-sm btn-info btn_ver_detalle" title="Ver Detalle"><i class="fas fa-eye"></i></button>',
                "className": "text-center"
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });
}

function calcular_metricas(data) {
    var cant_deudores = data.length;
    var total_adeudado = 0;

    $.each(data, function (index, item) {
        total_adeudado += parseFloat(item.total_deuda || 0);
    });

    var promedio = cant_deudores > 0 ? (total_adeudado / cant_deudores) : 0;

    $("#lbl_cant_deudores").text(cant_deudores.toLocaleString("de-DE"));
    $("#lbl_total_adeudado").text("$ " + Math.round(total_adeudado).toLocaleString("de-DE"));
    $("#lbl_promedio_deuda").text("$ " + Math.round(promedio).toLocaleString("de-DE"));
}

var grid_detalle;

// Abrir Modal de Detalle
$("#grid_deudores tbody").on("click", ".btn_ver_detalle", function () {
    var data = grid_deudores.row($(this).closest("tr")).data();

    $("#lbl_detalle_socio").text("Socio: " + data.nombre_socio + " (Rol: " + data.rol_socio + ")");
    $("#lbl_detalle_rut").text("RUT: " + data.rut);
    $("#lbl_detalle_total").text("$ " + parseInt(data.total_deuda || 0).toLocaleString("de-DE"));

    cargar_grid_detalle(data.id_socio);
    $("#dlg_detalle_deuda").modal("show");
});

function cargar_grid_detalle(id_socio) {
    grid_detalle = $("#grid_detalle_deuda").DataTable({
        "destroy": true,
        "pageLength": 10,
        "searching": false,
        "lengthChange": false,
        "ajax": {
            "url": base_url + "/Informes/Ctrl_deudores/datatable_deuda_socio",
            "type": "POST",
            "data": { "id_socio": id_socio },
            "dataSrc": function (json) {
                return json.data || [];
            }
        },
        "columns": [
            { "data": "id_metros" },
            {
                "data": "folio_bolect",
                "render": function (data) {
                    return '<span class="badge badge-light font-weight-bold">' + data + '</span>';
                }
            },
            { "data": "fecha_vencimiento" },
            {
                "data": "metros",
                "className": "text-right",
                "render": function (data) {
                    return (data || 0) + " m³";
                }
            },
            {
                "data": "deuda",
                "className": "text-right font-weight-bold text-danger",
                "render": function (data) {
                    return "$ " + parseInt(data || 0).toLocaleString("de-DE");
                }
            },
            {
                "data": "url_boleta",
                "className": "text-center",
                "render": function (data) {
                    if (data && data !== '') {
                        return '<a href="' + data + '" target="_blank" class="btn btn-sm btn-outline-danger" title="Ver Boleta Digital"><i class="fas fa-file-pdf"></i></a>';
                    } else {
                        return '<span class="text-muted"><i class="fas fa-times-circle"></i></span>';
                    }
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });

   
}