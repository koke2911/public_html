var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nueva_cuenta").prop("disabled", b);
    $("#btn_procesar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    $("#btn_reciclar").prop("disabled", b);

    $("#cmb_cuenta_bancaria").prop("disabled", b);
    $("input[name='opt_tipo_operacion']").prop("disabled", a);
    $("#txt_monto").prop("disabled", a);
    $("#txt_concepto").prop("disabled", a);
    $("#btn_procesar_transaccion").prop("disabled", a);
}

function mostrar_datos_movimiento(data) {
    if (data["tipo_operacion"] != null) {
        if (data["tipo_operacion"] == "abono") {
            $("#opt_abono").prop("checked", true).parent().addClass("active");
            $("#opt_extraccion").prop("checked", false).parent().removeClass("active");
        } else {
            $("#opt_extraccion").prop("checked", true).parent().addClass("active");
            $("#opt_abono").prop("checked", false).parent().removeClass("active");
        }
    }
    if (data["monto"] != null) { $("#txt_monto").val(data["monto"]); }
    if (data["concepto"] != null) { $("#txt_concepto").val(data["concepto"]); }
}

function guardar_transaccion() {
    var id_cuenta = $("#cmb_cuenta_bancaria").val();
    var tipo_operacion = $("input[name='opt_tipo_operacion']:checked").val();
    var monto = $("#txt_monto").val();
    var concepto = $("#txt_concepto").val();

    $.ajax({
        url: base_url + "/Finanzas/Ctrl_banco/guardar_transaccion",
        type: "POST",
        async: false,
        data: {
            id_cuenta: id_cuenta,
            tipo_operacion: tipo_operacion,
            monto: monto,
            concepto: concepto
        },
        success: function (respuesta) {
            const OK = 1;
            if (respuesta == OK || respuesta.status == OK) {
                $("#grid_movimientos").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_banco/datatable_movimientos?id_cuenta=" + id_cuenta);
                actualizar_metricas(id_cuenta);
                $("#form_operacion")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Transacción procesada con éxito");
                datatable_enabled = true;
            } else {
                alerta.error("alerta", respuesta);
            }
        },
        error: function (error) {
            var respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

function cambiar_estado_movimiento(observacion, id_movimiento, estado) {
    $.ajax({
        url: base_url + "/Finanzas/Ctrl_banco/cambiar_estado_movimiento",
        type: "POST",
        async: false,
        data: {
            id_movimiento: id_movimiento,
            estado: estado,
            observacion: observacion
        },
        success: function (respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                var id_cuenta = $("#cmb_cuenta_bancaria").val();
                $("#grid_movimientos").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_banco/datatable_movimientos?id_cuenta=" + id_cuenta);
                actualizar_metricas(id_cuenta);
                $("#form_operacion")[0].reset();
                des_habilitar(true, false);
                if (estado == "eliminar") {
                    alerta.ok("alerta", "Movimiento eliminado con éxito");
                } else {
                    $('#dlg_reciclar_movimiento').modal('hide');
                    alerta.ok("alerta", "Movimiento recuperado con éxito");
                }
            } else {
                alerta.error("alerta", respuesta);
            }
        },
        error: function (error) {
            var respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

function actualizar_metricas(id_cuenta) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_banco/obtener_metricas?id_cuenta=" + id_cuenta,
    }).done(function (data) {
        if (data) {
            $("#lbl_saldo_total").text(data.saldo_total);
            $("#lbl_num_transacciones").text(data.num_transacciones);
        }
    }).fail(function (error) {
        var respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_cuentas() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_banco/llenar_cmb_cuentas",
    }).done(function (data) {
        $("#cmb_cuenta_bancaria").html('');
        var opciones = "<option value=\"\">Seleccionar Cuenta Bancaria</option>";

        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id_cuenta + "\">" + data[i].nombre_tipo_cuenta + "</option>";
        }

        $("#cmb_cuenta_bancaria").append(opciones);
    }).fail(function (error) {
        var respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function convertirMayusculas(texto) {
    return texto.toUpperCase().trim();
}

$(document).ready(function () {
    des_habilitar(true, false);
    llenar_cmb_cuentas();

    // Eventos de botones superiores
    $("#btn_nueva_cuenta").on("click", function () {
        des_habilitar(false, true);
        $("#form_operacion")[0].reset();
    });

    $("#btn_procesar").on("click", function () {
        if ($("#form_operacion").valid()) {
            guardar_transaccion();
        }
    });

    $("#btn_cancelar").on("click", function () {
        $("#form_operacion")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
    });

    $("#btn_reciclar").on("click", function () {
        $("#divContenedorReciclarMovimiento").load(
            base_url + "/Finanzas/Ctrl_banco/v_movimientos_reciclar"
        );
        $('#dlg_reciclar_movimiento').modal('show');
    });

    // Formulario de Transacción
    $("#btn_procesar_transaccion").on("click", function () {
        if ($("#form_operacion").valid()) {
            guardar_transaccion();
        }
    });

    $("#cmb_cuenta_bancaria").on("change", function () {
        var id_cuenta = $(this).val();
        if (id_cuenta != "") {
            $("#grid_movimientos").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_banco/datatable_movimientos?id_cuenta=" + id_cuenta);
            actualizar_metricas(id_cuenta);
            des_habilitar(false, false);
        } else {
            des_habilitar(true, false);
        }
    });

    $("#txt_concepto").on("blur", function () {
        $(this).val(convertirMayusculas($(this).val()));
    });

    // Filtros de búsqueda rápida sobre la tabla
    $("#txt_buscar_movimiento").on("keyup", function () {
        grid_movimientos.search(this.value).draw();
    });

    $("#cmb_filtro_tipo").on("change", function () {
        var val = $(this).val();
        if (val === "todos") {
            grid_movimientos.column(1).search("").draw();
        } else {
            grid_movimientos.column(1).search(val).draw();
        }
    });

    // Validaciones de formulario
    $.validator.addMethod("charspecial", function (value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_operacion").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules: {
            cmb_cuenta_bancaria: {
                required: true
            },
            opt_tipo_operacion: {
                required: true
            },
            txt_monto: {
                required: true,
                number: true,
                min: 0.01
            },
            txt_concepto: {
                required: true,
                charspecial: true,
                maxlength: 150
            }
        },
        messages: {
            cmb_cuenta_bancaria: {
                required: "Seleccione una cuenta bancaria"
            },
            opt_tipo_operacion: {
                required: "Seleccione el tipo de operación"
            },
            txt_monto: {
                required: "El monto es obligatorio",
                number: "Ingrese un monto válido",
                min: "El monto debe ser mayor a 0"
            },
            txt_concepto: {
                required: "El concepto es obligatorio",
                charspecial: "Caracteres no permitidos",
                maxlength: "Máximo 150 caracteres"
            }
        }
    });

    // Inicialización de DataTable
    var grid_movimientos = $("#grid_movimientos").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        order: [[0, "desc"]],
        select: {
            toggleable: false
        },
        ajax: base_url + "/Finanzas/Ctrl_banco/datatable_movimientos",
        orderClasses: true,
        columns: [
            { "data": "fecha_hora" },
            {
                "data": "tipo_operacion",
                "render": function (data, type, row) {
                    if (data == "abono") {
                        return "<span class='badge badge-light-success text-success'>Abono</span>";
                    } else {
                        return "<span class='badge badge-light-danger text-danger'>Extracción</span>";
                    }
                }
            },
            { "data": "concepto" },
            {
                "data": "monto",
                "render": function (data, type, row) {
                    var signo = (row.tipo_operacion == "abono") ? "+ " : "- ";
                    var color = (row.tipo_operacion == "abono") ? "text-success" : "text-danger";
                    return "<span class='" + color + " font-weight-bold'>" + signo + data + "</span>";
                }
            },
            { "data": "saldo_resultante" },
            {
                "data": "id_movimiento",
                "render": function (data, type, row) {
                    return "<button type='button' class='eliminar_movimiento btn btn-sm btn-outline-danger' title='Eliminar Movimiento'><i class='fas fa-trash'></i></button>";
                }
            },
            
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
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
    });

    // Eventos sobre la DataTable
    $("#grid_movimientos tbody").on("click", "tr", function () {
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child')) {
                tr = $(tr).prev();
            }

            var data = grid_movimientos.row(tr).data();
            if (data) {
                mostrar_datos_movimiento(data);
                des_habilitar(false, false);
            }
        }
    });

    $("#grid_movimientos tbody").on("click", "button.eliminar_movimiento", function (e) {
        e.stopPropagation();
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child')) {
            tr = $(tr).prev();
        }
        var data = grid_movimientos.row(tr).data();

        Swal.fire({
            title: "¿Eliminar Movimiento?",
            text: "¿Está seguro de eliminar el movimiento '" + data.concepto + "'?",
            input: 'text',
            inputPlaceholder: 'Ingrese motivo de eliminación',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                cambiar_estado_movimiento(result.value, data.id_movimiento, "eliminar");
            }
        });
    });

    $("#grid_movimientos tbody").on("click", "button.traza_movimiento", function (e) {
        e.stopPropagation();
        if (datatable_enabled) {
            var tr = $(this).closest('tr');
            if ($(tr).hasClass('child')) {
                tr = $(tr).prev();
            }
            var data = grid_movimientos.row(tr).data();

            $("#divContenedorTrazaMovimiento").load(
                base_url + "/Finanzas/Ctrl_banco/v_movimientos_traza?id_movimiento=" + data.id_movimiento
            );

            $('#dlg_traza_movimiento').modal('show');
        }
    });
});