var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_nombre").prop("disabled", a);
}

function mostrar_datos_sector(data) {
    $("#txt_id_sector").val(data["id_sector"]);
    $("#txt_nombre").val(data["nombre"]);
}

function guardar_sector() {
    var id_sector = $("#txt_id_sector").val();
    var nombre = $("#txt_nombre").val();

    $.ajax({
        url: base_url + "/Formularios/Ctrl_sectores/guardar_sector",
        type: "POST",
        async: false,
        data: {
            id_sector: id_sector,
            nombre: nombre
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_sectores").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_sectores/datatable_sectores");
                $("#form_sector")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Sector guardado con éxito");
                $("#datosSector").collapse("hide");
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

function eliminar_sector(opcion, observacion, id_sector) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Formularios/Ctrl_sectores/eliminar_sector",
        type: "POST",
        async: false,
        data: { 
            id_sector: id_sector,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Sector eliminado con éxito");
                } else {
                    $('#dlg_reciclar').modal('hide');
                    alerta.ok("alerta", "Sector reciclado con éxito");
                }

                $("#grid_sectores").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_sectores/datatable_sectores");
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
    $("#txt_id_sector").prop("disabled", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_sector")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosSector").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosSector").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var sector = $("#txt_nombre").val();
        
        Swal.fire({
            title: "¿Eliminar Sector?",
            text: "¿Está seguro de eliminar el sector " + sector + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_sector = $("#txt_id_sector").val();
                eliminar_sector("eliminar", result.value, id_sector);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_sector").valid()) {
            guardar_sector();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_sector")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosSector").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarSector").load(
            base_url + "/Formularios/Ctrl_sectores/v_sector_reciclar"
        ); 

        $('#dlg_reciclar').modal('show');
    });

    $("#txt_nombre").on("blur", function() {
        this.value = convertirMayusculas(this.value);
    });

    $.validator.addMethod("letras", function(value, element) {
        return this.optional(element) || /^[a-zA-ZñÑáÁéÉíÍóÓúÚ ]*$/.test(value);
    });

    $("#form_sector").validate({
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
                letras: true,
                maxlength: 45
            }
        },
        messages: {
            txt_nombre: {
                required: "El nombre del sector es obligatorio",
                letras: "Solo puede ingresar letras",
                maxlength: "Máximo 45 caracteres"
            }
        }
    });

    var grid_sectores = $("#grid_sectores").DataTable({
		responsive: true,
        paging: true,
        // scrollY: '50vh',
        // scrollCollapse: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_sectores/datatable_sectores",
        orderClasses: true,
        columns: [
            { "data": "id_sector" },
            { "data": "nombre" },
            { "data": "estado" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_sector",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_sector btn btn-warning' title='Traza Sector'><i class='fas fa-shoe-prints'></i></button>";
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

    $("#grid_sectores tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_sectores.row(tr).data();
            mostrar_datos_sector(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosSector").collapse("hide");
        }
    });

    $("#grid_sectores tbody").on("click", "button.traza_sector", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaSector").load(
                base_url + "/Formularios/Ctrl_sectores/v_sector_traza"
            ); 

            $('#dlg_traza_sector').modal('show');
        }
    });
});