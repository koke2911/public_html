var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_ubicacion").prop("disabled", a);
}

function mostrar_datos_ubicacion(data) {
    $("#txt_id_ubicacion").val(data["id_ubicacion"]);
    $("#txt_ubicacion").val(data["ubicacion"]);
}

function guardar_ubicacion() {
    var id_ubicacion = $("#txt_id_ubicacion").val();
    var ubicacion = $("#txt_ubicacion").val();

    $.ajax({
        url: base_url + "/Inventario/Ctrl_ubicaciones_producto/guardar_ubicacion",
        type: "POST",
        async: false,
        data: {
            id_ubicacion: id_ubicacion,
            ubicacion: ubicacion
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_ubicaciones").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_ubicaciones_producto/datatable_ubicaciones_producto");
                $("#form_ubicacion")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Ubicación guardada con éxito");
                $("#datosUbicacionProducto").collapse("hide");
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

function cambiar_estado_ubicacion(opcion, observacion, id_ubicacion) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Inventario/Ctrl_ubicaciones_producto/cambiar_estado_ubicacion",
        type: "POST",
        async: false,
        data: { 
            id_ubicacion: id_ubicacion,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Ubicación eliminada con éxito");
                } else {
                    $('#dlg_reciclar_ubicacion').modal('hide');
                    alerta.ok("alerta", "Ubicación recuperada con éxito");
                }

                $("#grid_ubicaciones").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_ubicaciones_producto/datatable_ubicaciones_producto");
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

$(document).ready(function() {
    $("#txt_id_ubicacion").prop("disabled", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_ubicacion")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosUbicacionProducto").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosUbicacionProducto").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var ubicacion = $("#txt_ubicacion").val();
        
        Swal.fire({
            title: "¿Eliminar Ubicación?",
            text: "¿Está seguro de eliminar el ubicación " + ubicacion + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_ubicacion = $("#txt_id_ubicacion").val();
                cambiar_estado_ubicacion("eliminar", result.value, id_ubicacion);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_ubicacion").valid()) {
            guardar_ubicacion();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_ubicacion")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosUbicacionProducto").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarUbicacion").load(
            base_url + "/Inventario/Ctrl_ubicaciones_producto/v_ubicaciones_producto_reciclar"
        ); 

        $('#dlg_reciclar_ubicacion').modal('show');
    });

    $("#txt_ubicacion").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_ubicacion").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_ubicacion: {
                required: true,
                charspecial: true,
                maxlength: 100
            }
        },
        messages: {
            txt_ubicacion: {
                required: "La ubicación es obligatoria",
                charspecial: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            }
        }
    });

    var grid_ubicaciones = $("#grid_ubicaciones").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Inventario/Ctrl_ubicaciones_producto/datatable_ubicaciones_producto",
        orderClasses: true,
        columns: [
            { "data": "id_ubicacion" },
            { "data": "ubicacion" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_ubicacion",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_ubicacion btn btn-warning' title='Traza Ubicación'><i class='fas fa-shoe-prints'></i></button>";
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

    $("#grid_ubicaciones tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_ubicaciones.row(tr).data();
            mostrar_datos_ubicacion(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosUbicacionProducto").collapse("hide");
        }
    });

    $("#grid_ubicaciones tbody").on("click", "button.traza_ubicacion", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaUbicacion").load(
                base_url + "/Inventario/Ctrl_ubicaciones_producto/v_ubicaciones_producto_traza"
            ); 

            $('#dlg_traza_ubicacion').modal('show');
        }
    });
});