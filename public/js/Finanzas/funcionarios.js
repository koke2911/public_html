var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_rut").prop("disabled", a);
    $("#cmb_sexo").prop("disabled", a);
    $("#txt_nombres").prop("disabled", a);
    $("#txt_ape_pat").prop("disabled", a);
    $("#txt_ape_mat").prop("disabled", a);
    $("#cmb_region").prop("disabled", a);
    $("#cmb_provincia").prop("disabled", a);
    $("#cmb_comuna").prop("disabled", a);
    $("#txt_calle").prop("disabled", a);
    $("#txt_numero").prop("disabled", a);
    $("#txt_resto_direccion").prop("disabled", a);
}

function mostrar_datos_funcionarios(data) {
    $("#txt_id_funcionario").val(data["id_funcionario"]);
    $("#txt_rut").val(Fn.formatear(data["rut"]));
    $("#cmb_sexo").val(data["id_sexo"]);
    $("#txt_nombres").val(data["nombres"]);
    $("#txt_ape_pat").val(data["ape_pat"]);
    $("#txt_ape_mat").val(data["ape_mat"]);
    $("#cmb_region").val(data["id_region"]);
    $("#cmb_provincia").val(data["id_provincia"]);
    $("#cmb_comuna").val(data["id_comuna"]);
    $("#txt_calle").val(data["calle"]);
    $("#txt_numero").val(data["numero"]);
    $("#txt_resto_direccion").val(data["resto_direccion"]);
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

function guardar_funcionario() {
    var id_funcionario = $("#txt_id_funcionario").val();
    var rut = $("#txt_rut").val();
    rut = rut.split('.').join('');
    var sexo = $("#cmb_sexo").val();
    var nombres = $("#txt_nombres").val();
    var ape_pat = $("#txt_ape_pat").val();
    var ape_mat = $("#txt_ape_mat").val();
    var region = $("#cmb_region").val();
    var provincia = $("#cmb_provincia").val();
    var comuna = $("#cmb_comuna").val();
    var calle = $("#txt_calle").val();
    var numero = $("#txt_numero").val();
    var resto_direccion = $("#txt_resto_direccion").val();

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_funcionarios/guardar_funcionario",
        type: "POST",
        async: false,
        data: {
            id_funcionario: id_funcionario,
            rut: rut,
            id_sexo: sexo,
            nombres: nombres,
            ape_pat: ape_pat,
            ape_mat: ape_mat,
            id_comuna: comuna,
            calle: calle,
            numero: numero,
            resto_direccion: resto_direccion
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_funcionarios").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_funcionarios/datatable_funcionarios");
                $("#form_funcionario")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Funcionario guardado con éxito");
                $("#datosFuncionario").collapse("hide");
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

function cambiar_estado_funcionario(estado, observacion, id_funcionario) {
    $.ajax({
        url: base_url + "/Finanzas/Ctrl_funcionarios/cambiar_estado_funcionario",
        type: "POST",
        async: false,
        data: {
            id_funcionario: id_funcionario,
            estado: estado,
            observacion: observacion
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_funcionarios").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_funcionarios/datatable_funcionarios");
                $("#form_funcionario")[0].reset();
                des_habilitar(true, false);
                if (estado == "eliminar") {
                    alerta.ok("alerta", "Funcionario eliminado con éxito");
                } else {
                    $('#dlg_reciclar_funcionario').modal('hide');
                    alerta.ok("alerta", "Funcionario recuperado con éxito");
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

$(document).ready(function() {
    $("#txt_id_funcionario").prop("disabled", true);
    des_habilitar(true, false);
    llenar_cmb_region();
    llenar_cmb_provincia();
    llenar_cmb_comuna();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_funcionario")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosFuncionario").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosFuncionario").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var nombres = $("#txt_nombres").val();
        var ape_pat = $("#txt_ape_pat").val();
        var ape_mat = $("#txt_ape_mat").val();
        var nombre_completo = nombres + " " + ape_pat + " " + ape_mat;
        
        Swal.fire({
            title: "¿Eliminar Funcionario?",
            text: "¿Está seguro de eliminar a " + nombre_completo + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_funcionario = $("#txt_id_funcionario").val();
                cambiar_estado_funcionario("eliminar", result.value, id_funcionario);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_funcionario").valid()) {
            guardar_funcionario();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_funcionario")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosFuncionario").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarFuncionario").load(
            base_url + "/Finanzas/Ctrl_funcionarios/v_funcionarios_reciclar"
        ); 

        $('#dlg_reciclar_funcionario').modal('show');
    });

    $("#txt_rut").on("blur", function() {
        if (this.value != "") {
            if (Fn.validaRut(Fn.formatear(this.value))) {
                this.value = convertirMayusculas(Fn.formatear(this.value));
            } else {
                alerta.error("alerta", "RUT incorrecto");
                this.value = "";
                setTimeout(function() { $("#txt_rut").focus(); }, 100);
            }
        }
    });

    $("#cmb_region").on("change", function() {
        llenar_cmb_provincia();
    });

    $("#cmb_provincia").on("change", function() {
        llenar_cmb_comuna();
    });

    $("#txt_nombres").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_ape_pat").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_ape_mat").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_calle").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_resto_direccion").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $.validator.addMethod("rut", function(value, element) {
        return this.optional(element) || /^[0-9-.Kk]*$/.test(value);
    });

    $.validator.addMethod("letras", function(value, element) {
        return this.optional(element) || /^[a-zA-ZñÑáÁéÉíÍóÓúÚ ]*$/.test(value);
    });

    $("#form_funcionario").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_rut: {
                rut: true,
                maxlength: 12
            },
            txt_nombres: {
                required: true,
                letras: true,
                maxlength: 45
            },
            txt_ape_pat: {
                required: true,
                letras: true,
                maxlength: 45
            },
            txt_ape_mat: {
                letras: true,
                maxlength: 45
            },
            txt_calle: {
                charspecial: true,
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
            txt_rut: {
                rut: "Solo números o k",
                maxlength: "Máximo 10 caracteres"
            },
            txt_nombres: {
                required: "El nombre de socio es obliatorio",
                letras: "Solo puede ingresar letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_ape_pat: {
                required: "El apellido paterno es obligatorio",
                letras: "Solo puede ingresar letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_ape_mat: {
                letras: "Solo puede ingresar letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_calle: {
                charspecial: "Ha ingresado caracteres no permitdos",
                maxlength: "Máximo 60 caracteres"
            },
            txt_numero: {
                digits: "Solo números",
                maxlength: "Máximo 6 dígitos"
            },
            txt_resto_direccion: {
                charspecial: "Hay caracteres extraños no permitdos",
                maxlength: "Máximo 200 caracteres"
            }
        }
    });

    var grid_funcionarios = $("#grid_funcionarios").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Finanzas/Ctrl_funcionarios/datatable_funcionarios",
        orderClasses: true,
        columns: [
            { "data": "id_funcionario" },
            { "data": "rut" },
            { "data": "nombres" },
            { "data": "ape_pat" },
            { "data": "ape_mat" },
            { "data": "nombre_completo" },
            { "data": "id_sexo" },
            { "data": "id_region" },
            { "data": "id_provincia" },
            { "data": "id_comuna" },
            { "data": "comuna" },
            { "data": "calle" },
            { "data": "numero" },
            { "data": "resto_direccion" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_funcionario",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_funcionario btn btn-warning' title='Traza funcionario'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [0, 2, 3, 4, 6, 7, 8, 9], "visible": false, "searchable": false }
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

    $("#grid_funcionarios tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_funcionarios.row(tr).data();
            mostrar_datos_funcionarios(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosFuncionario").collapse("hide");
        }
    });

    $("#grid_funcionarios tbody").on("click", "button.traza_funcionario", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaFuncionario").load(
                base_url + "/Finanzas/Ctrl_funcionarios/v_funcionario_traza"
            ); 

            $('#dlg_traza_funcionario').modal('show');
        }
    });
});