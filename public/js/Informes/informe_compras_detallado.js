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

function llenar_cmb_productos(id_producto){
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_egresos/llenar_cmb_productos",
    }).done( function(data) {
        $("#cmb_productos").html('');

        var opciones = "<option value=\"\">Seleccione un producto</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].producto + "</option>";
        }

        $("#cmb_productos").append(opciones);

        if (id_producto != "") {
            $("#cmb_productos").val(id_producto);
        }
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function buscar_compras_detallado() {
    var fecha_desde = $("#dt_fecha_desde_det").val();
    var fecha_hasta = $("#dt_fecha_hasta_det").val();
    var id_tipo_documento = $("#cmb_tipo_documento_det").val();
    var id_producto = $("#cmb_productos").val();
    var id_proveedor = $("#txt_id_proveedor_det").val();

    var datos = {
        fecha_desde: fecha_desde,
        fecha_hasta: fecha_hasta,
        id_tipo_documento: id_tipo_documento,
        id_producto: id_producto,
        id_proveedor: id_proveedor
    }

    var datosBusqueda = JSON.stringify(datos);

    $("#grid_compras_detallado").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_compras/datatable_informe_compras_detallado/" + datosBusqueda);
}

$(document).ready(function() {
    $("#dt_fecha_hasta_det").prop("disabled", true);
    $("#txt_id_proveedor_det").prop("readonly", true);
    $("#txt_rut_proveedor_det").prop("readonly", true);
    $("#txt_razon_social_det").prop("readonly", true);
    llenar_cmb_tipo_documento();
    llenar_cmb_productos();

    $("#dt_fecha_desde_det").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        if ($(this).val() != "") {
            $('#dt_fecha_hasta_det').data("DateTimePicker").minDate($(this).val());

            $("#dt_fecha_hasta_det").rules("add", {
                required: true,
                messages: { 
                    required: "Fecha hasta es obligatoria"
                }
            });

            $("#dt_fecha_hasta_det").prop("disabled", false);
        } else {
            $("#dt_fecha_hasta_det").rules("add", { required: false });
            $("#dt_fecha_hasta_det").data("DateTimePicker").clear();
            $("#dt_fecha_hasta_det").prop("disabled", true);
        }
    });

    $("#dt_fecha_hasta_det").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#btn_buscar_proveedor_det").on("click", function() {
        $("#tlt_buscador").text("Buscar Proveedor");
        $("#txt_origen").val("informe_compras_detallado");
        var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_proveedor";
                
        $("#divContenedorBuscador").load(url_buscador);
        $('#dlg_buscador').modal('show');
    });

    $("#btn_buscar_det").on("click", function() {
        if ($("#form_inf_compras_det").valid()) {
            buscar_compras_detallado();
            $("#informeComprasDet").collapse("hide");
        }
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_inf_compras_det")[0].reset();
        $("#dt_fecha_desde_det").data("DateTimePicker").clear();
        $("#dt_fecha_hasta_det").rules("add", { required: false });
        $("#dt_fecha_hasta_det").prop("disabled", true);
    });

    var grid_compras_detallado = $("#grid_compras_detallado").DataTable({
		// responsive: true,
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
            { "data": "producto" },
            { "data": "cantidad" },
            { 
                "data": "precio",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
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
            $(api.column(10).footer()).html(
                peso.formateaNumero(api.column(10, {page:'current'} ).data().sum())
            );
        },
        dom: 'Bfrtilp',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Compras Detallado",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column == 3 || column == 4 || column == 5 || column == 10) {
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
                title: "Informe de Compras Detallado",
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
                title: "Informe de Compras Detallado",
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
        var column = grid_compras_detallado.column($(this).attr('data-column'));

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

     $("#form_inf_compras_det").validate({
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