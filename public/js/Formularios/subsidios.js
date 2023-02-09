var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#txt_id_socio").prop("disabled", a);
    $("#txt_rut_socio").prop("disabled", a);
    $("#txt_rol").prop("disabled", a);
    $("#txt_nombre_socio").prop("disabled", a);
    $("#btn_buscar_socio").prop("disabled", a);
    $("#txt_n_decreto").prop("disabled", a);
    $("#dt_fecha_decreto").prop("disabled", a);
    $("#dt_fecha_caducidad").prop("disabled", a);
    $("#cmb_porcentaje").prop("disabled", a);
    $("#dt_fecha_encuesta").prop("disabled", a);
    $("#txt_puntaje").prop("disabled", a);
    $("#txt_n_unico").prop("disabled", a);
    $("#txt_d_unico").prop("disabled", a);
}

function mostrar_datos_subsidio(data) {
    $("#txt_id_subsidio").val(data["id_subsidio"]);
    $("#txt_id_socio").val(data["id_socio"]);
    $("#txt_rut_socio").val(data["rut_socio"]);
    $("#txt_rol").val(data["rol_socio"]);
    $("#txt_nombre_socio").val(data["nombre_socio"]);
    $("#txt_n_decreto").val(data["n_decreto"]);
    $("#dt_fecha_decreto").val(data["fecha_decreto"]);
    $("#dt_fecha_caducidad").val(data["fecha_caducidad"]);
    $("#cmb_porcentaje").val(data["id_porcentaje"]);
    $("#dt_fecha_encuesta").val(data["fecha_encuesta"]);
    $("#txt_puntaje").val(data["puntaje"]);
    $("#txt_n_unico").val(data["n_unico"]);
    $("#txt_d_unico").val(data["d_unico"]);
}

function guardar_subsidio() {
    var id_subsidio = $("#txt_id_subsidio").val();
    var id_socio = $("#txt_id_socio").val();
    var rut_socio = $("#txt_rut_socio").val();
    var rol = $("#txt_rol").val();
    var nombre_socio = $("#txt_nombre_socio").val();
    var n_decreto = $("#txt_n_decreto").val();
    var fecha_decreto = $("#dt_fecha_decreto").val();
    var fecha_caducidad = $("#dt_fecha_caducidad").val();
    var porcentaje = $("#cmb_porcentaje").val();
    var fecha_encuesta = $("#dt_fecha_encuesta").val();
    var puntaje = $("#txt_puntaje").val();
    var n_unico = $("#txt_n_unico").val();
    var d_unico = $("#txt_d_unico").val();

    $.ajax({
        url: base_url + "/Formularios/Ctrl_subsidios/guardar_subsidio",
        type: "POST",
        async: false,
        data: {
            id_subsidio: id_subsidio,
            id_socio: id_socio,
            n_decreto: n_decreto,
            fecha_decreto: fecha_decreto,
            fecha_caducidad: fecha_caducidad,
            porcentaje: porcentaje,
            fecha_encuesta: fecha_encuesta,
            puntaje: puntaje,
            n_unico: n_unico,
            d_unico: d_unico
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_subsidios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_subsidios/datatable_subsidios");
                $("#form_subsidio")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Subsidio guardado con éxito");
                $("#datosSubsidio").collapse("hide");
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

function eliminar_subsidio(opcion, observacion, id_subsidio) {
    if (opcion == "eliminar") { var estado = 0; } else { var estado = 1; }
    
    $.ajax({
        url: base_url + "/Formularios/Ctrl_subsidios/eliminar_subsidio",
        type: "POST",
        async: false,
        data: { 
            id_subsidio: id_subsidio,
            observacion: observacion,
            estado: estado
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                if (opcion == "eliminar") {
                    alerta.ok("alerta", "Subsidio eliminado con éxito");
                } else {
                    $('#dlg_reciclar_subsidio').modal('hide');
                    alerta.ok("alerta", "Subsidio reciclado con éxito");
                }

                $("#grid_subsidios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_subsidios/datatable_subsidios");
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

function llenar_cmb_porcentaje() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_subsidios/llenar_cmb_porcentaje",
    }).done( function(data) {
        $("#cmb_porcentaje").html('');

        var opciones = "<option value=\"\">Seleccione un porcentaje</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].porcentaje + "</option>";
        }

        $("#cmb_porcentaje").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

$(document).ready(function() {
    $("#txt_id_subsidio").prop("disabled", true);
    $("#txt_id_socio").prop("readonly", true);
    $("#txt_rut_socio").prop("readonly", true);
    $("#txt_rol").prop("readonly", true);
    $("#txt_nombre_socio").prop("readonly", true);
    des_habilitar(true, false);
    llenar_cmb_porcentaje();

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_subsidio")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosSubsidio").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosSubsidio").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Eliminar Subsidio?",
            text: "¿Está seguro de eliminar el subsidio?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_subsidio = $("#txt_id_subsidio").val();
                eliminar_subsidio("eliminar", result.value, id_subsidio);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_subsidio").valid()) {
            guardar_subsidio();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_subsidio")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosSubsidio").collapse("hide");
    });

    $("#btn_reciclar").on("click", function() {
        $("#divContenedorReciclarSubsidio").load(
            base_url + "/Formularios/Ctrl_subsidios/v_subsidio_reciclar"
        ); 

        $('#dlg_reciclar_subsidio').modal('show');
    });

    $("#btn_buscar_socio").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_subsidios"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

    $("#dt_fecha_decreto").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#dt_fecha_caducidad").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#dt_fecha_encuesta").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#form_subsidio").validate({
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
            txt_n_decreto: {
                digits: true,
                maxlength: 11
            },
            cmb_porcentaje: {
                required: true
            },
            txt_puntaje: {
                digits: true,
                maxlength: 11
            },
            txt_n_unico: {
                digits: true,
                maxlength: 11
            },
            txt_d_unico: {
                digits: true,
                maxlength: 11
            }
        },
        messages: {
            txt_id_socio: {
                required: "Seleccione un socio, botón buscar"
            },
            txt_n_decreto: {
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            cmb_porcentaje: {
                required: "Seleccione un porcentaje"
            },
            txt_puntaje: {
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            txt_n_unico: {
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            },
            txt_d_unico: {
                digits: "Solo números",
                maxlength: "Máximo 11 números"
            }
        }
    });

    var grid_subsidios = $("#grid_subsidios").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_subsidios/datatable_subsidios",
        orderClasses: true,
        columns: [
            { "data": "id_subsidio" },
            { "data": "id_socio" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { "data": "n_decreto" },
            { "data": "fecha_decreto" },
            { "data": "fecha_caducidad" },
            { "data": "id_porcentaje" },
            { "data": "porcentaje" },
            { "data": "fecha_encuesta" },
            { "data": "puntaje" },
            { "data": "n_unico" },
            { "data": "d_unico" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_subsidio",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_subsidio btn btn-warning' title='Traza Subsidio'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [1, 2, 8, 11, 12, 13], "visible": false, "searchable": false }
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

    $("#grid_subsidios tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_subsidios.row(tr).data();
            mostrar_datos_subsidio(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosSubsidio").collapse("hide");
        }
    });

    $("#grid_subsidios tbody").on("click", "button.traza_subsidio", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaSubsidio").load(
                base_url + "/Formularios/Ctrl_subsidios/v_subsidio_traza"
            ); 

            $('#dlg_traza_subsidio').modal('show');
        }
    });
});