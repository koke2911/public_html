var base_url = $("#txt_base_url").val();





$(document).ready(function() {



    $("#dt_mes_consulta").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_mes_consulta").blur();
    });

    $("#dt_ano").datetimepicker({
        format: "YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_ano").blur();
    });


    $("#dt_inicio").datetimepicker({
        format: "YYYY-MM-DD",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function () {
        $("#dt_inicio").blur();
    });

    $("#dt_fin").datetimepicker({
        format: "YYYY-MM-DD",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function () {
        $("#dt_fin").blur();
    });

    
    $("#dt_inicio").on("dp.change", function (e) {
        var fechaInicio = e.date;
        var fechaMaxima = moment().subtract(0, 'days');
        if (fechaInicio.isAfter(fechaMaxima)) {
            $("#dt_inicio").data("DateTimePicker").date(fechaMaxima);
            alerta.error("alerta", 'La fecha de inicio no puede ser una fecha a futuro');
            $("#dt_inicio").data("DateTimePicker").clear();
            
        }
    });

    $("#dt_fin").on("dp.change", function (e) {
        var fechaFin = e.date;
        var fechaInicio = $("#dt_inicio").data("DateTimePicker").date();
        var fechaMaxima = moment().subtract(0, 'days');
        if (fechaFin.isAfter(fechaMaxima)) {
            $("#dt_fin").data("DateTimePicker").date(fechaMaxima);
            alerta.error("alerta", 'La fecha de fin no puede ser una fecha a futuro');
            $("#dt_fin").data("DateTimePicker").clear();
        }
        if (fechaFin.isBefore(fechaInicio)) {
            $("#dt_fin").data("DateTimePicker").date(fechaInicio);
            alerta.error("alerta", 'La fecha de fin no puede ser menor a la fecha de inicio');
            $("#dt_fin").data("DateTimePicker").clear();
        }
    });


    $("#btn_export").on("click", function() {

            var mes_consulta=$("#dt_mes_consulta").val();

	        if(mes_consulta!="" ){
	            window.open(base_url+"/Informes/Ctrl_libro_caja/reporte_trimestral/"+mes_consulta);
	        }else{
	            alerta.error("alerta", 'Debe Seleccionar un mes  para exportar');
	        }
        
    });

    $("#btn_export_anual").on("click", function() {

            var mes_consulta=$("#dt_ano").val();

            if(mes_consulta!="" ){
                window.open(base_url+"/Informes/Ctrl_libro_caja/reporte_anual/"+mes_consulta);
            }else{
                alerta.error("alerta", 'Debe Seleccionar un mes  para exportar');
            }
        
    });


    $("#btn_export_simple").on("click", function () {
        
        var inicio=$("#dt_inicio").val();
        var fin=$("#dt_fin").val();

        if (inicio != "" && fin != "") {
            window.open(base_url + "/Informes/Ctrl_libro_caja/reporte_simple/" + inicio+"/"+fin);
        } else {
            alerta.error("alerta", 'Debe Seleccionar rango de fechas para exportar');
        }

    });

   
    
});