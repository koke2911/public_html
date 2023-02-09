var base_url = $("#txt_base_url").val();
var datatable_enabled = true;
var indice;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_desactivar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_nombre").prop("disabled", a);
    $("#txt_marca").prop("disabled", a);
    $("#txt_modelo").prop("disabled", a);
    $("#txt_cantidad").prop("disabled", a);
    $("#txt_id_detalle").prop("disabled", a);
    $("#cmb_estado").prop("disabled", a);
    $("#btn_modificar_detalle").prop("disabled", a);
    $("#cmb_ubicacion").prop("disabled", a);
}

function mostrar_datos_productos(data) {
    $("#txt_id_producto").val(data["id_producto"]);
    $("#txt_nombre").val(data["nombre"]);
    $("#txt_marca").val(data["marca"]);
    $("#txt_modelo").val(data["modelo"]);
    $("#txt_cantidad").val(data["cantidad"]);

    $("#grid_productos_detalles").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_productos/datatable_productos_detalles/" + data["id_producto"]);
}

function guardar_producto() {
    var id_producto = $("#txt_id_producto").val();
    var nombre = $("#txt_nombre").val();
    var marca = $("#txt_marca").val();
    var modelo = $("#txt_modelo").val();
    var cantidad = $("#txt_cantidad").val();

    var datos_grid_detalles = $("#grid_productos_detalles").DataTable().data();
    var datos_detalles = [];

    for (var i = 0; i < datos_grid_detalles.length; i++) {
        datos_detalles.push({
            "id_detalle": datos_grid_detalles[i].id_detalle,
            "codigo_barra": datos_grid_detalles[i].codigo_barra,
            "id_estado": datos_grid_detalles[i].id_estado,
            "id_ubicacion": datos_grid_detalles[i].id_ubicacion
        });
    }

    $.ajax({
        url: base_url + "/Inventario/Ctrl_productos/guardar_productos",
        type: "POST",
        async: false,
        data: {
            id_producto: id_producto,
            nombre: nombre,
            marca: marca,
            modelo: modelo,
            cantidad: cantidad,
            datos_detalles: datos_detalles
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_productos").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_productos/datatable_productos");
                $("#form_producto")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Productos guardados con éxito");
                $("#datosProducto").collapse("hide");
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

function cambiar_estado_producto(opcion, observacion, id_producto) {
    if (opcion == "desactivar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Inventario/Ctrl_productos/cambiar_estado_producto",
        type: "POST",
        async: false,
        data: { 
            id_producto: id_producto,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "desactivar") {
                    alerta.ok("alerta", "Producto desactivado con éxito");
                } else {
                    alerta.ok("alerta", "Producto activado con éxito");
                }

                $("#grid_productos").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_productos/datatable_productos");
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

function agregar_cantidad() {
    var numero = parseInt($("#txt_cantidad").val());

    if ($("#txt_id_producto").val() != "") {
        var numero_datatable = parseInt($("#grid_productos_detalles").DataTable().data().count());
        if (numero < numero_datatable) {
            alerta.aviso("alerta", "Para eliminar productos, debe darlos de baja");
        } else {
            var rows_add = numero - numero_datatable;
            console.log("rows_add: " + rows_add);
            if (rows_add > 0) {
                for (var i = 0; i < rows_add; i++) {
                    $("#grid_productos_detalles").DataTable().rows.add([{ 
                        "id_detalle": "",
                        "codigo_barra": "",
                        "id_estado": "",
                        "estado": "SIN ESTADO",
                        "id_ubicacion": "",
                        "ubicacion": "SIN UBICACIÓN"
                    }]);
                }

                $("#grid_productos_detalles").DataTable().draw();

                setTimeout(function() {
                    $("#grid_productos_detalles").DataTable().columns.adjust().draw();
                }, 500);
            }
        }
    } else {
        if (numero > 0) {
            $("#grid_productos_detalles").DataTable().clear();
            
            for (var i = 0; i < numero; i++) {
                $("#grid_productos_detalles").DataTable().rows.add([{ 
                    "id_detalle": "",
                    "codigo_barra": "",
                    "id_estado": "",
                    "estado": "SIN ESTADO",
                    "id_ubicacion": "",
                    "ubicacion": "SIN UBICACIÓN"
                }]);
            }

            $("#grid_productos_detalles").DataTable().draw();

            setTimeout(function() {
                $("#grid_productos_detalles").DataTable().columns.adjust().draw();
            }, 500);
        } else {
            alerta.aviso("alerta", "Ingrese un número válido");
        }
    }
}

function llenar_cmb_estado() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Inventario/Ctrl_productos/llenar_cmb_estado",
    }).done( function(data) {
        $("#cmb_estado").html('');

        var opciones = "<option value=\"\">Seleccione un estado</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].estado + "</option>";
        }

        $("#cmb_estado").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_ubicacion() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Inventario/Ctrl_productos/llenar_cmb_ubicacion",
    }).done( function(data) {
        $("#cmb_ubicacion").html('');

        var opciones = "<option value=\"\">Seleccione un ubicación</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].ubicacion + "</option>";
        }

        $("#cmb_ubicacion").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function modificar_detalle() {
    var filas = $("#grid_productos_detalles").DataTable().rows({ selected: true}).count();

    if (filas > 0) {
        var datos_grid_detalles = $("#grid_productos_detalles").DataTable().data();
        for (var i = 0; i < datos_grid_detalles.length; i++) {
            if (i == indice) {
                datos_grid_detalles[i].codigo_barra = $("#txt_id_detalle").val();
                
                if ($("#cmb_estado").val() != "") {
                    datos_grid_detalles[i].id_estado = $("#cmb_estado").val();
                    datos_grid_detalles[i].estado = $("#cmb_estado option:selected").text();    
                }

                if ($("#cmb_ubicacion").val() != "") {
                    datos_grid_detalles[i].id_ubicacion = $("#cmb_ubicacion").val();
                    datos_grid_detalles[i].ubicacion = $("#cmb_ubicacion option:selected").text();
                }

                break;
            }
        }

        $("#grid_productos_detalles").DataTable().clear();
        $("#grid_productos_detalles").DataTable().rows.add(datos_grid_detalles)
        $("#grid_productos_detalles").DataTable().draw();
        setTimeout(function() {
            $("#grid_productos_detalles").DataTable().columns.adjust().draw();
        }, 500);
    } else {
        alerta.aviso("alerta", "Debe seleccionar la fila que desea actualizar");
    }
}

function dar_baja_unidad(observacion, id_detalle) {
    var id_producto = $("#txt_id_producto").val();
    $.ajax({
        url: base_url + "/Inventario/Ctrl_productos/dar_baja_unidad",
        type: "POST",
        async: false,
        data: { 
            id_detalle: id_detalle,
            observacion: observacion
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                $("#txt_cantidad").val(parseInt($("#txt_cantidad").val() - 1));
                $("#grid_productos").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_productos/datatable_productos");
                $("#grid_productos_detalles").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_productos/datatable_productos_detalles/" + id_producto);
                alerta.ok("alerta", "Unidad dada de baja, de manera exitosa");
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
    $("#txt_id_producto").prop("disabled", true);
    des_habilitar(true, false);
    llenar_cmb_estado();
    llenar_cmb_ubicacion();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_producto")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_desactivar").prop("disabled", true);
        $("#datosProducto").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_desactivar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosProducto").collapse("show");
    });

    $("#btn_desactivar").on("click", function() {
        var data = $("#grid_productos").DataTable().rows('.selected').data();
        var estado = data[0].estado;
        var cantidad = data[0].cantidad;
        var id_producto = data[0].id_producto;

        if (estado == "Activado") {
            var titulo = "Desactivar";
            var opcion = "desactivar";
        } else {
            var titulo = "Activar";
            var opcion = "activar";
        }
        
        var nombre = $("#txt_nombre").val();
        if (cantidad == 0) {
            Swal.fire({
                title: "¿" + titulo + " Producto?",
                text: "¿Está seguro de " + opcion + " el producto " + nombre + "?",
                input: 'text',
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si",
                cancelButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    var id_motivo = $("#txt_id_motivo").val();
                    cambiar_estado_producto(opcion, result.value, id_producto);
                }
            });
        } else {
            alerta.aviso("alerta", "No puede desactivar un producto que tiene unidades activas");
        }
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_producto").valid()) {
            guardar_producto();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_producto")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosProducto").collapse("hide");
    });

    $("#datosProducto").on('show.bs.collapse', function(){
        setTimeout(function() {
            $("#grid_productos_detalles").DataTable().columns.adjust().draw();
        }, 500);
    });

    $("#btn_codigo_barra").on("click", function() {
        $("#divCodigoBarras").printThis({canvas: true});
    });

    $("#txt_motivo").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_nombre").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_marca").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_modelo").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_cantidad").on("blur", function() {
        if ($(this).val() != "") {
            agregar_cantidad();
        }
    });

    $("#txt_cantidad").keypress(function(event) {
        const ENTER = 13;
        if (event.keyCode == ENTER) {
            $(this).blur();
        }
    });

    $("#btn_modificar_detalle").on("click", function() {
        modificar_detalle();
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_producto").validate({
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
                maxlength: 100
            },
            txt_marca: {
                charspecial: true,
                maxlength: 100
            },
            txt_modelo: {
                charspecial: true,
                maxlength: 100
            },
            txt_cantidad: {
                digits: true,
                maxlength: 11
            }
        },
        messages: {
            txt_nombre: {
                required: "El nombre es obligatorio",
                charspecial: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            },
            txt_marca: {
                charspecial: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            },
            txt_modelo: {
                charspecial: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            },
            txt_cantidad: {
                digits: "Solo números",
                maxlength: "Máximo 11 dígitos"
            }
        }
    });

    var grid_productos = $("#grid_productos").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Inventario/Ctrl_productos/datatable_productos",
        orderClasses: true,
        columns: [
            { "data": "id_producto" },
            { "data": "nombre" },
            { "data": "marca" },
            { "data": "modelo" },
            { "data": "cantidad" },
            { "data": "estado" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_producto",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_productos btn btn-warning' title='Traza Productos'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
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

    $("#grid_productos tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_productos.row(tr).data();
            mostrar_datos_productos(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_desactivar").prop("disabled", false);
            $("#datosProducto").collapse("hide");
        }

        if (data["estado"] == "Activado") {
            $("#btn_desactivar").html("<i class=\"fas fa-trash\"></i> Desactivar");
        } else {
            $("#btn_desactivar").html("<i class=\"fas fa-check\"></i> Activar");
        }
    });

    $("#grid_productos tbody").on("click", "button.traza_productos", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaProductos").load(
                base_url + "/Inventario/Ctrl_productos/v_productos_traza"
            ); 

            $('#dlg_traza_productos').modal('show');
        }
    });

    var grid_productos_detalles = $("#grid_productos_detalles").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        orderClasses: true,
        searching: true,
        columns: [
            { "data": "id_detalle" },
            { "data": "codigo_barra" },
            { "data": "id_estado" },
            { "data": "estado" },
            { "data": "id_ubicacion" },
            { "data": "ubicacion" },
            { 
                "data": "id_detalle",
                "render": function(data, type, row) {
                    if (data != "") {
                        return "<button type='button' class='de_baja btn btn-danger' title='Dar  de Baja el Producto'><i class='fas fa-trash'></i></button>";
                    } else {
                        return "..."
                    }
                }
            },
            { 
                "data": "codigo_barra",
                "render": function(data, type, row) {
                    if (data != "") {
                        return "<button type='button' class='imprimir_codigo_barra btn btn-info' title='Imprimir Código de Barras'><i class='fas fa-barcode'></i></button>";
                    } else {
                        return "..."
                    }
                }
            },
            { 
                "data": "id_detalle",
                "render": function(data, type, row) {
                    if (data != "") {
                        return "<button type='button' class='traza_detalle btn btn-warning' title='Traza Detalles Productos'><i class='fas fa-shoe-prints'></i></button>";
                    } else {
                        return "..."
                    }
                }
            }
        ],
        "columnDefs": [
            { "targets": [0, 2, 4], "visible": false, "searchable": false }
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

    $("#grid_productos_detalles tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_productos_detalles.row(tr).data();
        $("#txt_id_detalle").val(data["codigo_barra"]);
        $("#cmb_estado").val(data["id_estado"]);
        $("#cmb_ubicacion").val(data["id_ubicacion"]);
        
        indice = $("#grid_productos_detalles").DataTable().row(this).index();
    });

    $("#grid_productos_detalles tbody").on("click", "button.de_baja", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_productos_detalles.row(tr).data();

        Swal.fire({
            title: "¿Dar de baja?",
            text: "¿Está seguro de dar de baja esta unidad de producto?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_detalle = data["id_detalle"];
                dar_baja_unidad(result.value, id_detalle);
            }
        });
    });

    $("#grid_productos_detalles tbody").on("click", "button.imprimir_codigo_barra", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_productos_detalles.row(tr).data();

        $("#divCodigoBarras").JsBarcode(
            data["codigo_barra"], 
            {
                displayValue: true, 
                fontSize: 15,
                width: 5
            }
        );
        $('#dlg_codigo_barra').modal('show');
    });

    $("#grid_productos_detalles tbody").on("click", "button.traza_detalle", function () {
        $("#divContenedorTrazaProductos").load(
            base_url + "/Inventario/Ctrl_productos/v_productos_detalles_traza"
        ); 

        $('#dlg_traza_productos').modal('show');
    });
});