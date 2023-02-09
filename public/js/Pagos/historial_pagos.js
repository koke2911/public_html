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

function anular_pago(id_caja) {
	$.ajax({
        url: base_url + "/Pagos/Ctrl_historial_pagos/anular_pago",
        type: "POST",
        async: false,
        data: { id_caja: id_caja },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_pagos").dataTable().fnReloadAjax(base_url + "/Pagos/Ctrl_historial_pagos/datatable_historial_pagos");
                alerta.ok("alerta", "Pago anulado con éxito");
            } else {
                alerta.error("alerta", respuesta);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

function buscar_pagos() {
	var id_socio = $("#txt_id_socio").val();
	var desde = $("#dt_desde").val();
	var hasta = $("#dt_hasta").val();

	var datosBusqueda = [id_socio, desde, hasta];

	$("#grid_pagos").dataTable().fnReloadAjax(base_url + "/Pagos/Ctrl_historial_pagos/datatable_historial_pagos_busqueda/" + datosBusqueda);
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
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Pagos/Ctrl_historial_pagos/datatable_historial_pagos",
        orderClasses: true,
        columns: [
            { "data": "id_caja" },
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
            { "data": "forma_pago" },
            { "data": "n_transaccion" },
            { "data": "rol_socio" },
            { "data": "estado" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_caja",
                "render": function(data, type, row) {
                    return "<button type='button' class='reimprimir_boucher btn btn-info' title='Reimprimir Boucher'><i class='fas fa-print'></i></button>";
                }
            },
            { 
                "data": "id_caja",
                "render": function(data, type, row) {
                    return "<button type='button' class='anular_pago btn btn-danger' title='Anular Pago'><i class='fas fa-ban'></i></button>";
                }
            },
            { 
                "data": "id_caja",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_pago btn btn-warning' title='Traza Pago'><i class='fas fa-shoe-prints'></i></button>";
                }
            },
            { "data": "nombre_socio" },
            { "data": "descuento" }
        ],
        "columnDefs": [
            { "targets": [0, 13, 14], "visible": false, "searchable": false }
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

	$("#grid_pagos tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_pagos.row(tr).data();
        var id_caja = data["id_caja"];

        $("#grid_deuda").dataTable().fnReloadAjax(base_url + "/Pagos/Ctrl_historial_pagos/datatable_detalle_pago/" + id_caja);
        $("#dlg_caja_detalle").modal("show");
    });

    $("#grid_pagos tbody").on("click", "button.traza_pago", function () {
		var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_pagos.row(tr).data();
        var id_caja = data["id_caja"];

        $("#divContenedorTrazaPagos").load(
            base_url + "/Pagos/Ctrl_historial_pagos/v_pago_traza/" + id_caja
        ); 

        $('#dlg_traza_pagos').modal('show');
    });

    $("#grid_pagos tbody").on("click", "button.anular_pago", function () {
		var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_pagos.row(tr).data();
        var id_caja = data["id_caja"];

        Swal.fire({
            title: "¿Anular Pago?",
            text: "¿Está seguro de anular el pago?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
            	anular_pago(id_caja);
            }
        });
    });

    $("#grid_pagos tbody").on("click", "button.reimprimir_boucher", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_pagos.row(tr).data();

        var datos = {
            total_pagar: data["pagado"],
            entregado: data["entregado"],
            vuelto: data["vuelto"],
            forma_pago_glosa: data["forma_pago"],
            n_transaccion: data["n_transaccion"],
            nombre_socio: data["nombre_socio"],
            descuento: data["descuento"]
        }

        var datos_json = JSON.stringify(datos);

        window.open(base_url + "/Pagos/Ctrl_caja/emitir_comprobante_pago/" + datos_json, "DTE", "width=1200,height=800,location=0,scrollbars=yes");
    });

    var grid_deuda = $("#grid_deuda").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        // ajax: base_url + "/Pagos/Ctrl_historial_pagos/datatable_detalle_pago/" + id_caja,
        orderClasses: true,
        columns: [
            { "data": "id_metros" },
            { 
            	"data": "deuda",
            	"render": function(data, type, row) {
                    return peso.formateaNumero(data);
            	}
            },
            { "data": "fecha_vencimiento" }
        ],
        "columnDefs": [
            { "targets": [0], "visible": false, "searchable": false }
        ],
        select: "multi",
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