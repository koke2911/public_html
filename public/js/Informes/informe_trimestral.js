var base_url = $("#txt_base_url").val();





$(document).ready(function() {



    $("#dt_ano_consulta").datetimepicker({
        format: "YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_ano_consulta").blur();
    });


    $("#btn_export").on("click", function() {
        // des_habilitar(false, true);
        var ano_consulta=$("#dt_ano_consulta").val();
        var trimestre=$("#txt_trimestre").val();

        if ($("#form_trimestral").valid()) {
	        if(ano_consulta!="" || trimestre!=""){
	            window.open(base_url+"/Informes/Ctrl_informe_trimestral/reporte_trimestral/"+ano_consulta+"-"+trimestre);
	        }else{
	            alerta.error("alerta", 'Debe Seleccionar un año y trimestre para exportar');
	        }
	    }
        
    });

    $("#form_trimestral").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_trimestre: {
                required: true,
                number: true,
                maxlength:1,
                range: [1, 4]
            }            
        },
        messages: {            
            txt_trimestre:{
                required:"El valor debe ser numerico"
            }            
        }
    });
    
});