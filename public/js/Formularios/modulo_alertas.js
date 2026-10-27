var base_url = $("#txt_base_url").val();
var dt_alertas_detalle = null;
var datosAlertasGlobal = null;

function cargarNotificacionesAlertas(callback) {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_alertas/obtener_alertas_indicadores",
        type: "GET",
        dataType: "json",
        success: function (resp) {
            if (!resp || resp.error) {
                console.error("Error al cargar alertas:", resp);
                return;
            }

            datosAlertasGlobal = resp;
            var c = resp.contadores;

            // Actualizar contadores del Navbar
            $("#lbl_total_notificaciones").text(c.total_general);
            $("#txt_alerta_morosos").text(`Hay ${c.total_morosos} lecturas vencidas`);
            $("#txt_alerta_vencen").text(`${c.total_vencen} lecturas vencen hoy`);
            $("#txt_alerta_anomalos").text(`Existen ${c.total_anomalos} consumos anómalos`);

            // Actualizar KPI Cards en el Modal
            $("#kpi_morosos").text(c.total_morosos);
            $("#kpi_vencen").text(c.total_vencen);
            $("#kpi_anomalos").text(c.total_anomalos);

            if (typeof callback === "function") {
                callback();
            }
        },
        error: function (xhr, status, error) {
            console.error("Error AJAX en Alertas:", error);
        }
    });
}

function inicializarTablaModal(dataset) {
    dataset = dataset || [];

    if ($.fn.DataTable.isDataTable('#grid_alertas_detalle')) {
        $('#grid_alertas_detalle').DataTable().clear().destroy();
    }

    dt_alertas_detalle = $("#grid_alertas_detalle").DataTable({
        destroy: true,
        data: dataset,
        columns: [
            { data: "nombre", defaultContent: "Sin Nombre" },
            { data: "ds", defaultContent: "-" },
            { data: "fecha_vencimiento", defaultContent: "N/A" },
            {
                data: "y",
                defaultContent: "0",
                render: function (data) {
                    return `<strong>${data} m³</strong>`;
                }
            },
            {
                data: "total",
                defaultContent: "0",
                render: function (data) {
                    var val = parseInt(data || 0);
                    return `$ ${val.toLocaleString('es-CL')}`;
                }
            },
            { data: "tipo_dte", defaultContent: "BOLETA EXENTA" },
            {
                data: "estado",
                defaultContent: "PENDIENTE",
                render: function (data) {
                    return `<span class="badge badge-warning">${data}</span>`;
                }
            }
        ],
        language: {
            "emptyTable": "No hay registros en esta categoría",
            "search": "Buscar:",
            "paginate": { "next": "Sig.", "previous": "Ant." }
        }
    });
}

function filtrarTablaModal(tipo) {
    if (!datosAlertasGlobal) {
        cargarNotificacionesAlertas(function () {
            filtrarTablaModal(tipo);
        });
        return;
    }

    var listaData = [];
    var d = datosAlertasGlobal.detalles;

    if (tipo === 'morosos') {
        listaData = d.morosos || [];
    } else if (tipo === 'vencen_hoy') {
        listaData = d.vencen_hoy || [];
    } else if (tipo === 'anomalos') {
        listaData = d.consumos_anomalos || [];
    } else {
        listaData = [
            ...(d.morosos || []),
            ...(d.vencen_hoy || []),
            ...(d.consumos_anomalos || [])
        ];
    }

    inicializarTablaModal(listaData);
}

$(document).ready(function () {
    // Carga inicial
    cargarNotificacionesAlertas();

    // Evento Clic en Notificaciones
    $(document).on("click", ".btn-ver-detalle", function (e) {
        e.preventDefault();

        var tipo = $(this).attr("data-tipo") || 'morosos';

        $("#modal_modulo_alertas").modal("show");

        if (tipo === 'vencen_hoy') {
            $('#btn_tab_vencen').tab('show');
        } else if (tipo === 'anomalos') {
            $('#btn_tab_anomalos').tab('show');
        } else {
            $('#btn_tab_morosos').tab('show');
        }

        filtrarTablaModal(tipo);
    });
}); var base_url = $("#txt_base_url").val();
var dt_alertas_detalle = null;
var datosAlertasGlobal = null;

function cargarNotificacionesAlertas(callback) {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_alertas/obtener_alertas_indicadores",
        type: "GET",
        dataType: "json",
        success: function (resp) {
            if (!resp || resp.error) {
                console.error("Error al cargar alertas:", resp);
                return;
            }

            datosAlertasGlobal = resp;
            var c = resp.contadores;

            // Actualizar contadores del Navbar
            $("#lbl_total_notificaciones").text(c.total_general);
            $("#txt_alerta_morosos").text(`Hay ${c.total_morosos} lecturas vencidas`);
            $("#txt_alerta_vencen").text(`${c.total_vencen} lecturas vencen hoy`);
            $("#txt_alerta_anomalos").text(`Existen ${c.total_anomalos} consumos anómalos`);

            // Actualizar KPI Cards en el Modal
            $("#kpi_morosos").text(c.total_morosos);
            $("#kpi_vencen").text(c.total_vencen);
            $("#kpi_anomalos").text(c.total_anomalos);

            if (typeof callback === "function") {
                callback();
            }
        },
        error: function (xhr, status, error) {
            console.error("Error AJAX en Alertas:", error);
        }
    });
}

function inicializarTablaModal(dataset) {
    dataset = dataset || [];

    if ($.fn.DataTable.isDataTable('#grid_alertas_detalle')) {
        $('#grid_alertas_detalle').DataTable().clear().destroy();
    }

    dt_alertas_detalle = $("#grid_alertas_detalle").DataTable({
        destroy: true,
        data: dataset,
        columns: [
            { data: "nombre", defaultContent: "Sin Nombre" },
            { data: "mes_consumo", defaultContent: "-" }, // Muestra "Octubre 2026", etc.
            { data: "fecha_vencimiento", defaultContent: "N/A" },
            {
                data: "consumo_anterior",
                defaultContent: "0",
                render: function (data) {
                    return `<span class="text-muted">${data} m³</span>`;
                }
            },
            {
                data: "y",
                defaultContent: "0",
                render: function (data) {
                    return `<strong>${data} m³</strong>`;
                }
            },
            {
                data: "diferencia",
                defaultContent: "0",
                render: function (data, type, row) {
                    var dif = parseFloat(data || 0);
                    var pct = row.variacion_pct || 0;

                    if (dif > 0) {
                        return `<span class="badge badge-danger" title="Aumento del ${pct}%">
                                <i class="fas fa-arrow-up mr-1"></i>+${dif} m³ (${pct}%)
                            </span>`;
                    } else if (dif < 0) {
                        return `<span class="badge badge-success" title="Disminución del ${pct}%">
                                <i class="fas fa-arrow-down mr-1"></i>${dif} m³ (${pct}%)
                            </span>`;
                    } else {
                        return `<span class="badge badge-secondary"><i class="fas fa-minus mr-1"></i>Sin cambio</span>`;
                    }
                }
            },
            {
                data: "total",
                defaultContent: "0",
                render: function (data) {
                    var val = parseInt(data || 0);
                    return `$ ${val.toLocaleString('es-CL')}`;
                }
            },
            { data: "tipo_dte", defaultContent: "BOLETA EXENTA" },
            {
                data: "estado",
                defaultContent: "PENDIENTE",
                render: function (data) {
                    return `<span class="badge badge-warning">${data}</span>`;
                }
            }
        ],
        language: {
            "emptyTable": "No hay registros en esta categoría",
            "search": "Buscar:",
            "paginate": { "next": "Sig.", "previous": "Ant." }
        }
    });
}

function filtrarTablaModal(tipo) {
    if (!datosAlertasGlobal) {
        cargarNotificacionesAlertas(function () {
            filtrarTablaModal(tipo);
        });
        return;
    }

    var listaData = [];
    var d = datosAlertasGlobal.detalles;

    if (tipo === 'morosos') {
        listaData = d.morosos || [];
    } else if (tipo === 'vencen_hoy') {
        listaData = d.vencen_hoy || [];
    } else if (tipo === 'anomalos') {
        listaData = d.consumos_anomalos || [];
    } else {
        listaData = [
            ...(d.morosos || []),
            ...(d.vencen_hoy || []),
            ...(d.consumos_anomalos || [])
        ];
    }

    inicializarTablaModal(listaData);
}

$(document).ready(function () {
    // Carga inicial
    cargarNotificacionesAlertas();

    // Evento Clic en Notificaciones
    $(document).on("click", ".btn-ver-detalle", function (e) {
        e.preventDefault();

        var tipo = $(this).attr("data-tipo") || 'morosos';

        $("#modal_modulo_alertas").modal("show");

        if (tipo === 'vencen_hoy') {
            $('#btn_tab_vencen').tab('show');
        } else if (tipo === 'anomalos') {
            $('#btn_tab_anomalos').tab('show');
        } else {
            $('#btn_tab_morosos').tab('show');
        }

        filtrarTablaModal(tipo);
    });
});