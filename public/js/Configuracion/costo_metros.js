var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_cantidad_cargo_fijo").prop("disabled", a);
    $("#txt_cargo_fijo").prop("disabled", a);
    $("#txt_desde").prop("disabled", a);
    $("#txt_hasta").prop("disabled", a);
    $("#txt_costo").prop("disabled", a);
}

function llenar_cmb_apr() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_apr",
    }).done( function(data) {
        $("#cmb_apr").html('');

        var opciones_apr = "<option value=\"\">Seleccione una APR</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones_apr += "<option value=\"" + data[i].id + "\">" + data[i].apr + "</option>";
        }

        $("#cmb_apr").append(opciones_apr);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_diametro() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_medidores/llenar_cmb_diametro",
    }).done( function(data) {
        $("#cmb_diametro").html('');

        var opciones = "<option value=\"\">Seleccione un diámetro</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].diametro + "</option>";
        }

        $("#cmb_diametro").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function eliminar_costo_metros(observacion, id_costo_metros) {
    $.ajax({
        url: base_url + "/Configuracion/Ctrl_costo_metros/eliminar_costo_metros",
        type: "POST",
        async: false,
        data: { 
            id_costo_metros: id_costo_metros,
            observacion: observacion
        },
        success: function(respuesta) {
            const OK = 1;
            
            if (respuesta == OK) {
                alerta.ok("alerta", "Costo eliminado con éxito");
                $("#grid_costo_metros").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_costo_metros/datatable_costo_metros/" + $("#cmb_apr").val());
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

function guardar_costo_metros() {
    var id_costo_metros = $("#txt_id_costo_metros").val();
    var id_apr = $("#cmb_apr").val();
    var id_diametro = $("#cmb_diametro").val();
    var id_cargo_fijo = $("#txt_id_cargo_fijo").val();
    var cantidad_cargo_fijo = $("#txt_cantidad_cargo_fijo").val();
    var cargo_fijo = peso.quitar_formato($("#txt_cargo_fijo").val());
    var desde = $("#txt_desde").val();
    var hasta = $("#txt_hasta").val();
    var costo = peso.quitar_formato($("#txt_costo").val());

    $.ajax({
        url: base_url + "/Configuracion/Ctrl_costo_metros/guardar_costo_metros",
        type: "POST",
        async: false,
        dataType: "json",
        data: {
            id_costo_metros: id_costo_metros,
            id_apr: id_apr,
            id_diametro: id_diametro,
            id_cargo_fijo: id_cargo_fijo,
            cantidad_cargo_fijo: cantidad_cargo_fijo,
            cargo_fijo: cargo_fijo,
            desde: desde,
            hasta: hasta,
            costo: costo
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta.respuesta == OK) {
                if (respuesta.nuevo_cf) {
                    $("#txt_id_cargo_fijo").val(respuesta.id_cargo_fijo);
                }
                $("#grid_costo_metros").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_costo_metros/datatable_costo_metros/" + id_apr + "/" + id_diametro);
                limpiar();
                des_habilitar(true, false);
                datatable_enabled = true;
                alerta.ok("alerta", "Costo de metros, guardado con éxito");
            } else {
                alerta.error("alerta", respuesta.respuesta);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

function mostrar_datos_costo_metros(data) {
    $("#txt_id_costo_metros").val(data["id_costo_metros"]);
    $("#cmb_apr").val(data["id_apr"]);
    $("#txt_desde").val(data["desde"]);
    $("#txt_hasta").val(data["hasta"]);
    $("#txt_costo").val(data["costo"]);
}

function limpiar() {
    $("#txt_id_costo_metros").val("");
	$("#txt_desde").val("");
	$("#txt_hasta").val("");
	$("#txt_costo").val("");
}

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

function actualizar_grid() {
    var id_apr = $("#cmb_apr").val();
    var id_diametro = $("#cmb_diametro").val();
    
    if (id_apr != "" && id_diametro != "") {
        $("#grid_costo_metros").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_costo_metros/datatable_costo_metros/" + id_apr + "/" + id_diametro);
        $.ajax({
            url: base_url + "/Configuracion/Ctrl_costo_metros/llenar_costo_fijo",
            type: "POST",
            dataType: "json",
            async: false,
            data: {
                id_diametro: id_diametro,
                id_apr: id_apr
            },
            success: function(datos) {
                if (Object.keys(datos).length > 0) {
                    $("#txt_cantidad_cargo_fijo").val(datos.cantidad);
                    $("#txt_cargo_fijo").val(datos.cargo_fijo);
                    $("#txt_id_cargo_fijo").val(datos.id_cargo_fijo);
                } else {
                    $("#txt_cantidad_cargo_fijo").val("");
                    $("#txt_cargo_fijo").val("");
                    $("#txt_id_cargo_fijo").val("");
                    if ($("#grid_costo_metros").DataTable().rows().count() > 0) {
                        alerta.aviso("alerta", "No se pudo cargar el cargo fijo");
                    }
                }
            },
            error: function(error) {
                respuesta = JSON.parse(error["responseText"]);
                alerta.error("alerta", respuesta.message);
            }
        });
    }
}

$(document).ready(function() {
	$("#txt_id_costo_metros").prop("disabled", true);
	des_habilitar(true, false);
	llenar_cmb_apr();
    llenar_cmb_diametro();

	$("#btn_nuevo").on("click", function() {
        if ($("#cmb_apr").val() != "" && $("#cmb_diametro").val() != "") {
            des_habilitar(false, true);
            limpiar();
            $("#btn_modificar").prop("disabled", true);
            $("#btn_eliminar").prop("disabled", true);
        } else {
            alerta.aviso("alerta", "Seleccionar una APR y un diámetro");
        }
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Eliminar costo de metros?",
            text: "¿Está seguro de eliminar costo de estos metros?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_costo_metros = $("#txt_id_costo_metros").val();
                eliminar_costo_metros(result.value, id_costo_metros);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_costo_metros").valid()) {
            guardar_costo_metros();
        }
    });

    $("#btn_cancelar").on("click", function() {
        limpiar();
        des_habilitar(true, false);
        datatable_enabled = true;
    });

    $("#cmb_apr").on("change", function() {
    	actualizar_grid();
    });

    $("#cmb_diametro").on("change", function() {
        actualizar_grid();
    });

    $("#txt_cargo_fijo").on("blur", function() {
        var numero = peso.quitar_formato(this.value);
        this.value = peso.formateaNumero(numero);
    });

    $("#txt_costo").on("blur", function() {
        var numero = peso.quitar_formato(this.value);
        this.value = peso.formateaNumero(numero);
    });

    $("#form_costo_metros").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_cantidad_cargo_fijo: {
                required: true,
                digits: true,
                maxlength: 2
            },
            txt_cargo_fijo: {
                required: true,
                number: true,
                maxlength: 11
            },
            cmb_apr: {
                required: true
            },
            txt_desde: {
                required: true,
                digits: true,
                maxlength: 11
            },
            txt_hasta: {
                required: true,
                digits: true,
                maxlength: 11
            },
            txt_costo: {
                required: true,
                number: true,
                maxlength: 11
            }
        },
        messages: {
            txt_cantidad_cargo_fijo: {
                required: "Debe ingresar cantidad de metros que cubre el cargo fijo",
                digits: "Solo números",
                maxlength: "Máximo 2 caracteres"
            },
            txt_cargo_fijo: {
                required: "Debe ingresar un cargo fijo",
                number: "Solo números",
                maxlength: "Máximo 11 caracteres"
            },
            cmb_apr: {
                required: "Seleccione APR"
            },
            txt_desde: {
                required: "Inicio de metros es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 caracteres"
            },
            txt_hasta: {
                required: "Fin de metros es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 caracteres"
            },
            txt_costo: {
                required: "Costo de metros es obligatorio",
                number: "Solo números",
                maxlength: "Máximo 11 caracteres"
            }
        }
    });

    var grid_costo_metros = $("#grid_costo_metros").DataTable({
		responsive: true,
        paging: true,
        // scrollY: '50vh',
        // scrollCollapse: true,
        destroy: true, 
        order: [[ 3, "asc" ]],
        select: {
            toggleable: false
        },
        // ajax: base_url + "/Configuracion/Ctrl_costo_metros/datatable_costo_metros",
        orderClasses: true,
        columns: [
            { "data": "id_costo_metros" },
            { "data": "id_apr" },
            { "data": "apr" },
            { "data": "id_diametro" },
            { "data": "diametro" },
            { "data": "desde" },
            { "data": "hasta" },
            { "data": "costo" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_costo_metros",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_costo_metros btn btn-warning' title='Traza Costo Metros'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [0, 1, 3], "visible": false, "searchable": false }
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
            "select": {
                "rows": "<br/>%d Perfiles Seleccionados"
            },
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
	});

	$("#grid_costo_metros tbody").on("click", "tr", function () {
		if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_costo_metros.row(tr).data();
	        mostrar_datos_costo_metros(data);
	        des_habilitar(true, false);
	        $("#btn_modificar").prop("disabled", false);
	        $("#btn_eliminar").prop("disabled", false);
    	}
    });

    $("#grid_costo_metros tbody").on("click", "button.traza_costo_metros", function () {
        if (datatable_enabled) {
	        $("#divContenedorTrazaCostoMetros").load(
	            base_url + "/Configuracion/Ctrl_costo_metros/v_costo_metros_traza"
	        ); 

	        $('#dlg_traza_costo_metros').modal('show');
	    }
    });
});