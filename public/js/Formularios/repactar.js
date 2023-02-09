var base_url = $("#txt_base_url").val();

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_id_socio").prop("disabled", a);
    $("#txt_rut_socio").prop("disabled", a);
    $("#txt_rol").prop("disabled", a);
    $("#txt_nombre_socio").prop("disabled", a);
    $("#txt_deuda_vigente").prop("disabled", a);
    $("#txt_n_cuotas").prop("disabled", a);
    $("#txt_valor_cuota").prop("disabled", a);
    $("#dt_fecha_pago").prop("disabled", a);
}

function mostrar_datos_convenio(data) {
    $("#txt_id_repactacion").val(data["id_repactacion"]);
    $("#txt_id_socio").val(data["id_socio"]);
    $("#txt_rut_socio").val(data["rut_socio"]);
    $("#txt_rol").val(data["rol_socio"]);
    $("#txt_nombre_socio").val(data["nombre_socio"]);
    $("#txt_deuda_vigente").val(peso.formateaNumero(data["deuda_vigente"]));
    $("#txt_n_cuotas").val(data["numero_cuotas"]);
    $("#txt_valor_cuota").val(data["valor_cuota"]);
    $("#dt_fecha_pago").val(data["fecha_pago"]);
}

function guardar_repactacion() {
    var id_repactacion = $("#txt_id_repactacion").val();
    var id_socio = $("#txt_id_socio").val();
    var deuda_vigente = peso.quitar_formato($("#txt_deuda_vigente").val());
    var numero_cuotas = $("#txt_n_cuotas").val();
    var valor_cuota = $("#txt_valor_cuota").val();
    var fecha_pago = $("#dt_fecha_pago").val();

    var datos_deuda = $("#grid_deuda").DataTable().rows(".selected").data();
    var datos = [];

    for (var i = 0; i < datos_deuda.length; i++) {
        datos.push({
            "id_metros": datos_deuda[i].id_metros,
            "deuda": datos_deuda[i].deuda,
            "fecha_vencimiento": datos_deuda[i].fecha_vencimiento
        });
    }

    $.ajax({
        url: base_url + "/Formularios/Ctrl_repactacion/guardar_repactacion",
        type: "POST",
        async: false,
        data: {
            id_repactacion: id_repactacion,
            id_socio: id_socio,
            deuda_vigente: deuda_vigente,
            numero_cuotas: numero_cuotas,
            valor_cuota: valor_cuota,
            fecha_pago: fecha_pago,
            datos_deuda: datos
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_repactaciones").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_repactacion/datatable_repactaciones");
                $("#form_repactar")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Repactación exitosa");
                $("#datosRepactar").collapse("hide");
                $("#grid_repactacion_detalle").DataTable().clear();
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

function anular_repactacion(observacion, id_repactacion) {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_repactacion/anular_repactacion",
        type: "POST",
        async: false,
        data: { 
            id_repactacion: id_repactacion,
            observacion: observacion,
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                alerta.ok("alerta", "Convenio eliminado con éxito");
                $("#grid_repactaciones").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_repactacion/datatable_repactaciones");
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

function buscar_deuda() {
    var id_socio = $("#txt_id_socio").val();
    $("#grid_deuda").dataTable().fnReloadAjax(base_url + "/Pagos/Ctrl_caja/datatable_deuda_socio/" + id_socio);
}

function sumar_deudas() {
    var total = 0;
    var datos = $("#grid_deuda").DataTable().rows(".selected").data();

    for (var i = 0; i < datos.length; i++) {
        total += parseInt(datos[i].deuda);
    }
    
    $("#txt_deuda_vigente").val(peso.formateaNumero(total));

    if (total == 0) {
        $("#txt_n_cuotas").val(0);
        $("#txt_valor_cuota").val(0);
    }
}

function calcular_cuota() {
    var deuda_vigente = parseInt(peso.quitar_formato($("#txt_deuda_vigente").val()));
    var n_cuotas = parseInt($("#txt_n_cuotas").val());
    var valor_cuota = Math.round(deuda_vigente / n_cuotas);
    $("#txt_valor_cuota").val(valor_cuota);
}

$(document).ready(function() {
    $("#txt_id_repactacion").prop("disabled", true);
    $("#txt_id_socio").prop("readonly", true);
    $("#txt_rut_socio").prop("readonly", true);
    $("#txt_rol").prop("readonly", true);
    $("#txt_nombre_socio").prop("readonly", true);
    $("#txt_deuda_vigente").prop("readonly", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_repactar")[0].reset();

        $("#btn_eliminar").prop("disabled", true);
        $("#datosRepactar").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Anular Repactación?",
            text: "¿Está seguro de anular la repactación?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_repactacion = $("#txt_id_repactacion").val();
                anular_repactacion(result.value, id_repactacion);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_repactar").valid() && $("#form_repactar2").valid()) {
            guardar_repactacion();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_repactar")[0].reset();
        $("#form_repactar2")[0].reset();
        des_habilitar(true, false);
        $("#grid_deuda").DataTable().clear().draw();
        $("#datosRepactar").collapse("hide");
        $("#grid_repactacion_detalle").DataTable().clear().draw();
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarConvenios").load(
            base_url + "/Formularios/Ctrl_repactacion/v_convenios_reciclar"
        ); 

        $('#dlg_reciclar_convenios').modal('show');
    });

    $("#btn_buscar_socio").on("click", function() {
        $("#divContenedorBuscador").load(
            base_url + "/Finanzas/Ctrl_ingresos/v_buscar_socio"
        ); 
        
        $('#dlg_buscador').modal('show');
    });

    $("#dt_fecha_pago").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es"),
        minDate: new Date()

    });

    $("#txt_n_cuotas").on("input", function() {
        var n_cuotas = parseInt($(this).val());
        if (n_cuotas > 0) {
            calcular_cuota();
        }
    });

    var grid_deuda = $("#grid_deuda").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        // ajax: base_url + "/Formularios/Ctrl_arranques/datatable_arranques",
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
            "select": {
                "rows": "<br/>%d Deuda(s) seleccionada(s)"
            },
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
    });

    grid_deuda.on("select", function (e, dt, type, indexes) {
        sumar_deudas();
    }).on("deselect", function (e, dt, type, indexes) {
        sumar_deudas();
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
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
            txt_id_socio: {
                required: true
            }
        },
        messages: {
            txt_id_socio: {
                required: "Seleccione un socio, botón buscar"
            }
        }
    });

    $("#form_repactar2").validate({
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
                required: true,
                number: false,
                maxlength: 20
            },
            txt_n_cuotas: {
                required: true,
                digits: true,
                maxlength: 11
            },
            txt_valor_cuota: {
                required: true,
                digits: true,
                maxlength: 20
            },
            dt_fecha_pago: {
                required: true
            }
        },
        messages: {
            txt_deuda_vigente: {
                required: "El valor de la cuota es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 caracteres"
            },
            txt_n_cuotas: {
                required: "El número de cuotas es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            txt_valor_cuota: {
                required: "El valor de la cuotas es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            dt_fecha_pago: {
                required: "Seleccione una fecha de pago"
            }
        }
    });

    var grid_repactaciones = $("#grid_repactaciones").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_repactacion/datatable_repactaciones",
        orderClasses: true,
        order: [[ 0, "desc" ]],
        columns: [
            { "data": "id_repactacion" },
            { "data": "id_socio" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { 
                "data": "deuda_total",
                "render": function(data, type, row) {
                    var numero = peso.quitar_formato(data);
                    return peso.formateaNumero(numero);
                }
            },
            { "data": "n_cuotas" },
            { "data": "fecha_pago" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_repactacion",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_repactacion btn btn-warning' title='Traza Convenio'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [1, 2, 3], "visible": false, "searchable": false }
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

    var grid_repactacion_detalle = $("#grid_repactacion_detalle").DataTable({
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

    $("#grid_repactaciones tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_repactaciones.row(tr).data();
        mostrar_datos_convenio(data);
        des_habilitar(true, false);
        $("#btn_eliminar").prop("disabled", false);
        $("#datosRepactar").collapse("hide");
    });

    $("#grid_repactaciones tbody").on("dblclick", "tr", function () {
        var id_repactacion = $("#txt_id_repactacion").val();
        $("#grid_repactacion_detalle").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_repactacion/datatable_repactacion_detalle/" + id_repactacion);

        $('#dlg_detalle').modal('show');
    });

    $("#grid_repactaciones tbody").on("click", "button.traza_repactacion", function () {
        $("#divContenedorTraza").load(
            base_url + "/Formularios/Ctrl_repactacion/v_repactar_traza"
        ); 

        $('#dlg_traza').modal('show');
    });
});