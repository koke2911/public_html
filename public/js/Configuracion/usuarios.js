var base_url = $("#txt_base_url").val();
var datatable_enabled = true;
var user_block;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_bloquear").prop("disabled", a);
    $("#btn_permisos").prop("disabled", a);
    $("#btn_reset").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_usuario").prop("disabled", a);
    $("#cmb_apr").prop("disabled", a);
    $("#txt_nombres").prop("disabled", a);
    $("#txt_apepat").prop("disabled", a);
    $("#txt_apemat").prop("disabled", a);
    $("#cmb_region").prop("disabled", a);
    $("#cmb_provincia").prop("disabled", a);
    $("#cmb_comuna").prop("disabled", a);
    $("#txt_calle").prop("disabled", a);
    $("#txt_numero").prop("disabled", a);
    $("#txt_resto_direccion").prop("disabled", a);
    $("#chk_punto_blue").prop("disabled", a);
}

function mostrar_datos_usuario(data) {
    $("#txt_id_usuario").val(data["id_usuario"]);
    $("#txt_usuario").val(data["usuario"]);
    $("#cmb_apr").val(data["id_apr"]);
    $("#txt_nombres").val(data["nombres"]);
    $("#txt_apepat").val(data["ape_paterno"]);
    $("#txt_apemat").val(data["ape_materno"]);
    $("#cmb_region").val(data["id_region"]);
    $("#cmb_provincia").val(data["id_provincia"]);
    $("#cmb_comuna").val(data["id_comuna"]);
    $("#txt_calle").val(data["calle"]);
    $("#txt_numero").val(data["numero"]);
    $("#txt_resto_direccion").val(data["resto_direccion"]);
    if (data["punto_blue"] == 1) {
        $("#chk_punto_blue").prop("checked", true);
    } else {
        $("#chk_punto_blue").prop("checked", false);
    }
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

function llenar_cmb_region() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_region",
    }).done( function(data) {
        $("#cmb_region").html('');

        var opciones_region = "<option value=\"\">Seleccione una region</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones_region += "<option value=\"" + data[i].id + "\">" + data[i].region + "</option>";
        }

        $("#cmb_region").append(opciones_region);
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

function guardar_usuario() {
    var id_usuario = $("#txt_id_usuario").val();
    var usuario = $("#txt_usuario").val();
    var id_apr = $("#cmb_apr").val();
    var nombres = $("#txt_nombres").val();
    var apepat = $("#txt_apepat").val();
    var apemat = $("#txt_apemat").val();
    var id_comuna = $("#cmb_comuna").val();
    var calle = $("#txt_calle").val();
    var numero = $("#txt_numero").val();
    var resto_direccion = $("#txt_resto_direccion").val();
    var punto_blue = $("#chk_punto_blue").prop("checked") ? 1 : 0;

    $.ajax({
        url: base_url + "/Configuracion/Ctrl_usuarios/guardar_usuario",
        type: "POST",
        async: false,
        data: {
            id_usuario: id_usuario,
            usuario: usuario,
            id_apr: id_apr,
            nombres: nombres,
            apepat: apepat,
            apemat: apemat,
            id_comuna: id_comuna,
            calle: calle,
            numero: numero,
            resto_direccion: resto_direccion,
            punto_blue: punto_blue
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta.substring(0, 1) == OK) {
                $("#grid_usuarios").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_usuarios/datatable_usuarios");
                $("#form_usuarios")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Usuario guardado con éxito");
                $("#datosUsuario").collapse("hide");
                datatable_enabled = true;
                if (respuesta.length > 1) {
                    var array = respuesta.split("--");
                    var clave = array[1];
                    Swal.fire({
                        icon: "success",
                        title: "Clave",
                        text: clave,
                        footer: "Copia la clave para enviar"
                    });
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

function convertirMayusculas(texto) {
    var text = texto.toUpperCase().trim();
    return text;
}

function bloquear_usuario(opcion, observacion) {
    var id_usuario = $("#txt_id_usuario").val();
    
    $.ajax({
        url: base_url + "/Configuracion/Ctrl_usuarios/bloquear_usuario",
        type: "POST",
        async: false,
        data: { 
            id_usuario: id_usuario,
            observacion: observacion,
            opcion: opcion
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "bloquear") {
                    alerta.ok("alerta", "Usuario bloqueado con éxito");
                } else {
                    alerta.ok("alerta", "Usuario desbloqueado con éxito");
                }

                $("#grid_usuarios").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_usuarios/datatable_usuarios");
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

function resetear_clave() {
    var id_usuario = $("#txt_id_usuario").val();

    $.ajax({
        url: base_url + "/Configuracion/Ctrl_usuarios/resetear_clave",
        type: "POST",
        async: false,
        data: { id_usuario: id_usuario },
        success: function(respuesta) {
            const OK = 1;

            var array = respuesta.split("--");
            respuesta = array[0];

            if (respuesta == OK) {
                var clave = array[1];

                Swal.fire({
                    icon: "success",
                    title: "Clave",
                    text: clave,
                    footer: "Copia la clave para enviar"
                });
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
    $("#txt_id_usuario").prop("disabled", true);
    des_habilitar(true, false);
    llenar_cmb_apr();
    llenar_cmb_region();
    llenar_cmb_provincia();
    llenar_cmb_comuna();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_usuarios")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_bloquear").prop("disabled", true);
        $("#btn_permisos").prop("disabled", true);
        $("#btn_reset").prop("disabled", true);
        $("#datosUsuario").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_bloquear").prop("disabled", true);
        $("#btn_permisos").prop("disabled", true);
        $("#btn_reset").prop("disabled", true);
        datatable_enabled = false;
        $("#datosUsuario").collapse("show");
    });

    $("#btn_bloquear").on("click", function() {
        const BLOQUEADO = 2;
        var usuario = $("#txt_usuario").val();

        if (user_block == BLOQUEADO) {
            Swal.fire({
                title: "¿Desbloquear Usuario?",
                text: "¿Está seguro de desbloquear a " + usuario + "?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                   bloquear_usuario("desbloquear");
                }
            });
        } else {
            Swal.fire({
                title: "¿Bloquear Usuario?",
                text: "¿Está seguro de bloquear a " + usuario + "?",
                input: 'text',
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                   bloquear_usuario("bloquear", result.value);
                }
            });
        }
    });

    $("#btn_permisos").on("click", function() {
        $("#divContenedorPermisos").load(
            base_url + "/Configuracion/Ctrl_usuarios/v_permisos",
        );

        $('#dlg_permisos').modal('show');
    });

    $("#btn_reset").on("click", function() {
        var usuario = $("#txt_usuario").val();

        Swal.fire({
            title: "¿Resetear Clave?",
            text: "¿Está seguro de resetear la clave de " + usuario + "?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
               resetear_clave();
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_usuarios").valid()) {
            guardar_usuario();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_usuarios")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosUsuario").collapse("hide");
    });

    $("#cmb_region").on("change", function() {
        llenar_cmb_provincia();
    });

    $("#cmb_provincia").on("change", function() {
        llenar_cmb_comuna();
    });

    $("#txt_nombres").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $("#txt_apepat").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $("#txt_apemat").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $("#txt_calle").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $("#txt_resto_direccion").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $.validator.addMethod("letras", function(value, element) {
        return this.optional(element) || /^[a-zA-ZñÑáÁéÉíÍóÓúÚ ]*$/.test(value);
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_usuarios").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_usuario: {
                required: true,
                maxlength: 45
            },
            cmb_apr: {
                required: true
            },
            txt_nombres: {
                required: true,
                letras: true,
                maxlength: 45 
            },
            txt_apepat: {
                required: true,
                letras: true,
                maxlength: 45
            },
            txt_apemat: {
                required: true,
                letras: true,
                maxlength: 45
            },
            txt_calle: {
                letras: true,
                maxlength: 60
            },
            txt_numero: {
                digits: true,
                maxlength: 6
            },
            txt_resto_direccion: {
                charspecial: true,
                maxlength: 200
            }
        },
        messages: {
            txt_usuario: {
                required: "El usuario es obligatorio",
                maxlength: "Máximo 45 caracteres"
            },
            cmb_apr: {
                required: "La APR es obligatoria"
            },
            txt_nombres: {
                required: "Los nombre(s) es obligatorio(s)",
                letras: "Solo letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_apepat: {
                required: "El apellido paterno es obligatorio",
                letras: "Solo letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_apemat: {
                required: "Los apellido materno es obligatorio",
                letras: "Solo letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_calle: {
                letras: "Solo letras",
                maxlength: "Máximo 60 caracteres"
            },
            txt_numero: {
                digits: "Solo números",
                maxlength: "Máximo 6 caracteres"
            },
            txt_resto_direccion: {
                charspecial: "Caracteres especiales no válidos",
                maxlength: "Máximo 200 caracteres"
            }
        }
    });

    var grid_usuarios = $("#grid_usuarios").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Configuracion/Ctrl_usuarios/datatable_usuarios",
        orderClasses: true,
        columns: [
            { "data": "id_usuario" },
            { "data": "usuario" },
            { "data": "id_apr" },
            { "data": "apr" },
            { "data": "nombres" },
            { "data": "ape_paterno" },
            { "data": "ape_materno" },
            { "data": "nombre_usuario" },
            { "data": "id_region" },
            { "data": "id_provincia" },
            { "data": "id_comuna" },
            { "data": "comuna" },
            { "data": "calle" },
            { "data": "numero" },
            { "data": "resto_direccion" },
            { "data": "punto_blue" },
            { "data": "id_estado" },
            { "data": "estado" },
            { "data": "usuario_reg" },
            { "data": "fecha" },
            { 
                "data": "id_usuario",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_usuario btn btn-warning' title='Traza Usuario'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [0, 2, 4, 5, 6, 8, 9, 10, 12, 13, 14, 15, 16], "visible": false, "searchable": false }
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

    $("#grid_usuarios tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_usuarios.row(tr).data();
            mostrar_datos_usuario(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_bloquear").prop("disabled", false);
            $("#btn_permisos").prop("disabled", false);
            $("#btn_reset").prop("disabled", false);
            $("#datosUsuario").collapse("hide");

            user_block = data["id_estado"];

            if (data["id_estado"] == 2) {
                $("#btn_bloquear").html("<i class=\"fas fa-unlock\"></i> Desbloquear");
            } else {
                $("#btn_bloquear").html("<i class=\"fas fa-user-lock\"></i> Bloquear");
            }
        }
    });

    $("#grid_usuarios tbody").on("click", "button.traza_usuario", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaUsuario").load(
                base_url + "/Configuracion/Ctrl_usuarios/v_usuario_traza"
            ); 

            $('#dlg_traza_usuario').modal('show');
        }
    });
});