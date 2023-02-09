var base_url = $("#txt_base_url").val();

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_titulo").prop("disabled", a);
    $("#txt_observacion").prop("disabled", a);
}

function mostrar_datos(data) {
    $("#txt_id_observacion").val(data["id_observacion"]);
    $("#txt_titulo").val(data["titulo"]);
    $("#txt_observacion").val(data["observacion"]);
}

function guardar() {
    var id_observacion = $("#txt_id_observacion").val();
    var titulo = $("#txt_titulo").val();
    var observacion = $("#txt_observacion").val();

    $.ajax({
        url: base_url + "/Configuracion/Ctrl_observaciones/guardar",
        type: "POST",
        async: false,
        data: {
            id_observacion: id_observacion,
            titulo: titulo,
            observacion: observacion
        },
        dataType: "json",
        success: function(respuesta) {
            const OK = 1;
            if (respuesta.estado == OK) {
                $("#grid_observaciones").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_observaciones/datatable_observaciones");
                $("#form_observacion")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", respuesta.mensaje);
                $("#datosObservacion").collapse("hide");
            } else {
                alerta.error("alerta", respuesta.mensaje);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

function eliminar(id_observacion) {
    $.ajax({
        url: base_url + "/Configuracion/Ctrl_observaciones/eliminar",
        type: "POST",
        async: false,
        data: { id_observacion: id_observacion },
        dataType: "json",
        success: function(respuesta) {
            const OK = 1;

            if (respuesta.estado == OK) {
                alerta.ok("alerta", respuesta.mensaje);
                $("#grid_observaciones").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_observaciones/datatable_observaciones");
            } else {
                alerta.error("alerta", respuesta.mensaje);
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
    $("#txt_id_observacion").prop("disabled", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_observacion")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosObservacion").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosObservacion").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Eliminar observación?",
            text: "¿Está seguro de eliminar la observación?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_observacion = $("#txt_id_observacion").val();
                eliminar(id_observacion);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_observacion").valid()) {
            guardar();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_observacion")[0].reset();
        des_habilitar(true, false);
        $("#datosObservacion").collapse("hide");
    });

    $("#txt_titulo").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });
    
    $("#txt_observacion").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_observacion").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            cmb_titulo: {
                required: true,
                charspecial: true
            },
            observacion: {
                required: true,
                charspecial: true
            }
        },
        messages: {
            cmb_titulo: {
                required: "Este campo es obligatorio",
                charspecial: "No se permiten estos caracteres (^[^;\"'{}\[\]^<>=]+$)"
            },
            observacion: {
                required: "Este campo es obligatorio",
                charspecial: "No se permiten estos caracteres (^[^;\"'{}\[\]^<>=]+$)"
            }
        }
    });

    var grid_observaciones = $("#grid_observaciones").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Configuracion/Ctrl_observaciones/datatable_observaciones",
        orderClasses: true,
        columns: [
            { "data": "id_observacion" },
            { "data": "titulo" },
            { "data": "observacion" }
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

    $("#grid_observaciones tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_observaciones.row(tr).data();
        mostrar_datos(data);
        des_habilitar(true, false);
        $("#btn_modificar").prop("disabled", false);
        $("#btn_eliminar").prop("disabled", false);
        $("#datosObservacion").collapse("hide");
    });
});