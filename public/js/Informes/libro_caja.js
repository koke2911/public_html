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

   
    
});