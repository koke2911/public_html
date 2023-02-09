var base_url = $("#txt_base_url").val();

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

function llenar_cmb_productos(id_producto){
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_egresos/llenar_cmb_productos",
    }).done( function(data) {
        $("#cmb_productos").html('');

        var opciones = "<option value=\"\">Seleccione un producto</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].producto + "</option>";
        }

        $("#cmb_productos").append(opciones);

        if (id_producto != "") {
            $("#cmb_productos").val(id_producto);
        }
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function agregar_fila_producto() {
    var id_producto = $("#cmb_productos").val();
    var producto = $("#cmb_productos option:selected").text();
    var precio = parseInt(peso.quitar_formato($("#txt_precio").val()));
    var cantidad = parseInt($("#txt_cantidad").val());
    var total_producto = precio * cantidad;

    $("#grid_productos_fac").DataTable().rows.add([{
        "id_producto": id_producto,
        "producto": producto,
        "cantidad": cantidad,
        "precio": precio,
        "total_producto": total_producto
    }]);

    $("#grid_productos_fac").DataTable().draw();

    setTimeout(function() {
        $("#grid_productos_fac").DataTable().columns.adjust().draw();
    }, 500);

    $("#form_producto")[0].reset();

    calcular_iva_neto_total();
}

function calcular_iva_neto_total() {
    var neto = $("#grid_productos_fac").DataTable().column(4).data().sum();
    var iva = neto * 0.19;
    var total = neto + iva;
    $("#txt_neto").val(peso.formateaNumero(neto));
    $("#txt_iva").val(peso.formateaNumero(iva));
    $("#txt_total").val(peso.formateaNumero(total));
}

function guardar_producto() {
    var nombre = $("#txt_nombre").val();
    var marca = $("#txt_marca").val();
    var modelo = $("#txt_modelo").val();

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_egresos/guardar_producto",
        type: "POST",
        async: false,
        data: { 
            nombre: nombre,
            marca: marca,
            modelo: modelo
        },
        success: function(respuesta) {
            if (respuesta > 0) {
                llenar_cmb_productos(respuesta);
                alerta.ok("alerta", "Producto agregado de manera exitosa");
                $("#dlg_productos").modal("hide");
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

function guardar_compra() {
    var tipo_documento = $("#cmb_tipo_documento").val();
    var n_documento = $("#txt_n_documento").val();
    var fecha_documento = $("#dt_fecha_documento").val();
    var neto = peso.quitar_formato($("#txt_neto").val());
    var iva = peso.quitar_formato($("#txt_iva").val());
    var total = peso.quitar_formato($("#txt_total").val());
    var id_proveedor = $("#txt_id_proveedor").val();

    var data_grid_productos = $("#grid_productos_fac").DataTable().data();
    var productos = [];

    for (var i = 0; i < data_grid_productos.length; i++) {
        productos.push({
            "id_producto": data_grid_productos[i].id_producto,
            "cantidad": data_grid_productos[i].cantidad,
            "precio": data_grid_productos[i].precio,
            "total_producto": data_grid_productos[i].total_producto
        });
    }

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_egresos/guardar_compra",
        type: "POST",
        async: false,
        data: { 
            tipo_documento: tipo_documento,
            n_documento: n_documento,
            fecha_documento: fecha_documento,
            neto: neto,
            iva: iva,
            total: total,
            id_proveedor: id_proveedor,
            productos: productos
        },
        success: function(respuesta) {
            if (respuesta > 0) {
                $("#form_compras")[0].reset();
                $("#grid_productos_fac").DataTable().clear().draw();
                alerta.ok("alerta", "Compra guardada de manera exitosa");
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

function mostrar_datos_compras() {
    var id_egreso = $("#txt_id_egreso_com").val(); 

    $.ajax({
        type: "POST",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_egresos/datos_compra",
        data: { id_egreso: id_egreso }
    }).done( function(data) {
        $("#cmb_tipo_documento").val(data[0].tipo_documento);
        $("#txt_n_documento").val(data[0].n_documento);
        $("#dt_fecha_documento").val(data[0].fecha_documento);
        $("#txt_neto").val(peso.formateaNumero(data[0].neto));
        $("#txt_iva").val(peso.formateaNumero(data[0].iva));
        $("#txt_total").val(peso.formateaNumero(data[0].total));
        $("#txt_id_proveedor").val(data[0].id_proveedor);
        $("#txt_rut_proveedor").val(data[0].rut_proveedor);
        $("#txt_razon_social").val(data[0].razon_social);

        var id_compra = data[0].id_compra;

        $("#grid_productos_fac").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_egresos/datatable_productos_fac/" + id_compra);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

$(document).ready(function() {
	llenar_cmb_tipo_documento();
    llenar_cmb_productos();

	$("#dt_fecha_documento").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#txt_neto").on("blur", function() {
        var numero = peso.quitar_formato($(this).val());
        $(this).val(peso.formateaNumero(numero));
    });

    $("#txt_iva").on("blur", function() {
        var numero = peso.quitar_formato($(this).val());
        $(this).val(peso.formateaNumero(numero));
    });

    $("#txt_total").on("blur", function() {
        var numero = peso.quitar_formato($(this).val());
        $(this).val(peso.formateaNumero(numero));
    });

    $("#txt_precio").on("blur", function() {
        var numero = peso.quitar_formato($(this).val());
        $(this).val(peso.formateaNumero(numero));
    });

    $("#btn_buscar_proveedor").on("click", function() {
        $("#divContenedorDlg").load(
            base_url + "/Finanzas/Ctrl_ingresos/v_buscar_proveedor"
        ); 

        $('#dlg_compras').modal('show');
    });

    $("#btn_agregar_producto").on("click", function() {
        $('#dlg_productos').modal('show');
    });

    $("#btn_guardar_producto").on("click", function() {
        if ($("#form_producto_add").valid()) {
            guardar_producto();
        }
    });

    $("#btn_agregar").on("click", function() {
        agregar_fila_producto();
    });

    $("#btn_guardar").on("click", function() {
        if ($("#form_compras").valid()) {
            guardar_compra();
        }
    });

	var grid_productos_fac = $("#grid_productos_fac").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        select: {
            toggleable: false
        },
        orderClasses: true,
        searching: false,
        columns: [
            { "data": "id_producto" },
            { "data": "producto" },
            { "data": "cantidad" },
            { 
                "data": "precio",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "total_producto",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "id_producto",
                "render": function(data, type, row) {
                    if (!$("#txt_id_egreso_com")) {
                        return "<button type='button' class='eliminar_producto btn btn-danger' title='Eliminar Producto'><i class=\"fas fa-trash\"></i></button>";
                    } else {
                        return "...";
                    }
                }
            }
        ],
        "columnDefs": [
            { "targets": [0], "visible": false, "searchable": false }
        ],
        language: {
            "decimal": "",
            "emptyTable": "No ha ingresado productos",
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

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $.validator.addMethod("numero", function(value, element) {
        return this.optional(element) || /^[0-9.]+?$/.test(value);
    });

    $("#form_compras").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            cmb_tipo_documento: {
                required: true
            },
            txt_n_documento: {
                required: true,
                digits: true,
                maxlength: 12
            },
            dt_fecha_documento: {
                required: true
            },
            txt_neto: {
                required: true,
                numero: true,
                maxlength: 11
            },
            txt_iva: {
                required: true,
                numero: true,
                maxlength: 11
            },
            txt_total: {
                required: true,
                numero: true,
                maxlength: 11
            },
            txt_id_proveedor: {
                required: true
            },
            cmb_productos: {
                required: true
            },
            txt_cantidad: {
                required: true,
                digits: true,
                maxlength: 11
            },
            txt_precio: {
                required: true,
                numero: true,
                maxlength: 12
            }
        },
        messages: {
            cmb_tipo_documento: {
                required: "El tipo de documento es obligatorio"
            },
            txt_n_documento: {
                required: "El número de documento es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            dt_fecha_documento: {
                required: "Seleccione una fecha"
            },
            txt_neto: {
                required: "El NETO es obligatorio",
                numero: "Solo números",
                maxlength: "Máximo 11 números"
            },
            txt_iva: {
                required: "El IVA es obligatorio",
                numero: "Solo números",
                maxlength: "Máximo 11 números"
            },
            txt_total: {
                required: "El total es obligatorio",
                numero: "Solo números",
                maxlength: "Máximo 11 números"
            },
            txt_id_proveedor: {
                required: "Seleccione un proveedor"
            },
            cmb_productos: {
                required: "Seleccion un producto"
            },
            txt_cantidad: {
                required: "Ingrese la cantidad de unidades",
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            txt_precio: {
                required: "Ingrese el precio de la unidad",
                numero: "Solo números",
                maxlength: "Máximo 12 números"
            }
        }
    });

    $("#form_producto_add").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_nombre: {
                required: true,
                charspecial: true,
                maxlength: 45
            },
            txt_marca: {
                charspecial: true,
                maxlength: 45
            },
            txt_modelo: {
                charspecial: true,
                maxlength: 45
            }
        },
        messages: {
            txt_nombre: {
                required: "El nombre es obligatorio",
                charspecial: "Tiene caracteres no permitidos",
                maxlength: "Máximo 45 caracteres"
            },
            txt_marca: {
                charspecial: "Tiene caracteres no permitidos",
                maxlength: "Máximo 45 caracteres"
            },
            txt_modelo: {
                charspecial: "Tiene caracteres no permitidos",
                maxlength: "Máximo 45 caracteres"
            }
        }
    });

    if ($("#txt_id_egreso_com").length > 0) {
        $("#btn_buscar_proveedor").prop("disabled", true);
        $("#btn_agregar").prop("disabled", true);
        mostrar_datos_compras();
    }
});