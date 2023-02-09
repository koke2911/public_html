var base_url = $("#txt_base_url").val();

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_id_socio").prop("disabled", a);
    $("#txt_rut_socio").prop("disabled", a);
    $("#txt_rol").prop("disabled", a);
    $("#txt_nombre_socio").prop("disabled", a);
    $("#txt_abono").prop("disabled", a);
}

function mostrar_datos(data) {
    $("#txt_id_abono").val(data["id_abono"]);
    $("#txt_id_socio").val(data["id_socio"]);
    $("#txt_rut_socio").val(data["rut_socio"]);
    $("#txt_rol").val(data["rol"]);
    $("#txt_nombre_socio").val(data["nombre_socio"]);
    $("#txt_abono").val(data["abono"]);
}

function guardar_abono() {
    var id_abono = $("#txt_id_abono").val();
    var id_socio = $("#txt_id_socio").val();
    var abono = peso.quitar_formato($("#txt_abono").val());

    $.ajax({
        url: base_url + "/Pagos/Ctrl_abonos/guardar_abono",
        type: "POST",
        async: false,
        data: {
            id_abono: id_abono,
            id_socio: id_socio,
            abono: abono,
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_abonos").dataTable().fnReloadAjax(base_url + "/Pagos/Ctrl_abonos/datatable_abonos");
                $("#form_abono")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "Abono guardado con éxito");
                $("#datosAbono").collapse("hide");
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

function eliminar_abono(observacion, id_abono) {
    var abono = peso.quitar_formato($("#txt_abono").val());
    var id_socio = peso.quitar_formato($("#txt_id_socio").val());

    $.ajax({
        url: base_url + "/Pagos/Ctrl_abonos/eliminar_abono",
        type: "POST",
        async: false,
        data: { 
            id_abono: id_abono,
            abono: abono,
            id_socio: id_socio,
            observacion: observacion
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                alerta.ok("alerta", "Abono eliminado con éxito");
                $("#grid_abonos").dataTable().fnReloadAjax(base_url + "/Pagos/Ctrl_abonos/datatable_abonos");
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

var peso = {
    validaEntero: function  ( value ) {
        var RegExPattern = /[0-9]+$/;
        return RegExPattern.test(value);
    },
    formateaNumero: function (value) {
        if (peso.validaEntero(value))  {  
            var retorno = '';
            value = value.toString().split('').reverse().join('');
            var i = value.length;
            while(i>0) retorno += ((i%3===0&&i!=value.length)?'.':'')+value.substring(i--,i);
            return retorno;
        }
        return 0;
    },
    quitar_formato : function(numero){
        numero = numero.split('.').join('');
        return numero;
    }
}

$(document).ready(function() {
    $("#txt_id_abono").prop("disabled", true);
    $("#txt_id_socio").prop("readonly", true);
    $("#txt_rut_socio").prop("readonly", true);
    $("#txt_rol").prop("readonly", true);
    $("#txt_nombre_socio").prop("readonly", true);
    des_habilitar(true, false);

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_abono")[0].reset();

        $("#btn_eliminar").prop("disabled", true);
        $("#datosAbono").collapse("show");
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Eliminar Abono?",
            text: "¿Está seguro de eliminar el abono?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_abono = $("#txt_id_abono").val();
                eliminar_abono(result.value, id_abono);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_abono").valid()) {
            guardar_abono();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_abono")[0].reset();
        des_habilitar(true, false);
        $("#datosAbono").collapse("hide");
    });

    $("#txt_abono").on("blur", function() {
        var numero = peso.quitar_formato($(this).val());
        $(this).val(peso.formateaNumero(numero)); 
    });

    $("#btn_buscar_socio").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_convenios"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

    $.validator.addMethod("peso", function(value, element) {
        return this.optional(element) || /^[0-9.]+$/.test(value);
    });

    $("#form_abono").validate({
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
            txt_abono: {
                required: true,
                peso: true,
                maxlength: 11
            }
        },
        messages: {
            txt_id_socio: {
                required: "Seleccione un socio"
            },
            txt_abono: {
                required: "Ingrese el monto del abono",
                peso: "Ingrese solo números",
                maxlength: "Máximo 11 números"
            }
        }
    });

    var grid_abonos = $("#grid_abonos").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Pagos/Ctrl_abonos/datatable_abonos",
        orderClasses: true,
        order: [[ 0, "desc" ]],
        columns: [
            { "data": "id_abono" },
            { "data": "id_socio" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { 
                "data": "abono",
                "render": function(data, type, row) {
                    var numero = peso.quitar_formato(data);
                    return peso.formateaNumero(numero);
                }
            },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "id_abono",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_abono btn btn-warning' title='Traza Abono'><i class='fas fa-shoe-prints'></i></button>";
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

    $("#grid_abonos tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_abonos.row(tr).data();
        mostrar_datos(data);
        des_habilitar(true, false);
        $("#btn_eliminar").prop("disabled", false);
        $("#datosAbono").collapse("hide");
    });

    $("#grid_abonos tbody").on("click", "button.traza_abono", function () {
        $("#divContenedorTrazaAbono").load(
            base_url + "/Pagos/Ctrl_abonos/v_abono_traza"
        ); 

        $('#dlg_traza').modal('show');
    });
});