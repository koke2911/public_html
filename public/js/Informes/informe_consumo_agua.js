var ctx = $("#graf_consumo");
var myLineChart;

function buscar() {
    var fecha_desde = $("#dt_fecha_desde").val();
    var fecha_hasta = $("#dt_fecha_hasta").val();
    var id_socio = $("#txt_id_socio").val();
    var id_conversion = $("#cmb_conversion").val();
    var conversion = $("#cmb_conversion option:selected").text();
    var id_sector = $("#cmb_sectores").val();

    var datos = {
        fecha_desde: fecha_desde,
        fecha_hasta: fecha_hasta,
        id_socio: id_socio,
        id_conversion: id_conversion,
        conversion: conversion,
        id_sector: id_sector
    }

    var datosBusqueda = JSON.stringify(datos);

    $("#grid_inf_consumo_agua").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_consumo_agua/datatable_informe_consumo_agua/" + datosBusqueda);

    var consumos = [];
    var sectores = [];
    var colores = [];

    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Informes/Ctrl_informe_consumo_agua/llenar_grafico_consumo_agua/" + datosBusqueda,
        async: false
    }).done( function(data) {
        for (var i = 0; i < data.length; i++) {
            consumos.push(data[i].consumo);
            sectores.push(data[i].sector);
            colores.push(colorRGB());
        }
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });

    if (myLineChart != undefined) { myLineChart.destroy(); }

    myLineChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: sectores,
            datasets: [{
                label: "Consumo",
                backgroundColor: colores,
                borderColor: colores,
                data: consumos
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
}

function generarNumero(numero){
    return (Math.random()*numero).toFixed(0);
}

function colorRGB(){
    var coolor = "("+generarNumero(255)+"," + generarNumero(255) + "," + generarNumero(255) +")";
    return "rgb" + coolor;
}

function llenar_cmb_sectores() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_sector",
    }).done( function(data) {
        $("#cmb_sectores").html('');

        var opciones = "<option value=\"\">Seleccione un sector</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].sector + "</option>";
        }

        $("#cmb_sectores").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

$(document).ready(function() {
    $("#dt_fecha_hasta").prop("disabled", true);
    $("#txt_id_socio").prop("readonly", true);
    $("#txt_rut_socio").prop("readonly", true);
    $("#txt_nombre_socio").prop("readonly", true);
    llenar_cmb_sectores();

    $("#dt_fecha_desde").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        if ($(this).val() != "") {
            $('#dt_fecha_hasta').data("DateTimePicker").minDate($(this).val());

            $("#dt_fecha_hasta").rules("add", {
                required: true,
                messages: { 
                    required: "Fecha hasta es obligatoria"
                }
            });

            $("#dt_fecha_hasta").prop("disabled", false);
        } else {
            $("#dt_fecha_hasta").rules("add", { required: false });
            $("#dt_fecha_hasta").data("DateTimePicker").clear();
            $("#dt_fecha_hasta").prop("disabled", true);
        }
    });

    $("#dt_fecha_hasta").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#btn_buscar_socio").on("click", function() {
        $("#tlt_buscador").text("Buscar Operador");
        var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_socio";
                
        $("#divContenedorBuscador").load(url_buscador);
        $('#dlg_buscador').modal('show');
    });

    $("#btn_buscar").on("click", function() {
        if ($("#form_inf_consumo").valid()) {
            buscar();
            $("#informeConsumoAgua").collapse("hide");
        }
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_inf_consumo")[0].reset();
        $("#dt_fecha_desde").data("DateTimePicker").clear();
        $("#dt_fecha_hasta").rules("add", { required: false });
        $("#dt_fecha_hasta").prop("disabled", true);
    });

    $("#btn_imprimir_grafico").on("click", function() {
        $("#graf_consumo").printThis({canvas: true});
    });

    var grid_inf_consumo_agua = $("#grid_inf_consumo_agua").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        orderClasses: true,
        columns: [
            { "data": "id_metros" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { "data": "consumo" },
            { "data": "um_agua" },
            { "data": "sector" }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            $(api.column(4).footer()).html(api.column(4, {page:'current'} ).data().sum());
        },
        dom: 'Bfrtilp',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Llenado de Agua",
                footer: true

            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Llenado de Agua",
                pageSize: 'LETTER',
                orientation: 'landscape',
                footer: true
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Llenado de Agua",
                footer: true
            },
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
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
	});

     $("#form_inf_consumo").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        }
    });
});