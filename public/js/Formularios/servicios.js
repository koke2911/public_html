var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_servicio").prop("disabled", a);
}

function mostrar_datos_servicio(data) {
    $("#txt_id_servicio").val(data["id_servicio"]);
    $("#txt_servicio").val(data["servicio"]);
}

function guardar_servicio() {
    var id_servicio = $("#txt_id_servicio").val();
    var servicio = $("#txt_servicio").val();

    $.ajax({
        url: base_url + "/Formularios/Ctrl_servicios/guardar_servicio",
        type: "POST",
        async: false,
        data: {
            id_servicio: id_servicio,
            servicio: servicio
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_servicios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_servicios/datatable_servicios");
                $("#form_servicio")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Servicio guardado con éxito");
                $("#datosServicio").collapse("hide");
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

function eliminar_servicio(opcion, observacion, id_servicio) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Formularios/Ctrl_servicios/eliminar_servicio",
        type: "POST",
        async: false,
        data: { 
            id_servicio: id_servicio,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Servicio eliminado con éxito");
                } else {
                    $('#dlg_reciclar_servicios').modal('hide');
                    alerta.ok("alerta", "Servicio reciclado con éxito");
                }

                $("#grid_servicios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_servicios/datatable_servicios");
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
    $("#txt_id_servicio").prop("disabled", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_servicio")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosServicio").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosServicio").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var servicio = $("#txt_servicio").val();
        
        Swal.fire({
            title: "¿Eliminar servicio?",
            text: "¿Está seguro de eliminar el servicio " + servicio + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_servicio = $("#txt_id_servicio").val();
                eliminar_servicio("eliminar", result.value, id_servicio);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_servicio").valid()) {
            guardar_servicio();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_servicio")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosServicio").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarServicios").load(
            base_url + "/Formularios/Ctrl_servicios/v_servicios_reciclar"
        ); 

        $('#dlg_reciclar_servicios').modal('show');
    });

    $("#txt_servicio").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_servicio").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_servicio: {
                required: true,
                charspecial: true,
                maxlength: 100
            }
        },
        messages: {
            txt_servicio: {
                required: "El servicio es obligatorio",
                charspecial: "Caraceteres no permitidos",
                maxlength: "Máximo 100 caracteres"
            }
        }
    });

    var grid_servicios = $("#grid_servicios").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_servicios/datatable_servicios",
        orderClasses: true,
        columns: [
            { "data": "id_servicio" },
            { "data": "servicio" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_servicio",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_servicio btn btn-warning' title='Traza servicio'><i class='fas fa-shoe-prints'></i></button>";
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

    $("#grid_servicios tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_servicios.row(tr).data();
            mostrar_datos_servicio(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosServicio").collapse("hide");
        }
    });

    $("#grid_servicios tbody").on("click", "button.traza_servicio", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaServicios").load(
                base_url + "/Formularios/Ctrl_servicios/v_servicios_traza"
            ); 

            $('#dlg_traza_servicios').modal('show');
        }
    });
});