var base_url = $("#txt_base_url").val();

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_id_cambio").prop("disabled", a);
	$("#txt_id_socio").prop("disabled", a);
	$("#txt_rut_socio").prop("disabled", a);
	$("#txt_nombre_socio").prop("disabled", a);
	$("#txt_id_funcionario").prop("disabled", a);
	$("#txt_rut_funcionario").prop("disabled", a);
	$("#txt_nombre_funcionario").prop("disabled", a);
	$("#txt_motivo_cambio").prop("disabled", a);
	$("#dt_fecha_cambio").prop("disabled", a);
}

function mostrar_datos_cambio_medidor(data) {
	$("#txt_id_cambio").val(data["id_cambio"])
	$("#txt_id_socio").val(data["id_socio"])
	$("#txt_rut_socio").val(data["rut_socio"])
	$("#txt_nombre_socio").val(data["nombre_socio"])
	$("#txt_id_funcionario").val(data["id_funcionario"])
	$("#txt_rut_funcionario").val(data["rut_funcionario"])
	$("#txt_nombre_funcionario").val(data["nombre_funcionario"])
	$("#txt_motivo_cambio").val(data["motivo_cambio"])
	$("#dt_fecha_cambio").val(data["fecha_cambio"])
}

function guardar_cambio_medidor() {
    var id_cambio = $("#txt_id_cambio").val();
	var id_socio = $("#txt_id_socio").val();
	var id_funcionario = $("#txt_id_funcionario").val();
	var motivo_cambio = $("#txt_motivo_cambio").val();
	var fecha_cambio = $("#dt_fecha_cambio").val();

    $.ajax({
        url: base_url + "/Formularios/Ctrl_cambio_medidor/guardar_cambio_medidor",
        type: "POST",
        async: false,
        data: {
            id_cambio: id_cambio,
			id_socio: id_socio,
			id_funcionario: id_funcionario,
			motivo_cambio: motivo_cambio,
			fecha_cambio: fecha_cambio
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_cambio_medidor").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_cambio_medidor/datatable_cambio_medidor");
                $("#form_cambio_medidor")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Cambio de medidor exitoso");
                $("#datosCambioMedidor").collapse("hide");
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

function anular_cambio_medidor(observacion, id_cambio) {
    $.ajax({
        url: base_url + "/Formularios/Ctrl_cambio_medidor/anular_cambio_medidor",
        type: "POST",
        async: false,
        data: { 
            id_cambio: id_cambio,
            observacion: observacion,
            estado: 0
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
		        alerta.ok("alerta", "Cambio de medidor anulado con éxito");
		        $("#grid_cambio_medidor").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_cambio_medidor/datatable_cambio_medidor");
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
	$("#txt_id_cambio").prop("readonly", true);
	$("#txt_id_socio").prop("readonly", true);
	$("#txt_rut_socio").prop("readonly", true);
	$("#txt_nombre_socio").prop("readonly", true);
	$("#txt_id_funcionario").prop("readonly", true);
	$("#txt_rut_funcionario").prop("readonly", true);
	$("#txt_nombre_funcionario").prop("readonly", true);
	des_habilitar(true, false);

	$("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_cambio_medidor")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosCambioMedidor").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosCambioMedidor").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Anular Cambio Medidor?",
            text: "¿Está seguro de anular el cambio de medidor?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_cambio = $("#txt_id_cambio").val();
                anular_cambio_medidor(result.value, id_cambio);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_cambio_medidor").valid()) {
            guardar_cambio_medidor();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_cambio_medidor")[0].reset();
        des_habilitar(true, false);
        $("#datosCambioMedidor").collapse("hide");
    });

    $("#btn_buscar_socio").on("click", function() {
    	$("#divContenedorBuscador").html("");
    	$("#tlt_buscador").text("Buscar Socio");
        $("#divContenedorBuscador").load(base_url + "/Finanzas/Ctrl_ingresos/v_buscar_socio"); 
        $('#dlg_buscador').modal('show');
    });

    $("#btn_buscar_funcionario").on("click", function() {
    	$("#divContenedorBuscador").html("");
    	$("#tlt_buscador").text("Buscar Funcionario");
        $("#divContenedorBuscador").load(base_url + "/Finanzas/Ctrl_ingresos/v_buscar_funcionario"); 
        $('#dlg_buscador').modal('show');
    });

    $("#dt_fecha_cambio").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_cambio_medidor").validate({
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
            },
        	txt_id_funcionario: {
                required: true
            },
            txt_motivo_cambio: {
                required: true,
                charspecial: true,
                maxlength: 500
            },
            dt_fecha_cambio: {
                required: true
            }
        },
        messages: {
            txt_id_socio: {
                required: "Seleccione un socio"
            },
        	txt_id_funcionario: {
                required: "Seleccione un funcionario"
            },
            txt_motivo_cambio: {
                required: "Debe ingresar un motivo",
                charspecial: "Ha ingresado caracteres no permitidos",
                maxlength: "Máximo 500 caracteres"
            },
            dt_fecha_cambio: {
                required: "La fecha del cambio es obligatoria"
            }
        }
    });

    var grid_cambio_medidor = $("#grid_cambio_medidor").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_cambio_medidor/datatable_cambio_medidor",
        orderClasses: true,
        columns: [
            { "data": "id_cambio" },
            { "data": "id_socio" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { "data": "id_funcionario" },
            { "data": "rut_funcionario" },
            { "data": "nombre_funcionario" },
            { "data": "motivo_cambio" },
            { "data": "fecha_cambio" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_cambio",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_cambio_medidor btn btn-warning' title='Traza Cambio de Medidor'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [1, 2, 3, 5, 6], "visible": false, "searchable": false }
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

	$("#grid_cambio_medidor tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_cambio_medidor.row(tr).data();
        mostrar_datos_cambio_medidor(data);
        des_habilitar(true, false);
        $("#btn_modificar").prop("disabled", false);
        $("#btn_eliminar").prop("disabled", false);
        $("#datosCambioMedidor").collapse("hide");
    });

    $("#grid_cambio_medidor tbody").on("click", "button.traza_cambio_medidor", function () {
        $("#divContenedorTrazaCambio").load(
            base_url + "/Formularios/Ctrl_cambio_medidor/v_cambio_medidor_traza"
        ); 

        $('#dlg_traza_cambio').modal('show');
    });
});