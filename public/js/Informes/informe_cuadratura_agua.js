var ctx = $("#graf_consumo");
var myLineChart;

function buscar() {
    var fecha_desde = $("#dt_fecha_desde").val();
    var fecha_hasta = $("#dt_fecha_hasta").val();
    var id_conversion = $("#cmb_conversion").val();
    var conversion = $("#cmb_conversion option:selected").text();

    var datos = {
        fecha_desde: fecha_desde,
        fecha_hasta: fecha_hasta,
        id_conversion: id_conversion,
    }

    var datosBusqueda = JSON.stringify(datos);

    var datos_graf = [];
    var tipos = [];
    var diferencia = 0;
    var consumo = 0;
    var llenado = 0;

    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Informes/Ctrl_informe_cuadratura_agua/llenar_grafico_cuadratura_agua/" + datosBusqueda,
        async: false
    }).done( function(data) {
        for (var i = 0; i < data.length; i++) {
            var color = colorRGB();

            if (data[i].tipo == "Consumo") {
                consumo = data[i].agua;
            } else {
                llenado = data[i].agua;
            }
            
            datos_graf.push({
                label: data[i].tipo,
                backgroundColor: color,
                borderColor: color,
                data: [parseInt(data[i].agua)]
            });

            tipos.push(data[i].tipo);
        }

        diferencia = parseInt(llenado) - parseInt(consumo);
        tipos.push("Diferencia");
        $("#txt_diferencia").val(diferencia); 
        $("#txt_consumo").val(consumo);
        $("#txt_llenado").val(llenado);

        datos_graf.push({
            label: "Diferencia",
            backgroundColor: colorRGB(),
            borderColor: colorRGB(),
            data: [diferencia]
        });
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });

    if (myLineChart != undefined) { myLineChart.destroy(); }

    myLineChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [conversion],
            datasets: datos_graf,
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
}

function generarNumero(numero){
    return (Math.random()*numero).toFixed(0);
}

function colorRGB(){
    var coolor = "("+generarNumero(255)+"," + generarNumero(255) + "," + generarNumero(255) +")";
    return "rgb" + coolor;
}

$(document).ready(function() {
    $("#dt_fecha_desde").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        if ($(this).val() != "") {
            $('#dt_fecha_hasta').data("DateTimePicker").minDate($(this).val());
            $("#dt_fecha_hasta").prop("disabled", false);
        } else {
            $("#dt_fecha_hasta").data("DateTimePicker").clear();
            $("#dt_fecha_hasta").prop("disabled", true);
        }
    });

    $("#dt_fecha_hasta").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#btn_buscar").on("click", function() {
        if ($("#form_inf_cuadratura").valid()) {
            buscar();
            $("#informeCuadraturaAgua").collapse("hide");
        }
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_inf_cuadratura")[0].reset();
        $("#dt_fecha_desde").data("DateTimePicker").clear();
    });

    $("#btn_imprimir_grafico").on("click", function() {
        $("#divCuadratura").printThis({canvas: true});
    });

    $("#form_inf_cuadratura").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            dt_fecha_desde: {
                required: true
            },
            dt_fecha_hasta: {
                required: true
            }
        },
        messages: {
            dt_fecha_desde: {
                required: "Obligatorio"
            },
            dt_fecha_hasta: {
                required: "Obligatorio"
            }
        }
    });
});