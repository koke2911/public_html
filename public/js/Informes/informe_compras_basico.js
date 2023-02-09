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

function llenar_cmb_tipo_documento() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_tipo_documento",
    }).done( function(data) {
        $("#cmb_tipo_documento").html('');
        $("#cmb_tipo_documento_det").html('');

        var opciones = "<option value=\"\">Seleccione tipo de documento</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].tipo_documento + "</option>";
        }

        $("#cmb_tipo_documento").append(opciones);
        $("#cmb_tipo_documento_det").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function buscar_compras_basico() {
    var fecha_desde = $("#dt_fecha_desde").val();
    var fecha_hasta = $("#dt_fecha_hasta").val();
    var id_tipo_documento = $("#cmb_tipo_documento").val();
    var id_proveedor = $("#txt_id_proveedor").val();

    var datos = {
        fecha_desde: fecha_desde,
        fecha_hasta: fecha_hasta,
        id_tipo_documento: id_tipo_documento,
        id_proveedor: id_proveedor
    }

    var datosBusqueda = JSON.stringify(datos);

    $("#grid_compras_basico").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_compras/datatable_informe_compras_basico/" + datosBusqueda);
}

$(document).ready(function() {
    $("#dt_fecha_hasta").prop("disabled", true);
    $("#txt_id_proveedor").prop("readonly", true);
    $("#txt_rut_proveedor").prop("readonly", true);
    $("#txt_razon_social").prop("readonly", true);
    llenar_cmb_tipo_documento();

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

    $("#btn_buscar_proveedor").on("click", function() {
        $("#tlt_buscador").text("Buscar Proveedor");
        $("#txt_origen").val("informe_compras_basico");
        var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_proveedor";
                
        $("#divContenedorBuscador").load(url_buscador);
        $('#dlg_buscador').modal('show');
    });

    $("#btn_buscar").on("click", function() {
        if ($("#form_inf_compras_bas").valid()) {
            buscar_compras_basico();
            $("#informeComprasBasico").collapse("hide");
        }
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_inf_compras_bas")[0].reset();
        $("#dt_fecha_desde").data("DateTimePicker").clear();
        $("#dt_fecha_hasta").rules("add", { required: false });
        $("#dt_fecha_hasta").prop("disabled", true);
    });

    var grid_compras_basico = $("#grid_compras_basico").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        scrollX: "100%",
        orderClasses: true,
        columns: [
            { "data": "id_egreso" },
            { "data": "tipo_documento" },
            { "data": "fecha_documento" },
            { 
                "data": "neto",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "iva",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "total",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { "data": "rut_proveedor" },
            { "data": "razon_social" },
            { "data": "fecha_reg" },
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            $(api.column(3).footer()).html(
                peso.formateaNumero(api.column(3, {page:'current'} ).data().sum())
            );
            $(api.column(4).footer()).html(
                peso.formateaNumero(api.column(4, {page:'current'} ).data().sum())
            );
            $(api.column(5).footer()).html(
                peso.formateaNumero(api.column(5, {page:'current'} ).data().sum())
            );
        },
        dom: 'Bfrtilp',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Compras Básico",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column == 3 || column == 4 || column == 5 ) {
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
                title: "Informe de Compras Básico",
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
                title: "Informe de Compras Básico",
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
        var column = grid_compras_basico.column($(this).attr('data-column'));

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

     $("#form_inf_compras_bas").validate({
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