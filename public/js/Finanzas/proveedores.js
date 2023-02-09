var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_rut_proveedor").prop("disabled", a);
    $("#txt_razon_social").prop("disabled", a);
    $("#txt_giro").prop("disabled", a);
    $("#cmb_region").prop("disabled", a);
    $("#cmb_provincia").prop("disabled", a);
    $("#cmb_comuna").prop("disabled", a);
    $("#txt_direccion_proveedor").prop("disabled", a);
    $("#txt_email_proveedor").prop("disabled", a);
    $("#txt_fono").prop("disabled", a);
}

function mostrar_datos_socios(data) {
    if (data["id_proveedor"] != null) { $("#txt_id_proveedor").val(data["id_proveedor"]); }
    if (data["rut_proveedor"] != null) { $("#txt_rut_proveedor").val(data["rut_proveedor"]); }
    if (data["razon_social"] != null) { $("#txt_razon_social").val(data["razon_social"]); }
    if (data["giro"] != null) { $("#txt_giro").val(data["giro"]); }
    if (data["id_region"] != null) { $("#cmb_region").val(data["id_region"]); }
    if (data["id_provincia"] != null) { $("#cmb_provincia").val(data["id_provincia"]); }
    if (data["id_comuna"] != null) { $("#cmb_comuna").val(data["id_comuna"]); }
    if (data["direccion"] != null) { $("#txt_direccion_proveedor").val(data["direccion"]); }
    if (data["email_proveedor"] != null) { $("#txt_email_proveedor").val(data["email_proveedor"]); }
    if (data["fono"] != null) { $("#txt_fono").val(data["fono"]); }
}

function llenar_cmb_region() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_region",
    }).done( function(data) {
        $("#cmb_region").html('');

        var opciones = "<option value=\"\">Seleccione una region</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].region + "</option>";
        }

        $("#cmb_region").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_provincia() {
    var id_region = $("#cmb_region").val();

    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_provincia/" + id_region,
    }).done( function(data) {
        $("#cmb_provincia").html('');

        var opciones = "<option value=\"\">Seleccione una provincia</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].provincia + "</option>";
        }

        $("#cmb_provincia").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_comuna() {
    var id_provincia = $("#cmb_provincia").val();

    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_comuna/" + id_provincia,
    }).done( function(data) {
        $("#cmb_comuna").html('');

        var opciones = "<option value=\"\">Seleccione una comuna</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].comuna + "</option>";
        }

        $("#cmb_comuna").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function guardar_proveedor() {
    var id_proveedor = $("#txt_id_proveedor").val();
    var rut = $("#txt_rut_proveedor").val();
    rut_proveedor = rut.split('.').join('');
    var razon_social = $("#txt_razon_social").val();
    var giro = $("#txt_giro").val();
    var id_comuna = $("#cmb_comuna").val();
    var direccion_proveedor = $("#txt_direccion_proveedor").val();
    var email_proveedor = $("#txt_email_proveedor").val();
    var fono = $("#txt_fono").val();

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_proveedores/guardar_proveedor",
        type: "POST",
        async: false,
        data: {
            id_proveedor: id_proveedor,
            rut_proveedor: rut_proveedor,
            razon_social: razon_social,
            giro: giro,
            id_comuna: id_comuna,
            direccion_proveedor: direccion_proveedor,
            email_proveedor: email_proveedor,
            fono: fono
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_proveedores").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_proveedores/datatable_proveedores");
                $("#form_proveedor")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Prveedor guardado con éxito");
                $("#datosProveedor").collapse("hide");
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

function cambiar_estado_proveedor(observacion, id_proveedor, estado) {
    $.ajax({
        url: base_url + "/Finanzas/Ctrl_proveedores/cambiar_estado_proveedor",
        type: "POST",
        async: false,
        data: {
            id_proveedor: id_proveedor,
            estado: estado,
            observacion: observacion
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_proveedores").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_proveedores/datatable_proveedores");
                $("#form_proveedor")[0].reset();
                des_habilitar(true, false);
                if (estado == "eliminar") {
                    alerta.ok("alerta", "Proveedor eliminado con éxito");
                } else {
                    $('#dlg_reciclar_proveedor').modal('hide');
                    alerta.ok("alerta", "Proveedor recuperado con éxito");
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

$(document).ready(function() {
    $("#txt_id_proveedor").prop("disabled", true);
    des_habilitar(true, false);
    llenar_cmb_region();
    llenar_cmb_provincia();
    llenar_cmb_comuna();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_proveedor")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosProveedor").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosProveedor").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var razon_social = $("#txt_razon_social").val();

        Swal.fire({
            title: "¿Eliminar Proveedor?",
            text: "¿Está seguro de eliminar a " + razon_social + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_proveedor = $("#txt_id_proveedor").val();
                cambiar_estado_proveedor(result.value, id_proveedor, "eliminar");
            }
        });
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarProveedor").load(
            base_url + "/Finanzas/Ctrl_proveedores/v_proveedor_reciclar"
        ); 

        $('#dlg_reciclar_proveedor').modal('show');
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_proveedor").valid()) {
            guardar_proveedor();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_proveedor")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosProveedor").collapse("hide");
    });

    $("#txt_rut_proveedor").on("blur", function() {
        if ($(this).val() != "") {
            if (Fn.validaRut(Fn.formatear($(this).val()))) {
                $(this).val(convertirMayusculas(Fn.formatear($(this).val())));
            } else {
                alerta.error("alerta", "RUT incorrecto");
                $(this).val("");
                setTimeout(function() { $("#txt_rut_proveedor").focus(); }, 100);
            }
        }
    });

    $("#cmb_region").on("change", function() {
        llenar_cmb_provincia();
    });

    $("#cmb_provincia").on("change", function() {
        llenar_cmb_comuna();
    });

    $("#txt_razon_social").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_giro").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_direccion_proveedor").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $.validator.addMethod("rut", function(value, element) {
        return this.optional(element) || /^[0-9-.Kk]*$/.test(value);
    });

    $("#form_proveedor").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_rut_proveedor: {
                required: true,
                rut: true,
                maxlength: 12
            },
            txt_razon_social: {
                required: true,
                charspecial: true,
                maxlength: 45
            },
            txt_giro: {
                charspecial: true,
                maxlength: 100
            },
            txt_direccion_proveedor: {
                charspecial: true,
                maxlength: 200
            },
            txt_fono: {
                digits: true,
                maxlength: 10
            },
            txt_email_proveedor: {
                email: true,
                maxlength: 100
            }
        },
        messages: {
            txt_rut_proveedor: {
                required: "El rut del proveedor es obligatorio",
                rut: "Formato de rut incorrecto",
                maxlength: "Máximo 12 caracteres"
            },
            txt_razon_social: {
                required: "La razón social es obligatoria",
                charspecial: "Tiene caracteres no permitidos",
                maxlength: "Máximo 45 caracteres"
            },
            txt_giro: {
                charspecial: "Tiene caracteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            },
            txt_direccion_proveedor: {
                charspecial: "Tiene caracteres no permitidos",
                maxlength: "Máximo 200 caracteres"
            },
            txt_fono: {
                digits: "Solo números",
                maxlength: "Máximo 10 caracteres"
            },
            txt_email_proveedor: {
                email: "Ingrese un correo electrónico válido",
                maxlength: "Máximo 100 caracteres"
            }
        }
    });

    var grid_proveedores = $("#grid_proveedores").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Finanzas/Ctrl_proveedores/datatable_proveedores",
        orderClasses: true,
        columns: [
            { "data": "id_proveedor" },
            { "data": "rut_proveedor" },
            { "data": "razon_social" },
            { "data": "giro" },
            { "data": "id_region" },
            { "data": "id_provincia" },
            { "data": "id_comuna" },
            { "data": "comuna" },
            { "data": "direccion" },
            { "data": "email" },
            { "data": "fono" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_proveedor",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_proveedor btn btn-warning' title='Traza proveedor'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [0, 4, 5, 6], "visible": false, "searchable": false }
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

    $("#grid_proveedores tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_proveedores.row(tr).data();
            mostrar_datos_socios(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosProveedor").collapse("hide");
        }
    });

    $("#grid_proveedores tbody").on("click", "button.traza_proveedor", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaProveedor").load(
                base_url + "/Finanzas/Ctrl_proveedores/v_proveedor_traza"
            ); 

            $('#dlg_traza_proveedor').modal('show');
        }
    });
});