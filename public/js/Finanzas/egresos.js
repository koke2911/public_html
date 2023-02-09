var base_url = $("#txt_base_url").val();

function guardar_egreso() {
    var monto = peso.quitar_formato($("#txt_monto").val());
    var fecha_egreso = $("#dt_fecha_egreso").val();
    var id_tipo_egreso = $("#cmb_tipo_egreso").val();
    var id_entidad = $("#txt_id_proveedor").val();
    var id_motivo = $("#txt_id_motivo").val();
    var id_cuenta = $("#txt_id_cuenta").val();
    var n_transaccion = $("#txt_n_transaccion").val();
    var observaciones = $("#txt_observaciones").val();

    if ($("#rd_proveedor").is(":checked")) {
        var tipo_entidad = "Proveedor";
    } else if ($("#rd_funcionario").is(":checked")) {
        var tipo_entidad = "Funcionario";
    } else if ($("#rd_socio").is(":checked")) {
        var tipo_entidad = "Socio";
    }

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_egresos/guardar_egreso",
        type: "POST",
        async: false,
        data: {
            monto: monto,
            fecha_egreso: fecha_egreso,
            id_tipo_egreso: id_tipo_egreso,
            tipo_entidad: tipo_entidad,
            id_entidad: id_entidad,
            id_motivo: id_motivo,
            id_cuenta: id_cuenta,
            n_transaccion: n_transaccion,
            observaciones: observaciones
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#form_egresos")[0].reset();
                alerta.ok("alerta", "Egreso guardado con éxito");
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

function convertirMayusculas(texto) {
    var text = texto.toUpperCase().trim();
    return text;
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

function llenar_cmb_tipo_egreso() {
    if ($("#txt_id_egreso_egre").val()) {
        var opcion = 1;
    } else {
        var opcion = 0;
    }

    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_egresos/llenar_cmb_tipo_egreso/" + opcion,
    }).done( function(data) {
        $("#cmb_tipo_egreso").html('');

        var opciones = "<option value=\"\">Seleccione un tipo de egreso</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].tipo_egreso + "</option>";
        }

        $("#cmb_tipo_egreso").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function mostrar_datos_egreso() {
    var id_egreso = $("#txt_id_egreso_egre").val(); 

    $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_egresos/datos_egreso",
        data: { id_egreso: id_egreso }
    }).done( function(data) {
        $("#cmb_tipo_egreso").val(data[0].id_tipo_egreso);
        $("#dt_fecha_egreso").val(data[0].fecha);
        $("#txt_monto").val(data[0].monto);
        $("#txt_id_cuenta").val(data[0].id_cuenta);
        $("#txt_banco").val(data[0].nombre_banco);
        $("#txt_tipo_cuenta").val(data[0].tipo_cuenta);
        $("#txt_n_cuenta").val(data[0].n_cuenta);
        $("#txt_rut").val(data[0].rut_cuenta);
        $("#txt_nombre").val(data[0].nombre_cuenta);
        $("#txt_email").val(data[0].email);
        $("#txt_n_transaccion").val(data[0].n_transaccion);
        $("#txt_id_proveedor").val(data[0].id_entidad);
        $("#txt_rut_proveedor").val(data[0].rut_entidad);
        $("#txt_razon_social").val(data[0].nombre_entidad);
        $("#txt_id_motivo").val(data[0].id_motivo);
        $("#txt_motivo").val(data[0].motivo);
        $("#txt_observaciones").val(data[0].observaciones);

        switch(data[0].tipo_entidad) {
            case "Proveedor":
                $("#rd_proveedor").prop("checked", true);
                break;
            case "Socio":
                $("#rd_socio").prop("checked", true);
                break;
            case "Funcionario":
                $("#rd_funcionario").prop("checked", true);
                break;
        }

    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

$(document).ready(function() {
    $("#txt_id_proveedor").prop("readonly", true);
    $("#txt_rut_proveedor").prop("readonly", true);
    $("#txt_razon_social").prop("readonly", true);
    $("#txt_id_motivo").prop("readonly", true);
    $("#txt_motivo").prop("readonly", true);
    $("#txt_id_cuenta").prop("readonly", true);
    $("#txt_banco").prop("readonly", true);
    $("#txt_tipo_cuenta").prop("readonly", true);
    $("#txt_n_cuenta").prop("readonly", true);
    $("#txt_rut").prop("readonly", true);
    $("#txt_nombre").prop("readonly", true);
    $("#txt_email").prop("readonly", true);
    llenar_cmb_tipo_egreso();

    $("#btn_guardar").on("click", function() {
        if ($("#form_egresos").valid()) {
            guardar_egreso();
        }
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_egresos")[0].reset();
    });

    $("#txt_monto").on("blur", function() {
        var numero = peso.quitar_formato($(this).val());
        $(this).val(peso.formateaNumero(numero));
    });

    $("#dt_fecha_egreso").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#btn_buscar_proveedor").on("click", function() {
        $("#divContenedorBuscador").html("");

        if ($("#rd_proveedor").is(":checked")) {
            $("#tlt_buscador").text("Buscar Proveedor");
            var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_proveedor";
        } else if ($("#rd_funcionario").is(":checked")) {
            $("#tlt_buscador").text("Buscar Funcionario");
            var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_funcionario";
        } else if ($("#rd_socio").is(":checked")) {
            $("#tlt_buscador").text("Buscar Socio");
            var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_socio";
        }

        $("#divContenedorBuscador").load(url_buscador);
        $('#dlg_buscador').modal('show');
    });

    $("#btn_buscar_cuenta").on("click", function() {
        $("#tlt_buscador").text("Buscar Cuenta");
        $("#divContenedorBuscador").html("");
        $("#divContenedorBuscador").load(
            base_url + "/Finanzas/Ctrl_egresos/v_buscar_cuenta"
        ); 

        $('#dlg_buscador').modal('show');
    });

    $("#btn_buscar_motivo").on("click", function() {
        $("#tlt_buscador").text("Buscar Motivo");
        $("#divContenedorBuscador").html("");
        $("#divContenedorBuscador").load(
            base_url + "/Finanzas/Ctrl_ingresos/v_buscar_motivo"
        ); 

        $('#dlg_buscador').modal('show');
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_egresos").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            cmb_tipo_egreso: {
                required: true
            },
            txt_monto: {
                required: true,
                maxlength: 12
            },
            dt_fecha_egreso: {
                required: true
            },
            txt_id_proveedor: {
                required: true
            },
            txt_id_motivo: {
                required: true
            },
            txt_n_transaccion: {
                digits: true,
                maxlength: 45
            },
            txt_observaciones: {
                charspecial: true,
                maxlength: 200
            }
        },
        messages: {
            cmb_tipo_egreso: {
                required: "El tipo de egreso es obligatorio"
            },
            txt_monto: {
                required: "El monto es obligatorio",
                maxlength: "Máximo 12 caracteres"
            },
            dt_fecha_egreso: {
                required: "La fecha es obligatoria"
            },
            txt_id_proveedor: {
                required: "Proveedor es obligatorio"
            },
            txt_id_motivo: {
                required: "Motivo es obligatorio"
            },
            txt_n_transaccion: {
                digits: "Solo números",
                maxlength: "Máximo 45 caracteres"
            },
            txt_observaciones: {
                charspecial: "Caracteres no permitidos",
                maxlength: "Máximo 200 caracteres"
            }
        }
    });

    if ($("#txt_id_egreso_egre").length > 0) {
        $("#btn_buscar_proveedor").prop("disabled", true);
        $("#btn_buscar_cuenta").prop("disabled", true);
        $("#btn_buscar_motivo").prop("disabled", true);
        mostrar_datos_egreso();
    }
});