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

function llenar_cmb_tipo_ingreso() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_ingresos/llenar_cmb_tipo_ingreso",
    }).done( function(data) {
        $("#cmb_tipo_ingreso").html('');

        var opciones = "<option value=\"\">Seleccione un tipo de ingreso</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].tipo_ingreso + "</option>";
        }

        $("#cmb_tipo_ingreso").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function buscar_ingresos() {
    var fecha_desde = $("#dt_fecha_desde").val();
    var fecha_hasta = $("#dt_fecha_hasta").val();
    var id_tipo_ingreso = $("#cmb_tipo_ingreso").val();
    var id_tipo_entidad = $("#cmb_tipo_entidad").val();
    var id_entidad = $("#txt_id_entidad").val();

    var datos = {
        "fecha_desde": fecha_desde,
        "fecha_hasta": fecha_hasta,
        "id_tipo_ingreso": id_tipo_ingreso,
        "id_tipo_entidad": id_tipo_entidad,
        "id_entidad": id_entidad
    }

    var datosBusqueda = JSON.stringify(datos);

    $("#grid_ingresos").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_ingresos/datatable_informe_ingresos/" + datosBusqueda);
}

$(document).ready(function() {
    $("#dt_fecha_hasta").prop("disabled", true);
    $("#txt_id_entidad").prop("readonly", true);
    llenar_cmb_tipo_ingreso();

    $("#dt_fecha_desde").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        if ($(this).val() != "") {
            $('#dt_fecha_hasta').data("DateTimePicker").minDate($(this).val());

            $("#dt_fecha_hasta").rules("add", {
                required: true,
                messages: { 
                    required: "Fecha hasta es obligatoria"
                }
            });

            $("#dt_fecha_hasta").prop("disabled", false);
        } else {
            $("#dt_fecha_hasta").rules("add", { required: false });
            $("#dt_fecha_hasta").data("DateTimePicker").clear();
            $("#dt_fecha_hasta").prop("disabled", true);
        }
    });

    $("#dt_fecha_hasta").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#cmb_tipo_entidad").on("change", function() {
        $("#txt_id_entidad").val("");
    });

    $("#btn_buscar_entidad").on("click", function() {
        var tipo_entidad = $("#cmb_tipo_entidad").val();
        if (tipo_entidad != "") {
            switch (tipo_entidad) {
                case "Proveedor":
                    $("#tlt_buscador").text("Buscar Proveedor");
                    var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_proveedor";
                    break;
                case "Funcionario":
                    $("#tlt_buscador").text("Buscar Funcionario");
                    var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_funcionario";
                    break;
                case "Socio":
                    $("#tlt_buscador").text("Buscar Socio");
                    var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_socio";    
                    break;
            }
                
            $("#divContenedorBuscador").load(url_buscador);
            $('#dlg_buscador').modal('show');
        } else {
            alerta.aviso("alerta", "Debe seleccionar un tipo entidad");
        }
    });

    $("#btn_buscar").on("click", function() {
        if ($("#form_inf_ingresos").valid()) {
            buscar_ingresos();
            $("#informeIngresos").collapse("hide");
        }
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_inf_ingresos")[0].reset();
        $("#dt_fecha_desde").data("DateTimePicker").clear();
        $("#dt_fecha_hasta").rules("add", { required: false });
        $("#dt_fecha_hasta").prop("disabled", true);
    });

    var grid_ingresos = $("#grid_ingresos").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        // ajax: base_url + "/Informes/Ctrl_informe_ingresos/datatable_informe_ingresos",
        orderClasses: true,
        columns: [
            { "data": "id_ingreso" },
            { 
                "data": "monto",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { "data": "fecha_ingreso" },
            { "data": "tipo_ingreso" },
            { "data": "tipo_entidad" },
            { "data": "rut_entidad" },
            { "data": "nombre_entidad" },
            { "data": "motivo" },
            { "data": "observaciones" },
            { "data": "fecha_reg" }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            $(api.column(1).footer()).html(
                peso.formateaNumero(api.column(1, {page:'current'} ).data().sum())
            );
        },
        dom: 'Bfrtilp',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Ingresos",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column == 1 ) {
                                return peso.quitar_formato(data);
                            } else {
                                return data;
                            }
                        },
                        footer: function ( data, row, column, node ) {
                            return peso.quitar_formato(data);
                        }
                    },
                    columns: ':visible'
                }
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Ingresos",
                pageSize: 'LETTER',
                orientation: 'landscape',
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    columns: ':visible'
                }
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Ingresos",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    columns: ':visible'
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
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
	});

    $("button.toggle-vis").on("click", function (e) {
        e.preventDefault();
        var column = grid_ingresos.column($(this).attr('data-column'));

        if (column.visible()) {
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-danger");
            $(this).find("i").removeClass("fas fa-eye");
            $(this).find("i").addClass("fas fa-eye-slash");
        } else {
            $(this).removeClass("btn-danger");
            $(this).addClass("btn-primary");
            $(this).find("i").removeClass("fas fa-eye-slash");
            $(this).find("i").addClass("fas fa-eye");
        }
        column.visible(!column.visible());
    });

     $("#form_inf_ingresos").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        }
    });
});