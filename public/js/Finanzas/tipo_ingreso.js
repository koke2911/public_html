var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_tipo_ingreso").prop("disabled", a);
}

function mostrar_datos_tipo_ingreso(data) {
    $("#txt_id_tipo").val(data["id_tipo_ingreso"]);
    $("#txt_tipo_ingreso").val(data["tipo_ingreso"]);
}

function guardar_tipo_ingreso() {
    var id_tipo_ingreso = $("#txt_id_tipo").val();
    var tipo_ingreso = $("#txt_tipo_ingreso").val();

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_tipos_ingreso/guardar_tipo_ingreso",
        type: "POST",
        async: false,
        data: {
            id_tipo_ingreso: id_tipo_ingreso,
            tipo_ingreso: tipo_ingreso
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_tipos_ingreso").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_tipos_ingreso/datatable_tipos_ingreso");
                $("#form_tipo_ingreso")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Tipo de ingreso guardado con éxito");
                $("#datosTipoIngreso").collapse("hide");
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

function cambiar_estado_tipo_ingreso(opcion, observacion, id_tipo_ingreso) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Finanzas/Ctrl_tipos_ingreso/cambiar_estado_tipo_ingreso",
        type: "POST",
        async: false,
        data: { 
            id_tipo_ingreso: id_tipo_ingreso,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Tipo de ingreso eliminado con éxito");
                } else {
                    $('#dlg_reciclar_tipo_ingreso').modal('hide');
                    alerta.ok("alerta", "Tipo de ingreso reciclado con éxito");
                }

                $("#grid_tipos_ingreso").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_tipos_ingreso/datatable_tipos_ingreso");
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
    $("#txt_id_tipo").prop("disabled", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_tipo_ingreso")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosTipoIngreso").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosTipoIngreso").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var tipo_ingreso = $("#txt_tipo_ingreso").val();
        
        Swal.fire({
            title: "¿Eliminar tipo_ingreso?",
            text: "¿Está seguro de eliminar el tipo de ingreso " + tipo_ingreso + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_tipo_ingreso = $("#txt_id_tipo").val();
                cambiar_estado_tipo_ingreso("eliminar", result.value, id_tipo_ingreso);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_tipo_ingreso").valid()) {
            guardar_tipo_ingreso();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_tipo_ingreso")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosTipoIngreso").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarTipoIngreso").load(
            base_url + "/Finanzas/Ctrl_tipos_ingreso/v_tipos_ingreso_reciclar"
        ); 

        $('#dlg_reciclar_tipo_ingreso').modal('show');
    });

    $("#txt_tipo_ingreso").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_tipo_ingreso").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_tipo_ingreso: {
                required: true,
                charspecial: true,
                maxlength: 100
            }
        },
        messages: {
            txt_tipo_ingreso: {
                required: "El tipo de ingreso es obligatorio",
                letras: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            }
        }
    });

    var grid_tipos_ingreso = $("#grid_tipos_ingreso").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Finanzas/Ctrl_tipos_ingreso/datatable_tipos_ingreso",
        orderClasses: true,
        columns: [
            { "data": "id_tipo_ingreso" },
            { "data": "tipo_ingreso" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_tipo_ingreso",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_tipo_ingreso btn btn-warning' title='Traza tipo_ingreso'><i class='fas fa-shoe-prints'></i></button>";
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

    $("#grid_tipos_ingreso tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_tipos_ingreso.row(tr).data();
            mostrar_datos_tipo_ingreso(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosTipoIngreso").collapse("hide");
        }
    });

    $("#grid_tipos_ingreso tbody").on("click", "button.traza_tipo_ingreso", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaTipoIngreso").load(
                base_url + "/Finanzas/Ctrl_tipos_ingreso/v_tipos_ingreso_traza"
            ); 

            $('#dlg_traza_tipo_ingreso').modal('show');
        }
    });
});