var base_url = $("#txt_base_url").val();

// Carga del Gráfico de Mensualidad
function grafico_mensualidad() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Ctrl_dashboard/llenar_graficos_pagos"
    }).done(function (data) {
        var labels = [];
        var total_pagado = [];

        var datos = data.data || data;

        for (var i = 0; i < datos.length; i++) {
            labels.push(datos[i].fecha_pago);
            total_pagado.push(parseFloat(datos[i].total_pagado) || 0);
        }

        var ctx = document.getElementById("graf_mensual").getContext("2d");

        // Degradado Naranja (Barras Generales)
        var gradientOrange = ctx.createLinearGradient(0, 0, 0, 300);
        gradientOrange.addColorStop(0, '#FF6D39');
        gradientOrange.addColorStop(1, 'rgba(255, 109, 57, 0.08)');

        // Degradado Dorado (Barra Último Mes)
        var gradientGold = ctx.createLinearGradient(0, 0, 0, 300);
        gradientGold.addColorStop(0, '#D4A373');
        gradientGold.addColorStop(1, 'rgba(212, 163, 115, 0.08)');

        // Asignar el degradado dorado al último mes
        var backgroundColors = total_pagado.map(function (val, index) {
            return (index === total_pagado.length - 1) ? gradientGold : gradientOrange;
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: "Total Pagado $",
                    data: total_pagado,
                    backgroundColor: backgroundColors,
                    borderWidth: 0,
                    borderRadius: 8,
                    borderSkipped: 'bottom',
                    categoryPercentage: 0.8,
                    barPercentage: 0.85
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,      // Elimina la cuadrícula vertical
                            drawBorder: false   // Oculta la línea base inferior
                        },
                        ticks: {
                            fontSize: 11,
                            fontColor: "#A0AEC0"
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,      // Elimina las líneas horizontales
                            drawBorder: false   // Oculta la línea de borde lateral
                        },
                        ticks: {
                            display: false,     // Oculta los valores del eje Y
                            beginAtZero: true
                        }
                    }]
                },
                tooltips: {
                    backgroundColor: '#2D3748',
                    titleFontSize: 12,
                    bodyFontSize: 12,
                    xPadding: 10,
                    yPadding: 10,
                    cornerRadius: 6,
                    callbacks: {
                        label: function (tooltipItem) {
                            return 'Total: $' + Number(tooltipItem.yLabel).toLocaleString('es-CL');
                        }
                    }
                }
            }
        });
    }).fail(function (error) {
        if (error["responseText"]) {
            var respuesta = JSON.parse(error["responseText"]);
            if (typeof alerta !== "undefined") alerta.error("alerta", respuesta.message);
        }
    });
}

// Carga Dinámica de los Últimos Pagos Registrados
function cargar_ultimos_pagos() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Ctrl_dashboard/datatable_ultimos_pagos",
    }).done(function (response) {
        var registros = response.data || response;
        var html = "";

        if (registros.length > 0) {
            // Ordenar por id_caja descendente (los más recientes primero)
            registros.sort(function (a, b) {
                return parseInt(b.id_caja) - parseInt(a.id_caja);
            });

            // Mostrar solo los primeros 5 registros
            var limite = Math.min(registros.length, 5);

            for (var i = 0; i < limite; i++) {
                var row = registros[i];

                var socio = row.nombre_socio || "N/A";
                var fecha = row.fecha || "";
                var estado = row.estado || "Pagado";

                // Obtener y limpiar el valor numérico del campo 'pagado'
                var rawMonto = row.pagado || 0;
                var montoNumero = parseFloat(rawMonto) || 0;

                // Formato badge según estado
                var badgeClass = (estado == "Anulado" || estado == "0") ? "badge-danger" : "badge-success";

                html += '<tr>' +
                    '<td class="font-weight-bold text-gray-800">' + socio + '</td>' +
                    '<td>$' + montoNumero.toLocaleString('es-CL') + '</td>' +
                    '<td class="text-muted small">' + fecha + '</td>' +
                    '<td class="text-center"><span class="badge ' + badgeClass + '">' + estado + '</span></td>' +
                    '</tr>';
            }
        } else {
            html = '<tr><td colspan="4" class="text-center text-muted">No se encontraron pagos recientes</td></tr>';
        }

        $("#tabla_ultimos_pagos tbody").html(html);
    }).fail(function () {
        $("#tabla_ultimos_pagos tbody").html('<tr><td colspan="4" class="text-center text-danger">Error al cargar pagos</td></tr>');
    });
}

function cargar_indicador_socios() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Ctrl_dashboard/obtener_indicador_socios"
    }).done(function (response) {
        var total = (response.total_activos || 0).toLocaleString('es-CL');
        var nuevos = response.nuevos_mes || 0;

        // Actualiza el número principal y el texto secundario
        $("#indicador_socios_total").text(total);
        $("#indicador_socios_delta").html("▲ +" + nuevos + " este mes");
    }).fail(function () {
        $("#indicador_socios_total").text("0");
        $("#indicador_socios_delta").html("▲ +0 este mes");
    });
}

function formatearMontoCorto(monto) {
    if (monto >= 1000000) {
        return '$' + (monto / 1000000).toFixed(1) + 'M';
    } else if (monto >= 1000) {
        return '$' + (monto / 1000).toFixed(0) + 'K';
    }
    return '$' + monto.toLocaleString('es-CL');
}

function obtener_recaudacion_indicador() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Ctrl_dashboard/cargar_indicador_recaudacion"
    }).done(function (res) {
        var montoFormateado = formatearMontoCorto(res.total || 0);
        var pct = res.porcentaje || 0;

        $("#recaudacion_total").text(montoFormateado);

        // Ajustar icono y clase CSS según si subió o bajó
        if (pct >= 0) {
            $("#recaudacion_delta")
                .removeClass("down").addClass("up")
                .html("▲ " + pct + "% vs mes anterior");
        } else {
            $("#recaudacion_delta")
                .removeClass("up").addClass("down")
                .html("▼ " + Math.abs(pct) + "% vs mes anterior");
        }
    }).fail(function () {
        $("#recaudacion_total").text("$0");
        $("#recaudacion_delta").html("▲ 0% vs mes anterior");
    });
}

function obtener_tasa_pago_indicador() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Ctrl_dashboard/cargar_indicador_tasa_pago"
    }).done(function (res) {
        var tasa = res.tasa_actual || 0;
        var diff = res.diferencia || 0;

        $("#tasa_pago_total").text(tasa.toFixed(1) + "%");

        if (diff >= 0) {
            $("#tasa_pago_delta")
                .removeClass("down").addClass("up")
                .html("▲ +" + diff.toFixed(1) + "% vs promedio");
        } else {
            $("#tasa_pago_delta")
                .removeClass("up").addClass("down")
                .html("▼ " + diff.toFixed(1) + "% vs promedio");
        }
    }).fail(function () {
        $("#tasa_pago_total").text("0%");
        $("#tasa_pago_delta").html("▲ +0% vs promedio");
    });
}

function obtener_deudas_pendientes_indicador() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Ctrl_dashboard/cargar_indicador_deudas_pendientes"
    }).done(function (res) {
        var total = (res.total || 0).toLocaleString('es-CL');
        var variacion = res.variacion_semana || 0;

        $("#deudas_total").text(total);

        // Si se pagaron deudas esta semana es positivo (bajan las deudas -> clase 'down' verde/favorable)
        if (variacion > 0) {
            $("#deudas_delta")
                .removeClass("up").addClass("down")
                .html("▼ −" + variacion + " desde la semana pasada");
        } else if (variacion < 0) {
            $("#deudas_delta")
                .removeClass("down").addClass("up")
                .html("▲ +" + Math.abs(variacion) + " desde la semana pasada");
        } else {
            $("#deudas_delta")
                .removeClass("up down")
                .html("0 desde la semana pasada");
        }
    }).fail(function () {
        $("#deudas_total").text("0");
        $("#deudas_delta").html("▼ −0 desde la semana pasada");
    });
}

// Inicialización de Funciones y Eventos
$(document).ready(function () {
    grafico_mensualidad();
    cargar_ultimos_pagos();
    cargar_indicador_socios();
    obtener_recaudacion_indicador();
    obtener_tasa_pago_indicador();
    obtener_deudas_pendientes_indicador();

    // Redirecciones de accesos directos
    // Redirecciones dinámicas de los indicadores en el contenedor central
    $("#card_indicador_socios").on("click", function () {
        $("#content").load(base_url + "/ctrl_menu/socios");
    });

    $("#card_indicador_recaudacion").on("click", function () {
        $("#content").load(base_url + "/ctrl_menu/historial_pagos");
    });

    $("#card_indicador_tasa").on("click", function () {
        $("#content").load(base_url + "/ctrl_menu/informe_mensualidad");
    });

    $("#card_indicador_deudas").on("click", function () {
        $("#content").load(base_url + "/ctrl_menu/deudores_det");
    });

    $("#card_historial_pagos").on("click", function () {
        $("#content").load(base_url + "/ctrl_menu/historial_pagos");
    });
    
});