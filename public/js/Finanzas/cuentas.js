var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#cmb_banco").prop("disabled", a);
    $("#cmb_tipo_cuenta").prop("disabled", a);
    $("#txt_n_cuenta").prop("disabled", a);
    $("#txt_rut_cuenta").prop("disabled", a);
    $("#txt_nombre_cuenta").prop("disabled", a);
    $("#txt_email_cuenta").prop("disabled", a);
}

function mostrar_datos_cuenta(data) {
    if (data["id_cuenta"] != null) { $("#txt_id_cuenta").val(data["id_cuenta"]); }
    if (data["id_banco"] != null) { $("#cmb_banco").val(data["id_banco"]); }
    if (data["id_tipo_cuenta"] != null) { $("#cmb_tipo_cuenta").val(data["id_tipo_cuenta"]); }
    if (data["n_cuenta"] != null) { $("#txt_n_cuenta").val(data["n_cuenta"]); }
    if (data["rut_cuenta"] != null) { $("#txt_rut_cuenta").val(data["rut_cuenta"]); }
    if (data["nombre_cuenta"] != null) { $("#txt_nombre_cuenta").val(data["nombre_cuenta"]); }
    if (data["email_cuenta"] != null) { $("#txt_email_cuenta").val(data["email_cuenta"]); }
}

function guardar_cuenta() {
    var id_cuenta = $("#txt_id_cuenta").val();
    var rut = $("#txt_rut_cuenta").val();
    rut_cuenta = rut.split('.').join('');
    var id_banco = $("#cmb_banco").val();
    var id_tipo_cuenta = $("#cmb_tipo_cuenta").val();
    var n_cuenta = $("#txt_n_cuenta").val();
    var nombre_cuenta = $("#txt_nombre_cuenta").val();
    var email_cuenta = $("#txt_email_cuenta").val();

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_cuentas/guardar_cuenta",
        type: "POST",
        async: false,
        data: {
            id_cuenta: id_cuenta,
            rut_cuenta: rut_cuenta,
            id_banco: id_banco,
            id_tipo_cuenta: id_tipo_cuenta,
            n_cuenta: n_cuenta,
            nombre_cuenta: nombre_cuenta,
            email_cuenta: email_cuenta
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_cuentas").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_cuentas/datatable_cuentas");
                $("#form_cuenta")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Cuenta guardada con éxito");
                $("#datosCuenta").collapse("hide");
                datatable_enabled = true;
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

var Fn = {
    // Valida el rut con su cadena completa "XXXXXXXX-X"
    validaRut : function (rutCompleto) {
        var rutCompleto_ =  rutCompleto.replace(/\./g, "");

        if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rutCompleto_ ))
            return false;
        var tmp     = rutCompleto_.split('-');
        var digv    = tmp[1]; 
        var rut     = tmp[0];
        if ( digv == 'K' ) digv = 'k' ;
        return (Fn.dv(rut) == digv );
    },
    dv : function(T){
        var M=0,S=1;
        for(;T;T=Math.floor(T/10))
            S=(S+T%10*(9-M++%6))%11;
        return S?S-1:'k';
    },
    formatear : function(rut){
        var tmp = this.quitar_formato(rut);
        var rut = tmp.substring(0, tmp.length - 1), f = "";
        while(rut.length > 3) {
            f = '.' + rut.substr(rut.length - 3) + f;
            rut = rut.substring(0, rut.length - 3);
        }
        return ($.trim(rut) == '') ? '' : rut + f + "-" + tmp.charAt(tmp.length-1);
    },
    quitar_formato : function(rut){
        rut = rut.split('-').join('').split('.').join('');
        return rut;
    }
}

function cambiar_estado_cuenta(observacion, id_cuenta, estado) {
    $.ajax({
        url: base_url + "/Finanzas/Ctrl_cuentas/cambiar_estado_cuenta",
        type: "POST",
        async: false,
        data: {
            id_cuenta: id_cuenta,
            estado: estado,
            observacion: observacion
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_cuentas").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_cuentas/datatable_cuentas");
                $("#form_cuenta")[0].reset();
                des_habilitar(true, false);
                if (estado == "eliminar") {
                    alerta.ok("alerta", "Cuenta eliminada con éxito");
                } else {
                    $('#dlg_reciclar_cuenta').modal('hide');
                    alerta.ok("alerta", "Cuenta recuperada con éxito");
                }
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

function llenar_cmb_banco() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_cuentas/llenar_cmb_banco",
    }).done( function(data) {
        $("#cmb_banco").html('');

        var opciones = "<option value=\"\">Seleccione una banco</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].banco + "</option>";
        }

        $("#cmb_banco").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_tipo_cuenta() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_cuentas/llenar_cmb_tipo_cuenta",
    }).done( function(data) {
        $("#cmb_tipo_cuenta").html('');

        var opciones = "<option value=\"\">Seleccione una tipo cuenta</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].tipo_cuenta + "</option>";
        }

        $("#cmb_tipo_cuenta").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}


$(document).ready(function() {
    $("#txt_id_cuenta").prop("disabled", true);
    des_habilitar(true, false);
    llenar_cmb_banco();
    llenar_cmb_tipo_cuenta();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_cuenta")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosCuenta").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosCuenta").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var nombre_cuenta = $("#txt_nombre_cuenta").val();

        Swal.fire({
            title: "¿Eliminar Cuenta?",
            text: "¿Está seguro de eliminar la " + nombre_cuenta + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_cuenta = $("#txt_id_cuenta").val();
                cambiar_estado_cuenta(result.value, id_cuenta, "eliminar");
            }
        });
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarCuenta").load(
            base_url + "/Finanzas/Ctrl_cuentas/v_cuentas_reciclar"
        ); 

        $('#dlg_reciclar_cuenta').modal('show');
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_cuenta").valid()) {
            guardar_cuenta();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_cuenta")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosCuenta").collapse("hide");
    });

    $("#txt_rut_cuenta").on("blur", function() {
        if ($(this).val() != "") {
            if (Fn.validaRut(Fn.formatear($(this).val()))) {
                $(this).val(convertirMayusculas(Fn.formatear($(this).val())));
            } else {
                alerta.error("alerta", "RUT incorrecto");
                $(this).val("");
                setTimeout(function() { $("#txt_rut_cuenta").focus(); }, 100);
            }
        }
    });

    $("#txt_nombre_cuenta").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $.validator.addMethod("rut", function(value, element) {
        return this.optional(element) || /^[0-9-.Kk]*$/.test(value);
    });

    $("#form_cuenta").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_rut_cuenta: {
                required: true,
                rut: true,
                maxlength: 12
            },
            cmb_banco: {
                required: true
            },
            cmb_tipo_cuenta: {
                required: true
            },
            txt_n_cuenta: {
                required: true,
                digits: true,
                maxlength: 20
            },
            txt_nombre_cuenta: {
                required: true,
                charspecial: true,
                maxlength: 100
            },
            txt_email_cuenta: {
                email: true,
                maxlength: 100
            }
        },
        messages: {
            txt_rut_cuenta: {
                required: "El rut es obligatorio",
                rut: "Ingresar RUT válido",
                maxlength: "Máximo 12 caracteres"
            },
            cmb_banco: {
                required: "El banco es obligatorio"
            },
            cmb_tipo_cuenta: {
                required: "El tipo de cuenta es obligatorio"
            },
            txt_n_cuenta: {
                required: "El número de cuenta es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 20 caracteres"
            },
            txt_nombre_cuenta: {
                required: "El nombre es obligatorio",
                charspecial: "Caracteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            },
            txt_email_cuenta: {
                email: "Ingrese un correo electrónico válido",
                maxlength: "Máximo 100 caracteres"
            }
        }
    });

    var grid_cuentas = $("#grid_cuentas").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Finanzas/Ctrl_cuentas/datatable_cuentas",
        orderClasses: true,
        columns: [
            { "data": "id_cuenta" },
            { "data": "id_banco" },
            { "data": "banco" },
            { "data": "id_tipo_cuenta" },
            { "data": "tipo_cuenta" },
            { "data": "n_cuenta" },
            { "data": "rut_cuenta" },
            { "data": "nombre_cuenta" },
            { "data": "email_cuenta" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_cuenta",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_cuenta btn btn-warning' title='Traza Cuenta'><i class=\"fas fa-shoe-prints\"></i></button>";
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
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
	});

    $("#grid_cuentas tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_cuentas.row(tr).data();
            mostrar_datos_cuenta(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosCuenta").collapse("hide");
        }
    });

    $("#grid_cuentas tbody").on("click", "button.traza_cuenta", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaCuenta").load(
                base_url + "/Finanzas/Ctrl_cuentas/v_cuentas_traza"
            ); 

            $('#dlg_traza_cuenta').modal('show');
        }
    });
});