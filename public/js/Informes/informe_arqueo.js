var base_url = $("#txt_base_url").val();

var peso = {
    validaEntero: function  ( value ) {
        var RegExPattern = /[0-9]+$/;
        return RegExPattern.test(value);
    },
    formateaNumero: function (value) {
        if (peso.validaEntero(value))  {  
            var retorno = '';
            value = value.toString().split('').reverse().join('');
            var i = value.length;
            while(i>0) retorno += ((i%3===0&&i!=value.length)?'.':'')+value.substring(i--,i);
            return retorno;
        }
        return 0;
    },
    quitar_formato : function(numero){
        numero = numero.split('.').join('');
        return numero;
    }
}

function buscar_pagos() {
	var id_socio = $("#txt_id_socio").val();
	var desde = $("#dt_desde").val();
	var hasta = $("#dt_hasta").val();
    var id_forma_pago = $("#cmb_forma_pago").val();
    var punto_blue = $("#chk_punto_blue").prop("checked") ? 1 : 0;

    var datos = {
        id_socio: id_socio,
        desde: desde,
        hasta: hasta,
        id_forma_pago: id_forma_pago,
        punto_blue: punto_blue
    }

	var datosBusqueda = JSON.stringify(datos);

	$("#grid_pagos").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_arqueo/datatable_informe_arqueo/" + datosBusqueda);
}

$(document).ready(function() {
	$("#txt_id_socio").prop("disabled", true);
	$("#txt_rut_socio").prop("disabled", true);
	$("#txt_rol").prop("disabled", true);
	$("#txt_nombre_socio").prop("disabled", true);
	$("#dt_hasta").prop("disabled", true);

	$("#btn_buscar_socio").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_historial_pagos"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

	$("#dt_desde").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        if (this.value != "") {
	        $('#dt_hasta').data("DateTimePicker").minDate(this.value);

	        $("#dt_hasta").rules("add", {
	            required: true,
	            messages: { 
	                required: "Fecha hasta es obligatoria"
	            }
	        });

	        $("#dt_hasta").prop("disabled", false);
        } else {
        	$("#dt_hasta").rules("add", { required: false });
	        $("#dt_hasta").data("DateTimePicker").clear();
	        $("#dt_hasta").prop("disabled", true);
        }
    });

    $("#dt_hasta").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#btn_buscar").on("click", function() {
    	if ($("#form_histPagos").valid()) {
		   	buscar_pagos();
            $("#informeArqueo").collapse("hide");
    	}
    });

    $("#btn_limpiar").on("click", function() {
    	$("#form_histPagos")[0].reset();
    	$("#dt_desde").data("DateTimePicker").clear();
    	$("#dt_hasta").rules("add", { required: false });
        $("#dt_hasta").prop("disabled", true);
    });

	var grid_pagos = $("#grid_pagos").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        select: {
            toggleable: false
        },
        orderClasses: true,
        dom: 'Bfrtilp',
        columns: [
            { "data": "usuario" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { "data": "forma_pago" },
            { "data": "n_transaccion" },
            { "data": "fecha_pago" },
            { 
                "data": "total",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "monto_subsidio",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "pagado",
            	"render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "entregado",
            	"render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "vuelto",
            	"render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { "data": "id_caja" }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            $( api.column(6).footer()).html(
                peso.formateaNumero(api.column(6, {page:'current'} ).data().sum())
            );

            $( api.column(7).footer()).html(
                peso.formateaNumero(api.column(7, {page:'current'} ).data().sum())
            );

            $( api.column(8).footer()).html(
                peso.formateaNumero(api.column(8, {page:'current'} ).data().sum())
            );
        },
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Arqueo",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            const TOTAL = 6; 
                            const MONTO_SUBSIDIO = 7;
                            const PAGADO = 8;
                            const ENTREGADO = 9;
                            const VUELTO = 10;

                            switch(column) {
                                case TOTAL:
                                    return peso.quitar_formato(data);
                                    break;
                                case MONTO_SUBSIDIO:
                                    return peso.quitar_formato(data);
                                    break;
                                case PAGADO:
                                    return peso.quitar_formato(data);
                                    break;
                                case ENTREGADO:
                                    return peso.quitar_formato(data);
                                    break;
                                case VUELTO:
                                    return peso.quitar_formato(data);
                                    break;
                                default:
                                    return data;
                                    break;
                            }
                        },
                        footer: function ( data, row, column, node ) {
                            return peso.quitar_formato(data);
                        }
                    }
                }
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Arqueo",
                orientation: 'landscape',
                pageSize: 'LETTER',
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Arqueo",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
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

    $("#form_histPagos").validate({
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