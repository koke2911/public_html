var base_url = $("#txt_base_url").val();


function des_habilitar(a, b,c) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#dt_mes").prop("disabled", a);
    $("#txt_ingresos").prop("disabled", c);
    $("#txt_egresos").prop("disabled", c);
    $("#txt_saldo_periodo").prop("disabled", c);
    $("#txt_saldo_anterior").prop("disabled", a);
    $("#txt_saldo_siguiente").prop("disabled", c);
    $("#txt_saldo_banco").prop("disabled", a);
    $("#txt_saldo_deposito").prop("disabled", a);
    $("#txt_total_fondos").prop("disabled", c);
}

function busca_datos(mes){
	// alert(mes);
	$.ajax({
    url: base_url + "/informes/Ctrl_libro_caja/buscar_datos_arqueo/"+mes,
    type: "POST",
    async: false,
    dataType: "json",
    success: function(data) {

        if(data!=0){
            // console.warn(data.data[0].id_funcionario);
            // // var id_socio =data.id_socio;
            // // var abono =data.abono;
            $("#txt_ingresos").val(data.data[0].ingresos);
            $("#txt_egresos").val(data.data[0].egresos);
            var saldo_mes=parseFloat( $("#txt_ingresos").val())-parseFloat( $("#txt_egresos").val());
            $("#txt_saldo_periodo").val(saldo_mes);
            $("#txt_saldo_anterior").val(data.data[0].saldo_anterior);

            var total=parseFloat( $("#txt_saldo_siguiente").val())+parseFloat( $("#txt_saldo_banco").val())+parseFloat( $("#txt_saldo_deposito").val());
    		$("#txt_total_fondos").val(total);



            
		    // var siguiente=parseFloat( $("#txt_saldo_periodo").val())-parseFloat( $("#txt_saldo_anterior").val());
		    // $("#txt_saldo_siguiente").val(siguiente);
            // $("#txt_nombres").val(data.data[0].nombres);
            // $("#txt_ape_pat").val(data.data[0].ape_pat);
            // $("#txt_ape_mat").val(data.data[0].ape_mat);
            // $("#txt_prevision").val(data.data[0].prevision);
            // $("#txt_prevision_porcen").val(data.data[0].prev_porcentaje);
            // $("#txt_afp").val(data.data[0].afp);
            // $("#txt_afp_porcent").val(data.data[0].afp_porcentaje);
            // $("#txt_sueldo_bruto").val(data.data[0].sueldo_bruto);
            // $("#dt_contrato").val(data.data[0].fecha_contrato);
            // $("#txt_jornada").val(data.data[0].jornada);
            // $("#txt_vacaciones").val(data.data[0].vacaciones);
            // $("#txt_disponibles").val(data.data[0].vacaciones_disponibles);
                                   
        }
    },
	    error: function(error) {
	        respuesta = JSON.parse(error["responseText"]);
	        alerta.error("alerta", respuesta.message);
	    }
	});

}

function guardar_arqueo(){
	var dt_mes = $("#dt_mes").val();
    var txt_ingresos =$("#txt_ingresos").val();
    var txt_egresos =$("#txt_egresos").val();
    var txt_saldo_periodo =$("#txt_saldo_periodo").val();
    var txt_saldo_anterior =$("#txt_saldo_anterior").val();
    var txt_saldo_siguiente =$("#txt_saldo_siguiente").val();
    var txt_saldo_banco =$("#txt_saldo_banco").val();
    var txt_saldo_deposito =$("#txt_saldo_deposito").val();
    var txt_total_fondos =$("#txt_total_fondos").val();

    $.ajax({
        url: base_url + "/informes/Ctrl_libro_caja/guarda_arqueo",
        type: "POST",
        async: false,
        data: {
            dt_mes:dt_mes,
			txt_ingresos:txt_ingresos,
			txt_egresos:txt_egresos,
			txt_saldo_periodo:txt_saldo_periodo,
			txt_saldo_anterior:txt_saldo_anterior,
			txt_saldo_siguiente:txt_saldo_siguiente,
			txt_saldo_banco:txt_saldo_banco,
			txt_saldo_deposito:txt_saldo_deposito,
			txt_total_fondos:txt_total_fondos
            
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_arqueo").dataTable().fnReloadAjax(base_url + "/informes/Ctrl_libro_caja/datatable_arqueo");
                $("#form_arqueo")[0].reset();
                des_habilitar(true, false,true);
                alerta.ok("alerta", "Arqueo de caja generado");
                $("#datosArqueo").collapse("hide");
                
            } else {
                alerta.error("alerta", respuesta);
            }
        }
    });
}

$(document).ready(function() {
	 des_habilitar(true, false,true);


	 $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true,true);        
        $("#form_arqueo")[0].reset();
        $("#datosArqueo").collapse("show");
    });

	 $("#btn_aceptar").on("click", function() {
        if ($("#form_arqueo").valid()) {
            guardar_arqueo();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_arqueo")[0].reset();
        des_habilitar(true, false,true);
        $("#form_arqueo").validate().resetForm();
        datatable_enabled = true;
        $("#datosArqueo").collapse("hide");
    });

    $("#txt_saldo_anterior").on("blur", function() {
    	var siguiente=parseFloat( $("#txt_saldo_periodo").val())+parseFloat( $("#txt_saldo_anterior").val());
	    $("#txt_saldo_siguiente").val(siguiente);

	    var total=parseFloat( $("#txt_saldo_siguiente").val())+parseFloat( $("#txt_saldo_banco").val())+parseFloat( $("#txt_saldo_deposito").val());
    	$("#txt_total_fondos").val(total);
    });

    $("#txt_saldo_banco").on("blur", function() {
    	
	    var total=parseFloat( $("#txt_saldo_siguiente").val())+parseFloat( $("#txt_saldo_banco").val())+parseFloat( $("#txt_saldo_deposito").val());
    	$("#txt_total_fondos").val(total);
    });

    $("#txt_saldo_deposito").on("blur", function() {
    	
	    var total=parseFloat( $("#txt_saldo_siguiente").val())+parseFloat( $("#txt_saldo_banco").val())+parseFloat( $("#txt_saldo_deposito").val());
    	$("#txt_total_fondos").val(total);
    });

    $("#dt_mes").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function () {
        $("#dt_mes").blur();
        busca_datos(this.value);
      });


	  $("#form_arqueo").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            dt_mes:{
            	required:true,

            }
			,
			txt_ingresos:{
				required:true,
				digits:true,
			}
			,
			txt_egresos:{
				required:true,
				digits:true,
			}
			,
			txt_saldo_periodo:{
				required:true,
				digits:true,
			}
			,
			txt_saldo_anterior:{
				required:true,
				digits:true,
			}
			,
			txt_saldo_siguiente:{
				required:true,
				digits:true,
			}
			,
			txt_saldo_banco:{
				required:true,
				digits:true,
			}
			,
			txt_saldo_deposito:{
				required:true,
				digits:true,
			}
			,
			txt_total_fondos:{
				required:true,
				digits:true,
			}
        },
        messages: {
            dt_mes:{
            	required:"Valor"

            }
			,
			txt_ingresos:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
			,
			txt_egresos:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
			,
			txt_saldo_periodo:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
			,
			txt_saldo_anterior:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
			,
			txt_saldo_siguiente:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
			,
			txt_saldo_banco:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
			,
			txt_saldo_deposito:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
			,
			txt_total_fondos:{
				required:"Valor Requerido",
				digits:"Solo numeros",
			}
        }
    });



	  var grid_arqueo = $("#grid_arqueo").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/informes/Ctrl_libro_caja/datatable_arqueo",
        orderClasses: true,
        columns: [
        	{"data":"id"},
            {"data":"mes"},
			{"data":"ingreos"},
			{"data":"egresos"},
			{"data":"saldo_periodo"},
			{"data":"saldo_anterior"},
			{"data":"saldo_siguiente"},
			{"data":"saldo_banco"},
			{"data":"saldo_deposito"},
			{"data":"total_fondos"},
			{"data":"fecha_reg"},
			// {"data": "id",
   //              "render":function(data,type,row){
   //                               return "<button type='button' disabled='true' class='btn_imprimir btn btn-primary' title='Funcionalidad no disponible'><i class='fas fa-print'></i></button>"
   //                     }
   //            },
            {"data": "id",
                "render":function(data,type,row){
                                 return "<button type='button' class='btn_eliminar btn btn-danger' title='Eliminar'><i class='fas fa-ban'></i></button>"
                       }
              }
			// {"data":"usu_reg"}
        ],
        "columnDefs": [
            { "targets": [], "visible": false, "searchable": false }
        ],
        dom: 'Bfrtip',
    buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Arqueo de caja"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Arqueo de caja",
                orientation: 'landscape',
                pageSize: 'TABLOID'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Arqueo de caja"
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


	 $("#grid_arqueo tbody").on("click", "button.btn_eliminar", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_arqueo.row(tr).data();
        var id=data.id;    

        $.ajax({
            url: base_url + "/informes/Ctrl_libro_caja/anula_arqueo/"+id,
            method: "GET",
            dataType: "json",
            success: function (data) {
                if(data==1){
                    $("#grid_arqueo").dataTable().fnReloadAjax(base_url + "/informes/Ctrl_libro_caja/datatable_arqueo");
                    alerta.error("alerta", "Arqueo anulado con éxito");
                    
                }else{
                     console.error("No se pudo anular", data);

                }
            },
            error: function (error) {
                console.error("No se pudo anular", error);
            }
        });               
    });
});