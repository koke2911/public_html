var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_motivo").prop("disabled", a);
}

function mostrar_datos_motivo(data) {
    $("#txt_id_motivo").val(data["id_motivo"]);
    $("#txt_motivo").val(data["motivo"]);
}

function guardar_motivo() {
    var id_motivo = $("#txt_id_motivo").val();
    var motivo = $("#txt_motivo").val();

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_motivos/guardar_motivo",
        type: "POST",
        async: false,
        data: {
            id_motivo: id_motivo,
            motivo: motivo
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_motivos").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_motivos/datatable_motivos");
                $("#form_motivo")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Motivo guardado con éxito");
                $("#datosMotivo").collapse("hide");
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

function eliminar_motivo(opcion, observacion, id_motivo) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Finanzas/Ctrl_motivos/eliminar_motivo",
        type: "POST",
        async: false,
        data: { 
            id_motivo: id_motivo,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Motivo eliminado con éxito");
                } else {
                    $('#dlg_reciclar_motivo').modal('hide');
                    alerta.ok("alerta", "Motivo reciclado con éxito");
                }

                $("#grid_motivos").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_motivos/datatable_motivos");
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
    $("#txt_id_motivo").prop("disabled", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_motivo")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosMotivo").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosMotivo").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var motivo = $("#txt_motivo").val();
        
        Swal.fire({
            title: "¿Eliminar Motivo?",
            text: "¿Está seguro de eliminar el motivo " + motivo + "?",
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
                eliminar_motivo("eliminar", result.value, id_motivo);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_motivo").valid()) {
            guardar_motivo();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_motivo")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosMotivo").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarMotivo").load(
            base_url + "/Finanzas/Ctrl_motivos/v_motivos_reciclar"
        ); 

        $('#dlg_reciclar_motivo').modal('show');
    });

    $("#txt_motivo").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_motivo").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_motivo: {
                required: true,
                charspecial: true,
                maxlength: 100
            }
        },
        messages: {
            txt_motivo: {
                required: "El motivo es obligatorio",
                charspecial: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            }
        }
    });

    var grid_motivos = $("#grid_motivos").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Finanzas/Ctrl_motivos/datatable_motivos",
        orderClasses: true,
        columns: [
            { "data": "id_motivo" },
            { "data": "motivo" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_motivo",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_motivo btn btn-warning' title='Traza Motivo'><i class='fas fa-shoe-prints'></i></button>";
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

    $("#grid_motivos tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_motivos.row(tr).data();
            mostrar_datos_motivo(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosMotivo").collapse("hide");
        }
    });

    $("#grid_motivos tbody").on("click", "button.traza_motivo", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaMotivo").load(
                base_url + "/Finanzas/Ctrl_motivos/v_motivos_traza"
            ); 

            $('#dlg_traza_motivo').modal('show');
        }
    });
});