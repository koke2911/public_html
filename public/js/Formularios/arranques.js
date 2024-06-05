var base_url = $("#txt_base_url").val();

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_id_socio").prop("disabled", a);
    $("#txt_rut_socio").prop("disabled", a);
    $("#txt_rol").prop("disabled", a);
    $("#txt_nombre_socio").prop("disabled", a);
    $("#btn_buscar_socio").prop("disabled", a);
    $("#cmb_medidor").prop("disabled", a);
    $("#cmb_sector").prop("disabled", a);
    $("#rd_si_a").prop("disabled", a);
    $("#rd_no_a").prop("disabled", a);
    $("#rd_si_cs").prop("disabled", a);
    $("#rd_no_cs").prop("disabled", a);
    $("#rd_si_otros").prop("disabled", a);
    $("#rd_no_otros").prop("disabled", a);
    $("#txt_alcantarillado").prop("disabled", a);
    $("#txt_cuota_socio").prop("disabled", a);
    $("#txt_otros").prop("disabled", a);
    $("#cmb_region").prop("disabled", a);
    $("#cmb_provincia").prop("disabled", a);
    $("#cmb_comuna").prop("disabled", a);
    $("#txt_calle").prop("disabled", a);
    $("#txt_numero").prop("disabled", a);
    $("#txt_resto_direccion").prop("disabled", a);
    $("#cmb_tipo_documento").prop("disabled", a);
    $("#txt_descuento").prop("disabled", a);
    $("#txt_razon_social").prop("disabled", a);
    $("#txt_giro").prop("disabled", a);
    $("#cmb_tarifa").prop("disabled", a);
}

function mostrar_datos_arranque(data) {
    $("#txt_id_arranque").val(data["id_arranque"]);
    $("#txt_id_socio").val(data["id_socio"]);
    $("#txt_rut_socio").val(data["rut_socio"]);
    $("#txt_rol").val(data["rol_socio"]);
    $("#txt_nombre_socio").val(data["nombre_socio"]);
    $("#cmb_medidor").val(data["id_medidor"]);
    $("#cmb_sector").val(data["id_sector"]);
    $("#txt_alcantarillado").val(data["alcantarillado"]);
    $("#txt_cuoto_socio").val(data["id_sector"]);
    $("#txt_otros").val(data["id_sector"]);
    
    $("#lbl_rd_si_sc").removeClass("active");
    $("#lbl_rd_si_a").removeClass("active");
    $("#lbl_rd_si_otros").removeClass("active");
    $("#lbl_rd_no_sc").removeClass("active");
    $("#lbl_rd_no_a").removeClass("active");
    $("#lbl_rd_no_otros").removeClass("active");

    if (data["alcantarillado"] == "SI") { 
        $("#rd_si_a").prop("checked", true);
        $("#rd_no_a").prop("checked", false);
        $("#lbl_rd_si_a").addClass("active");
        $("#txt_alcantarillado").prop("readonly", false);
    } else { 
        $("#rd_no_a").prop("checked", true);
        $("#rd_si_a").prop("checked", false);
        $("#lbl_rd_no_a").addClass("active");
        $("#txt_alcantarillado").prop("readonly", true);
    }
    
    if (data["cuota_socio"] == "SI") { 
        $("#rd_si_cs").prop("checked", true); 
        $("#rd_no_cs").prop("checked", false);
        $("#lbl_rd_si_sc").addClass("active");
        $("#txt_cuota_socio").prop("readonly", false);
    } else { 
        $("#rd_no_cs").prop("checked", true);
        $("#rd_si_cs").prop("checked", false);
        $("#lbl_rd_no_sc").addClass("active");
        $("#txt_cuota_socio").prop("readonly", true);
    }

    if (data["otros"] == "SI") { 
        $("#rd_si_otros").prop("checked", true); 
        $("#rd_no_otros").prop("checked", false);
        $("#lbl_rd_si_otros").addClass("active");
        $("#txt_otros").prop("readonly", false);
    } else { 
        $("#rd_no_otros").prop("checked", true);
        $("#rd_si_otros").prop("checked", false);
        $("#lbl_rd_no_otros").addClass("active");
        $("#txt_otros").prop("readonly", true);
    }

    $("#cmb_region").val(data["id_region"]);
    $("#cmb_provincia").val(data["id_provincia"]);
    $("#cmb_comuna").val(data["id_comuna"]);
    $("#txt_calle").val(data["calle"]);
    $("#txt_numero").val(data["numero"]);
    $("#txt_resto_direccion").val(data["resto_direccion"]);
    $("#cmb_tipo_documento").val(data["id_tipo_documento"]);
    $("#txt_descuento").val(data["descuento"]);
    $("#cmb_tarifa").val(data["tarifa"]);
    $("#txt_razon_social").val(data["razon_social"]);
    $("#txt_giro").val(data["giro"]);
    $("#txt_alcantarillado").val(peso.formateaNumero(data["monto_alcantarillado"]));
    $("#txt_cuota_socio").val(peso.formateaNumero(data["monto_cuota_socio"]));
    $("#txt_otros").val(peso.formateaNumero(data["monto_otros"]));
}

function guardar_arranque() {
    var id_arranque = $("#txt_id_arranque").val();
    var id_socio = $("#txt_id_socio").val();
    var id_sector = $("#cmb_sector").val();
    if ($("#rd_si_a").prop("checked")) { var alcantarillado = 1; } else { var alcantarillado = 0; }
    if ($("#rd_si_cs").prop("checked")) { var cuota_socio = 1; } else { var cuota_socio = 0; }
    if ($("#rd_si_otros").prop("checked")) { var otros = 1; } else { var otros = 0; }
    var id_comuna = $("#cmb_comuna").val();
    var calle = $("#txt_calle").val();
    var numero = $("#txt_numero").val();
    var resto_direccion = $("#txt_resto_direccion").val();
    var tipo_documento = $("#cmb_tipo_documento").val();
    var descuento = $("#txt_descuento").val();
    var id_medidor = $("#cmb_medidor").val();
    var razon_social = $("#txt_razon_social").val();
    var giro = $("#txt_giro").val();
    var monto_alcantarillado = parseInt(peso.quitar_formato($("#txt_alcantarillado").val()), 10);
    var monto_cuota_socio = parseInt(peso.quitar_formato($("#txt_cuota_socio").val()), 10);
    var monto_otros = parseInt(peso.quitar_formato($("#txt_otros").val()), 10);
    var tarifa = $("#cmb_tarifa").val();

    $.ajax({
        url: base_url + "/Formularios/Ctrl_arranques/guardar_arranque",
        type: "POST",
        async: false,
        data: {
            id_arranque: id_arranque,
            id_socio: id_socio,
            id_medidor: id_medidor,
            id_sector: id_sector,
            alcantarillado: alcantarillado,
            cuota_socio: cuota_socio,
            otros: otros,
            id_comuna: id_comuna,
            calle: calle,
            numero: numero,
            resto_direccion: resto_direccion,
            tipo_documento: tipo_documento,
            descuento: descuento,
            razon_social: razon_social,
            giro: giro,
            monto_alcantarillado: monto_alcantarillado,
            monto_cuota_socio: monto_cuota_socio,
            monto_otros: monto_otros,
            tarifa:tarifa
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_arranques").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_arranques/datatable_arranques");
                $("#form_arranque")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Arranque guardado con éxito");
                reset_radio_buttons();
                llenar_cmb_medidores("TODOS");
                $("#datosArranque").collapse("hide");
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

function eliminar_arranque(opcion, observacion, id_arranque) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Formularios/Ctrl_arranques/eliminar_arranque",
        type: "POST",
        async: false,
        data: { 
            id_arranque: id_arranque,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Arranque eliminado con éxito");
                } else {
                    $('#dlg_reciclar_arranque').modal('hide');
                    alerta.ok("alerta", "Arranque reciclado con éxito");
                }

                $("#grid_arranques").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_arranques/datatable_arranques");
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

function llenar_cmb_tarifa() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_tarifa_metros",
    }).done( function(data) {
        $("#cmb_tarifa").html('');

        var opciones = "<option value=\"\">Seleccione una Tarifa</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].tarifa + "</option>";
        }

        $("#cmb_tarifa").append(opciones);
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

function llenar_cmb_sector() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_sector",
    }).done( function(data) {
        $("#cmb_sector").html('');

        var opciones = "<option value=\"\">Seleccione un sector</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].sector + "</option>";
        }

        $("#cmb_sector").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_tipo_documento() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_tipo_documento",
    }).done( function(data) {
        $("#cmb_tipo_documento").html('');

        var opciones = "<option value=\"\">Seleccione tipo de documento</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].tipo_documento + "</option>";
        }

        $("#cmb_tipo_documento").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function reset_radio_buttons() {
    $("#lbl_rd_si_sc").removeClass("active");
    $("#lbl_rd_si_a").removeClass("active");
    $("#lbl_rd_no_sc").removeClass("active");
    $("#lbl_rd_no_a").removeClass("active");

    $("#rd_si_a").prop("checked", false);
    $("#rd_no_a").prop("checked", true);
    $("#lbl_rd_no_a").addClass("active");

    $("#rd_si_cs").prop("checked", false); 
    $("#rd_no_cs").prop("checked", true);
    $("#lbl_rd_no_sc").addClass("active");
}

function llenar_cmb_medidores(opcion) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_medidores",
        data: {
            opcion: opcion
        }
    }).done( function(data) {
        $("#cmb_medidor").html('');
        var opciones = "<option value=\"\">Seleccione un número de medidor</option>";

        for (var i = 0; i < data.length; i++) {
            opciones+='<option value="'+data[i].id+'">'+data[i].numero+'</option>';
        }
        $("#cmb_medidor").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
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

$(document).ready(function() {
    $("#txt_id_arranque").prop("disabled", true);
    $("#txt_id_socio").prop("readonly", true);
    $("#txt_rut_socio").prop("readonly", true);
    $("#txt_rol").prop("readonly", true);
    $("#txt_nombre_socio").prop("readonly", true);
    $("#txt_alcantarillado").prop("readonly", true);
    $("#txt_cuota_socio").prop("readonly", true);
    $("#txt_otros").prop("readonly", true);
    des_habilitar(true, false);
    llenar_cmb_region();
    llenar_cmb_provincia();
    llenar_cmb_comuna();
    llenar_cmb_sector();
    llenar_cmb_tipo_documento();
    llenar_cmb_medidores("TODOS");

    llenar_cmb_tarifa();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_arranque")[0].reset();
        llenar_cmb_medidores("FILTRADO");

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        reset_radio_buttons();
        $("#datosArranque").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosArranque").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Eliminar Arranque?",
            text: "¿Está seguro de eliminar el arranque?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_arranque = $("#txt_id_arranque").val();
                eliminar_arranque("eliminar", result.value, id_arranque);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_arranque").valid()) {
            guardar_arranque();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_arranque")[0].reset();
        des_habilitar(true, false);
        reset_radio_buttons();
        llenar_cmb_medidores("TODOS");

        $("#datosArranque").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarArranque").load(
            base_url + "/Formularios/Ctrl_arranques/v_arranque_reciclar"
        ); 

        $('#dlg_reciclar_arranque').modal('show');
    });

    $("#btn_buscar_socio").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_arranques"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

    $("#cmb_region").on("change", function() {
        llenar_cmb_provincia();
    });

    $("#cmb_provincia").on("change", function() {
        llenar_cmb_comuna();
    });

    $("#txt_calle").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $("#txt_resto_direccion").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $("#rd_si_cs").on("change", function() {
        $('#txt_cuota_socio').prop("readonly", false);
    });

    $("#rd_no_cs").on("change", function() {
        $('#txt_cuota_socio').prop("readonly", true);
        $('#txt_cuota_socio').val("");
    });

    $("#rd_no_a").on("change", function() {
        $('#txt_alcantarillado').prop("readonly", true);
        $('#txt_alcantarillado').val("");
    });

    $("#rd_si_a").on("change", function() {
        $('#txt_alcantarillado').prop("readonly", false);
    });

    $("#rd_si_otros").on("change", function() {
        $('#txt_otros').prop("readonly", false);
    });

    $("#rd_no_otros").on("change", function() {
        $('#txt_otros').prop("readonly", true);
        $('#txt_otros').val("");
    });

    $("#txt_alcantarillado").on("blur", function() {
        $(this).val(peso.formateaNumero(peso.quitar_formato($(this).val())))
    });
    $("#txt_cuota_socio").on("blur", function() {
        $(this).val(peso.formateaNumero(peso.quitar_formato($(this).val())))
    });
    $("#txt_otros").on("blur", function() {
        $(this).val(peso.formateaNumero(peso.quitar_formato($(this).val())))
    });

    $.validator.addMethod("letras", function(value, element) {
        return this.optional(element) || /^[a-zA-ZñÑáÁéÉíÍóÓúÚ ]*$/.test(value);
    });

    $("#form_arranque").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_id_socio: {
                required: true
            },
            cmb_medidor: {
                required: true,
            },
            cmb_sector: {
                required: true
            },
            cmb_tipo_documento: {
                required: true
            },
            cmb_tarifa: {
                required: true
            }
        },
        messages: {
            txt_id_socio: {
                required: "Seleccione un socio, botón buscar"
            },
            cmb_medidor: {
                required: "El número de medidor es obligatorio",
            },
            cmb_sector: {
                required: "Seleccione un sector"
            },
            cmb_tipo_documento: {
                required: "Seleccione un tipo de documento"
            },
            cmb_tarifa: {
                required: "Seleccione una tarifa"
            }
        }
    });

    var grid_arranques = $("#grid_arranques").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_arranques/datatable_arranques",
        orderClasses: true,
        columns: [
            { "data": "id_arranque" },
            { "data": "id_socio" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { "data": "id_medidor" },
            { "data": "n_medidor" },
            { "data": "id_diametro" },
            { "data": "diametro" },
            { "data": "id_sector" },
            { "data": "sector" },
            { "data": "alcantarillado" },
            { "data": "cuota_socio" },
            { "data": "id_region" },
            { "data": "id_provincia" },
            { "data": "id_comuna" },
            { "data": "calle" },
            { "data": "numero" },
            { "data": "resto_direccion" },
            { "data": "id_tipo_documento" },
            { "data": "descuento" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_arranque",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_arranque btn btn-warning' title='Traza Arranque'><i class='fas fa-shoe-prints'></i></button>";
                }
            },
            { "data": "razon_social" },
            { "data": "giro" },
            { "data": "otros" },
            { "data": "monto_alcantarillado" },
            { "data": "monto_cuota_socio" },
            { "data": "monto_otros" },
            {
                "data": "id_arranque",
                "render": function (data, type, row) {
                    return "<button type='button' class='btn_certificado btn btn-primary' title='Imprimir'><i class='fas fa-print'></i></button>"
                }
            }
        ],
        "columnDefs": [
            { "targets": [1, 2, 5, 7, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 24, 25, 26, 27, 28, 29], "visible": false, "searchable": false }
        ],
        dom: 'Bfrtip',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Arranques"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Arranques",
                orientation: 'landscape',
                pageSize: 'TABLOID'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Arranques"
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

    $("#grid_arranques tbody").on("click", "button.btn_certificado", function () {

        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child')) {
            tr = $(tr).prev();
        }
        var data = grid_arranques.row(tr).data();
        var id_arranque = data.id_arranque;
        var id_socio = data.id_socio;
        var n_medidor = data.n_medidor;
        
        window.open(base_url + "/Formularios/Ctrl_arranques/imprime_certificado/" + id_arranque + "/" + id_socio + "/" + n_medidor);
    });
    

    $("#grid_arranques tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_arranques.row(tr).data();
        mostrar_datos_arranque(data);
        des_habilitar(true, false);
        $("#btn_modificar").prop("disabled", false);
        $("#btn_eliminar").prop("disabled", false);
        $("#datosArranque").collapse("hide");
    });

    $("#grid_arranques tbody").on("click", "button.traza_arranque", function () {
        $("#divContenedorTrazaArranque").load(
            base_url + "/Formularios/Ctrl_arranques/v_arranque_traza"
        ); 

        $('#dlg_traza_arranque').modal('show');
    });
});