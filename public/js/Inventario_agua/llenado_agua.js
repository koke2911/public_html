var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#dt_fecha_hora").prop("disabled", a);
    $("#txt_id_operador").prop("disabled", a);
    $("#txt_rut_operador").prop("disabled", a);
    $("#txt_nombre_operador").prop("disabled", a);
    $("#btn_buscar_operador").prop("disabled", a);
    $("#txt_cantidad_agua").prop("disabled", a);
    $("#cmb_um_agua").prop("disabled", a);
    $("#txt_cantidad_cloro").prop("disabled", a);
    $("#cmb_um_cloro").prop("disabled", a);
}

function mostrar_datos_llenado(data) {
    $("#txt_id_llenado").val(data["id_llenado"]);
    $("#dt_fecha_hora").val(data["fecha_hora"])
    $("#txt_id_operador").val(data["id_operador"])
    $("#txt_rut_operador").val(data["rut_operador"])
    $("#txt_nombre_operador").val(data["nombre_operador"])
    $("#txt_cantidad_agua").val(data["cantidad_agua"])
    $("#cmb_um_agua").val(data["id_um_agua"])
    $("#txt_cantidad_cloro").val(data["cantidad_cloro"])
    $("#cmb_um_cloro").val(data["id_um_cloro"])
}

function guardar_llenado() {
    var id_llenado = $("#txt_id_llenado").val();
    var fecha_hora = $("#dt_fecha_hora").val();
    var id_operador = $("#txt_id_operador").val();
    var cantidad_agua = $("#txt_cantidad_agua").val();
    var um_agua = $("#cmb_um_agua").val();
    var cantidad_cloro = $("#txt_cantidad_cloro").val();
    var um_cloro = $("#cmb_um_cloro").val();

    $.ajax({
        url: base_url + "/Inventario_agua/Ctrl_llenado/guardar_llenado",
        type: "POST",
        async: false,
        data: {
            id_llenado: id_llenado,
            fecha_hora: fecha_hora,
            id_operador: id_operador,
            cantidad_agua: cantidad_agua,
            um_agua: um_agua,
            cantidad_cloro: cantidad_cloro,
            um_cloro: um_cloro
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_llenado").dataTable().fnReloadAjax(base_url + "/Inventario_agua/Ctrl_llenado/datatable_llenado");
                $("#form_llenado")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Llenado de agua guardado con éxito");
                $("#datosLlenadoAgua").collapse("hide");
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

function eliminar_llenado(observacion, id_llenado) {
    $.ajax({
        url: base_url + "/Inventario_agua/Ctrl_llenado/eliminar_llenado",
        type: "POST",
        async: false,
        data: { 
            id_llenado: id_llenado,
            observacion: observacion,
            estado: 0
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                alerta.ok("alerta", "Llenado de agua eliminado con éxito");
                $("#grid_llenado").dataTable().fnReloadAjax(base_url + "/Inventario_agua/Ctrl_llenado/datatable_llenado");
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
    $("#txt_id_llenado").prop("disabled", true);
    $("#txt_id_operador").prop("readonly", true);
    $("#txt_rut_operador").prop("readonly", true);
    $("#txt_nombre_operador").prop("readonly", true);
    des_habilitar(true, false);

    $("#dt_fecha_hora").datetimepicker({
        format: "DD-MM-YYYY HH:mm",
        useCurrent: false,
        locale: moment.locale("es"),
        maxDate: new Date()
    });

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_llenado")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosLlenadoAgua").collapse("show");
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        datatable_enabled = false;
        $("#datosLlenadoAgua").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        var id_llenado = $("#txt_id_llenado").val();
        
        Swal.fire({
            title: "¿Eliminar Llenado de Agua?",
            text: "¿Está seguro de eliminar el llenado de agua N° " + id_llenado + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                eliminar_llenado(result.value, id_llenado);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_llenado").valid()) {
            guardar_llenado();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_llenado")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosLlenadoAgua").collapse("hide");
    });

    $("#btn_buscar_operador").on("click", function() {
        $("#divContenedorBuscador").load(
            base_url + "/Finanzas/Ctrl_ingresos/v_buscar_funcionario"
        ); 

        $('#dlg_buscador').modal('show');
    });

    $("#form_llenado").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            dt_fecha_hora: {
                required: true
            },
            txt_id_operador: {
                required: true
            },
            txt_cantidad_agua: {
                required: true,
                digits: true,
                maxlength: 10
            },
            txt_cantidad_cloro: {
                required: true,
                digits: true,
                maxlength: 10
            }
        },
        messages: {
            dt_fecha_hora: {
                required: "Seleccione una fecha - hora"
            },
            txt_id_operador: {
                required: "Seleccione un operador"
            },
            txt_cantidad_agua: {
                required: "Cantidad de agua es obligatoria",
                digits: "Solo números",
                maxlength: "Máximo 10 números"
            },
            txt_cantidad_cloro: {
                required: "Cantidad de cloro es obligatorio",
                digits: "Solo números",
                maxlength: "Máximo 10 números"
            }
        }
    });

    var grid_llenado = $("#grid_llenado").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Inventario_agua/Ctrl_llenado/datatable_llenado",
        orderClasses: true,
        columns: [
            { "data": "id_llenado" },
            { "data": "fecha_hora" },
            { "data": "id_operador" },
            { "data": "rut_operador" },
            { "data": "nombre_operador" },
            { "data": "cantidad_agua" },
            { "data": "id_um_agua" },
            { "data": "um_agua" },
            { "data": "cantidad_cloro" },
            { "data": "id_um_cloro" },
            { "data": "um_cloro" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_llenado",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_llenado btn btn-warning' title='Traza llenado de agua'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],
        "columnDefs": [
            { "targets": [2, 6, 9], "visible": false, "searchable": false }
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

    $("#grid_llenado tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child') ) {
                tr = $(tr).prev();  
            }

            var data = grid_llenado.row(tr).data();
            mostrar_datos_llenado(data);
            des_habilitar(true, false);
            $("#btn_modificar").prop("disabled", false);
            $("#btn_eliminar").prop("disabled", false);
            $("#datosLlenadoAgua").collapse("hide");
        }
    });

    $("#grid_llenado tbody").on("click", "button.traza_llenado", function () {
        if (datatable_enabled) {
            $("#divContenedorTrazaLlenado").load(
                base_url + "/Inventario_agua/Ctrl_llenado/v_llenado_traza"
            ); 

            $('#dlg_traza_llenado').modal('show');
        }
    });
});