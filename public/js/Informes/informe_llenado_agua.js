function buscar() {
    var fecha_desde = $("#dt_fecha_desde").val();
    var fecha_hasta = $("#dt_fecha_hasta").val();
    var id_operador = $("#txt_id_operador").val();
    var id_conversion = $("#cmb_conversion").val();

    var datos = {
        fecha_desde: fecha_desde,
        fecha_hasta: fecha_hasta,
        id_operador: id_operador,
        id_conversion: id_conversion
    }

    var datosBusqueda = JSON.stringify(datos);

    $("#grid_inf_llenado_agua").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_llenado_agua/datatable_informe_llenado_agua/" + datosBusqueda);
}

$(document).ready(function() {
    $("#dt_fecha_hasta").prop("disabled", true);
    $("#txt_id_operador").prop("readonly", true);
    $("#txt_rut_operador").prop("readonly", true);
    $("#txt_nombre_operador").prop("readonly", true);

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

    $("#btn_buscar_operador").on("click", function() {
        $("#tlt_buscador").text("Buscar Operador");
        var url_buscador = base_url + "/Finanzas/Ctrl_ingresos/v_buscar_funcionario";
                
        $("#divContenedorBuscador").load(url_buscador);
        $('#dlg_buscador').modal('show');
    });

    $("#btn_buscar").on("click", function() {
        if ($("#form_inf_llenado").valid()) {
            buscar();
            $("#informeLlenadoAgua").collapse("hide");
        }
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_inf_llenado")[0].reset();
        $("#dt_fecha_desde").data("DateTimePicker").clear();
        $("#dt_fecha_hasta").rules("add", { required: false });
        $("#dt_fecha_hasta").prop("disabled", true);
    });

    var grid_inf_llenado_agua = $("#grid_inf_llenado_agua").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        orderClasses: true,
        columns: [
            { "data": "id_llenado" },
            { "data": "fecha_hora" },
            { "data": "rut_operador" },
            { "data": "nombre_operador" },
            { "data": "cantidad_agua" },
            { "data": "um_agua" },
            { "data": "cantidad_cloro" },
            { "data": "um_cloro" }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            $(api.column(4).footer()).html(api.column(4, {page:'current'} ).data().sum());
            $(api.column(6).footer()).html(api.column(6, {page:'current'} ).data().sum());
        },
        dom: 'Bfrtilp',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Llenado de Agua",
                footer: true

            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Llenado de Agua",
                pageSize: 'LETTER',
                orientation: 'landscape',
                footer: true
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Llenado de Agua",
                footer: true
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

     $("#form_inf_llenado").validate({
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