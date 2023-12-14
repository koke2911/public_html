var base_url = $("#txt_base_url").val();



$(document).ready(function() {

	$("#btn_exportar_mes").on("click", function () {
        var dt_fecha_mes = $("#dt_fecha_mes").val();
        if(dt_fecha_mes!=""){
            window.open(base_url + "/Informes/Ctrl_informe_pagos_diarios/reporte_diferencia_consumo/"+dt_fecha_mes);
        }else{
            alert("Debe Seleccionar el mes de consumo");
        }
    });

    $("#dt_fecha_mes").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_fecha_mes").blur();
    });

    $("#dt_fecha_mes").val(moment().format("MM-YYYY"));

});