var base_url = $("#txt_base_url").val();

function grafico_socios() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Ctrl_dashboard/llenar_grafico_socios",
    }).done( function(data) {
        var sexo = [];
        var cantidad = [];
        
        for (var i = 0; i < data.length; i++) {
            sexo.push(data[i].sexo);
            cantidad.push(data[i].cantidad);
        }

        var ctx = document.getElementById("graf_socios");
        var myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sexo,
                datasets: [{
                    label: "Cantidad",
                    backgroundColor: "rgba(2,117,216,1)",
                    borderColor: "rgba(2,117,216,1)",
                    data: cantidad,
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: 15000,
                            maxTicksLimit: 5
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                },
                legend: {
                    display: false
                }
            }
        });
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function grafico_mensualidad() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Informes/Ctrl_informe_mensual/datatable_informe_mensual/0",
    }).done( function(data) {
        var labels = [];
        var subtotal = [];
        var subsidios = [];
        var multas = [];
        var servicios = [];
        var total_pagado = [];

        for (var i = 0; i < data.data.length; i++) {
            labels.push(data.data[i].fecha_pago);
            subtotal.push(data.data[i].subtotal);
            subsidios.push(data.data[i].subsidios);
            multas.push(data.data[i].multas);
            servicios.push(data.data[i].servicios);
            total_pagado.push(data.data[i].total_pagado);
        }

        var ctx = document.getElementById("graf_mensual");
        var myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Subtotal $",
                        backgroundColor: "rgba(191,191,63,1)",
                        borderColor: "rgba(191,191,63,1)",
                        data: subtotal
                    },
                    {
                        label: "Subsidio $",
                        backgroundColor: "rgba(191,63,63,1)",
                        borderColor: "rgba(191,63,63,1)",
                        data: subsidios
                    },
                    {
                        label: "Multas $",
                        backgroundColor: "rgba(63,191,191,1)",
                        borderColor: "rgba(63,191,191,1)",
                        data: multas
                    },
                    {
                        label: "Servicios $",
                        backgroundColor: "rgba(63,191,127,1)",
                        borderColor: "rgba(63,191,127,1)",
                        data: servicios
                    },
                    {
                        label: "Total Pagado $",
                        backgroundColor: "rgba(191,127,63,1)",
                        borderColor: "rgba(191,127,63,1)",
                        data: total_pagado
                    }
                ],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: 15000,
                            maxTicksLimit: 5
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                },
                legend: {
                    display: false
                }
            }
        });
    }).fail(function(error){
    respuesta = JSON.parse(error["responseText"]);
    alerta.error("alerta", respuesta.message);
});
}

$(document).ready(function() {
    grafico_socios();
    grafico_mensualidad();

    $("#card_socios").on("click", function(){
        $("#content").load(base_url + "/ctrl_menu/socios");
    });

    $("#card_informe_mensual").on("click", function(){
        $("#content").load(base_url + "/ctrl_menu/informe_mensualidad");
    });

    $("#card_consumo").on("click", function(){
        $("#content").load(base_url + "/ctrl_menu/metros");
    });

    $("#card_afecto_corte").on("click", function(){
        $("#content").load(base_url + "/ctrl_menu/informe_afecto_corte");
    });
});

    