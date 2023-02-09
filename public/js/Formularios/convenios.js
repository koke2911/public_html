var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);
    $("#btn_repactar").prop("disabled", a);

    $("#txt_id_socio").prop("disabled", a);
    $("#txt_rut_socio").prop("disabled", a);
    $("#txt_rol").prop("disabled", a);
    $("#txt_nombre_socio").prop("disabled", a);
    $("#cmb_servicios").prop("disabled", a);
    $("#txt_detalles").prop("disabled", a);
    $("#dt_fecha_servicio").prop("disabled", a);
    $("#txt_n_cuotas").prop("disabled", a);
    $("#dt_fecha_pago").prop("disabled", a);
    $("#txt_costo").prop("disabled", a);
}

function mostrar_datos_convenio(data) {
    $("#txt_id_convenio").val(data["id_convenio"]);
    $("#txt_id_socio").val(data["id_socio"]);
    $("#txt_rut_socio").val(data["rut_socio"]);
    $("#txt_rol").val(data["rol_socio"]);
    $("#txt_nombre_socio").val(data["nombre_socio"]);
    $("#cmb_servicios").val(data["id_servicio"]);
    $("#txt_detalles").val(data["detalle_servicio"]);
    $("#dt_fecha_servicio").val(data["fecha_servicio"]);
    $("#txt_n_cuotas").val(data["numero_cuotas"]);
    $("#dt_fecha_pago").val(data["fecha_pago"]);
    $("#txt_costo").val(peso.formateaNumero(data["costo_servicio"]));
}

function guardar_convenio() {
    var id_convenio = $("#txt_id_convenio").val();
    var id_socio = $("#txt_id_socio").val();
    var id_servicios = $("#cmb_servicios").val();
    var detalles = $("#txt_detalles").val();
    var fecha_servicio = $("#dt_fecha_servicio").val();
    var numero_cuotas = $("#txt_n_cuotas").val();
    var fecha_pago = $("#dt_fecha_pago").val();
    var costo_servicio = peso.quitar_formato($("#txt_costo").val());

    $.ajax({
        url: base_url + "/Formularios/Ctrl_convenios/guardar_convenio",
        type: "POST",
        async: false,
        data: {
            id_convenio: id_convenio,
            id_socio: id_socio,
            id_servicios: id_servicios,
            detalles: detalles,
            fecha_servicio: fecha_servicio,
            numero_cuotas: numero_cuotas,
            fecha_pago: fecha_pago,
            costo_servicio: costo_servicio
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_convenios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_convenios/datatable_convenios");
                $("#form_convenio")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Convenio guardado con éxito");
                $("#datosConvenio").collapse("hide");
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

function eliminar_convenio(opcion, observacion, id_convenio) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Formularios/Ctrl_convenios/eliminar_convenio",
        type: "POST",
        async: false,
        data: { 
            id_convenio: id_convenio,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Convenio eliminado con éxito");
                } else {
                    $('#dlg_reciclar_convenios').modal('hide');
                    alerta.ok("alerta", "Convenio reciclado con éxito");
                }

                $("#grid_convenios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_convenios/datatable_convenios");
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

function llenar_cmb_servicios() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_convenios/llenar_cmb_servicios",
    }).done( function(data) {
        $("#cmb_servicios").html('');

        var opciones = "<option value=\"\">Seleccione un servicio</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].servicio + "</option>";
        }

        $("#cmb_servicios").append(opciones);
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

function sumar_cuotas_faltantes() {
    var total = 0;
    $("#grid_repactar").DataTable().rows().data().each(function (value) {
        if (value.pagado == "NO") {
            total += parseInt(value.valor_cuota);
        }
    });

    $("#txt_deuda_vigente").val(total);
}

function guardar_repactacion() {
    var id_convenio = $("#txt_id_convenio").val();
    var deuda_vigente = $("#txt_deuda_vigente").val();
    var n_cuotas = $("#txt_n_cuotas_repact").val();
    var fecha_pago = $("#dt_fecha_pago_repac").val();

    $.ajax({
        url: base_url + "/Formularios/Ctrl_convenios/guardar_repactacion",
        type: "POST",
        async: false,
        data: {
            id_convenio: id_convenio,
            deuda_vigente: deuda_vigente,
            n_cuotas: n_cuotas,
            fecha_pago: fecha_pago
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_repactar").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_convenios/datatable_repactar_convenio/" + id_convenio);
                $("#form_repactar")[0].reset();
                alerta.ok("alerta_repactacion", "Repactación éxitosa");
            } else {
                alerta.error("alerta_repactacion", respuesta);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta_repactacion", respuesta.message);
        }
    });
}

$(document).ready(function() {
    $("#txt_id_convenio").prop("disabled", true);
    $("#txt_id_socio").prop("readonly", true);
    $("#txt_rut_socio").prop("readonly", true);
    $("#txt_rol").prop("readonly", true);
    $("#txt_nombre_socio").prop("readonly", true);
    $("#txt_deuda_vigente").prop("readonly", true);
    des_habilitar(true, false);
    llenar_cmb_servicios();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_convenio")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosConvenio").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosConvenio").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Eliminar Convenio?",
            text: "¿Está seguro de eliminar el convenio?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_convenio = $("#txt_id_convenio").val();
                eliminar_convenio("eliminar", result.value, id_convenio);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_convenio").valid()) {
            guardar_convenio();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_convenio")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosConvenio").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarConvenios").load(
            base_url + "/Formularios/Ctrl_convenios/v_convenios_reciclar"
        ); 

        $('#dlg_reciclar_convenios').modal('show');
    });

    $("#btn_buscar_socio").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_convenios"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

    $("#dt_fecha_servicio").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#dt_fecha_pago").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es"),
        minDate: new Date()

    });

    $("#txt_detalles").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $("#txt_costo").on("blur", function() {
        var numero = peso.quitar_formato(this.value);
        this.value = peso.formateaNumero(numero);
    });

    $("#btn_repactar").on("click", function() {
        var id_convenio = $("#txt_id_convenio").val();
        $("#form_repactar")[0].reset();

        $("#grid_repactar").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_convenios/datatable_repactar_convenio/" + id_convenio);
        setTimeout(function() {
            sumar_cuotas_faltantes();
        }, 1000);
        $('#dlg_repactar').modal('show');
    });

    $("#dt_fecha_pago_repac").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es"),
        minDate: new Date()

    });

    $("#btn_aceptar_repactar").on("click", function() {
        if ($("#form_repactar").valid()) {
            guardar_repactacion();
        }
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_convenio").validate({
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
            cmb_servicios: {
                required: true,
            },
            txt_detalles: {
                charspecial: true,
                maxlength: 200
            },
            dt_fecha_servicio: {
                required: true
            },
            txt_n_cuotas: {
                required: true,
                digits: true,
                maxlength: 11
            },
            dt_fecha_pago: {
                required: true
            },
            txt_costo: {
                required: true,
                number: true,
                maxlength: 11
            }
        },
        messages: {
            txt_id_socio: {
                required: "Seleccione un socio, botón buscar"
            },
            cmb_servicios: {
                required: "Seleccione un servicio"
            },
            txt_detalles: {
                charspecial: "Hay caracteres no permitidos",
                maxlength: "Máximo 200 caracteres"
            },
            dt_fecha_servicio: {
                required: "Seleccione una fecha de servicio"
            },
            txt_n_cuotas: {
                required: "El número de cuotas es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            dt_fecha_pago: {
                required: "Seleccione una fecha de pago"
            },
            txt_costo: {
                required: "El valor de la cuota es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 caracteres"
            }
        }
    });

    $("#form_repactar").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_deuda_vigente: {
                required: true
            },
            txt_n_cuotas_repact: {
                required: true
            },
            dt_fecha_pago_repac: {
                required: true
            }
        },
        messages: {
            txt_deuda_vigente: {
                required: "La deuda vigente es obligatoria"
            },
            txt_n_cuotas_repact: {
                required: "Ingrese el número de cuotas"
            },
            dt_fecha_pago_repac: {
                required: "Seleccione una fecha"
            }
        }
    });

    var grid_convenios = $("#grid_convenios").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_convenios/datatable_convenios",
        orderClasses: true,
        order: [[ 0, "desc" ]],
        columns: [
            { "data": "id_convenio" },
            { "data": "id_socio" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { "data": "id_servicio" },
            { "data": "servicio" },
            { "data": "detalle_servicio" },
            { "data": "fecha_servicio" },
            { "data": "numero_cuotas" },
            { "data": "fecha_pago" },
            { 
                "data": "costo_servicio",
                "render": function(data, type, row) {
                    var numero = peso.quitar_formato(data);
                    return peso.formateaNumero(numero);
                }
            },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_convenio",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_convenio btn btn-warning' title='Traza Convenio'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [1, 2, 3, 5, 7], "visible": false, "searchable": false }
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

    var grid_convenio_detalle = $("#grid_convenio_detalle").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        orderClasses: true,
        columns: [
            { "data": "id" },
            { "data": "socio" },
            { "data": "fecha_pago" },
            { "data": "numero_cuota" },
            { "data": "valor_cuota" }
        ],
        "columnDefs": [
            { "targets": [0], "visible": false, "searchable": false }
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

    $("#grid_convenios tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_convenios.row(tr).data();
            mostrar_datos_convenio(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#btn_repactar").prop("disabled", false);
            $("#datosConvenio").collapse("hide");
        }
    });

    $("#grid_convenios tbody").on("dblclick", "tr", function () {
        if (datatable_enabled) {
            var id_convenio = $("#txt_id_convenio").val();
            $("#grid_convenio_detalle").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_convenios/datatable_convenio_detalle/" + id_convenio);

            $('#dlg_detalle_convenio').modal('show');
        }
    });

    $("#grid_convenios tbody").on("click", "button.traza_convenio", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaConvenio").load(
                base_url + "/Formularios/Ctrl_convenios/v_convenio_traza"
            ); 

            $('#dlg_traza_convenio').modal('show');
        }
    });

    var grid_repactar = $("#grid_repactar").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        orderClasses: true,
        columns: [
            { "data": "id" },
            { "data": "socio" },
            { "data": "fecha_pago" },
            { "data": "numero_cuota" },
            { "data": "valor_cuota" },
            { "data": "pagado" }
        ],
        "columnDefs": [
            { "targets": [0], "visible": false, "searchable": false }
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
});