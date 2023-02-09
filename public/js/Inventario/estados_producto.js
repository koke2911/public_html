var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_estado").prop("disabled", a);
}

function mostrar_datos_estado(data) {
    $("#txt_id_estado").val(data["id_estado"]);
    $("#txt_estado").val(data["estado"]);
}

function guardar_estado() {
    var id_estado = $("#txt_id_estado").val();
    var estado = $("#txt_estado").val();

    $.ajax({
        url: base_url + "/Inventario/Ctrl_estados_producto/guardar_estado",
        type: "POST",
        async: false,
        data: {
            id_estado: id_estado,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_estados").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_estados_producto/datatable_estados_producto");
                $("#form_estado")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Estado guardado con éxito");
                $("#datosEstadosProducto").collapse("hide");
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

function cambiar_estado(opcion, observacion, id_estado) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Inventario/Ctrl_estados_producto/cambiar_estado",
        type: "POST",
        async: false,
        data: { 
            id_estado: id_estado,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Estado eliminado con éxito");
                } else {
                    $('#dlg_reciclar_estado').modal('hide');
                    alerta.ok("alerta", "Estado reciclado con éxito");
                }

                $("#grid_estados").dataTable().fnReloadAjax(base_url + "/Inventario/Ctrl_estados_producto/datatable_estados_producto");
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
    $("#txt_id_estado").prop("disabled", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_estado")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosEstadosProducto").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosEstadosProducto").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var estado = $("#txt_estado").val();
        
        Swal.fire({
            title: "¿Eliminar estado?",
            text: "¿Está seguro de eliminar el estado " + estado + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_estado = $("#txt_id_estado").val();
                cambiar_estado("eliminar", result.value, id_estado);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_estado").valid()) {
            guardar_estado();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_estado")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosEstadosProducto").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarEstado").load(
            base_url + "/Inventario/Ctrl_estados_producto/v_estados_producto_reciclar"
        ); 

        $('#dlg_reciclar_estado').modal('show');
    });

    $("#txt_estado").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_estado").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_estado: {
                required: true,
                charspecial: true,
                maxlength: 100
            }
        },
        messages: {
            txt_estado: {
                required: "El estado es obligatorio",
                charspecial: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            }
        }
    });

    var grid_estados = $("#grid_estados").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Inventario/Ctrl_estados_producto/datatable_estados_producto",
        orderClasses: true,
        columns: [
            { "data": "id_estado" },
            { "data": "estado" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_estado",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_estado btn btn-warning' title='Traza estado'><i class='fas fa-shoe-prints'></i></button>";
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

    $("#grid_estados tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_estados.row(tr).data();
            mostrar_datos_estado(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosEstadosProducto").collapse("hide");
        }
    });

    $("#grid_estados tbody").on("click", "button.traza_estado", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaEstado").load(
                base_url + "/Inventario/Ctrl_estados_producto/v_estados_producto_traza"
            ); 

            $('#dlg_traza_estado').modal('show');
        }
    });
});