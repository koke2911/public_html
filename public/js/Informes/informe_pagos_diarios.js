var base_url = $("#txt_base_url").val();



$(document).ready(function() {

    $("#btn_exportar").on("click", function () {
        var dt_fecha_dia = $("#dt_fecha_dia").val();
        if(dt_fecha_dia!=""){
            window.open(base_url + "/Informes/Ctrl_informe_pagos_diarios/reporte_pagos_resumen/"+dt_fecha_dia);
        }else{
            alert("Debe Seleccionar el dia de consumo");
        }
    });


    $("#btn_exportar_mes").on("click", function () {
        var dt_fecha_mes = $("#dt_fecha_mes").val();
        if(dt_fecha_mes!=""){
            window.open(base_url + "/Informes/Ctrl_informe_pagos_diarios/reporte_pagos_resumen_mes/"+dt_fecha_mes);
        }else{
            alert("Debe Seleccionar el mes de consumo");
        }
    });


    $("#dt_fecha_dia").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_fecha_dia").blur();
    });;

    $("#dt_fecha_dia").val(moment().format("DD-MM-YYYY"));

    $("#dt_fecha_dia").on("blur", function() {

        var grid_pagos_diarios = $("#grid_pagos_diarios").DataTable({
            responsive: true,
            scrollCollapse: true,
            destroy: true,
            ajax: base_url + "/Informes/Ctrl_informe_pagos_diarios/datatable_informe_pagos_diarios/" + $("#dt_fecha_dia").val(),
            orderClasses: true,
            dom: 'Bfrtilp',
            columns: [
                { "data": "folio_caja" },
                { "data": "rol_socio" },
                { "data": "nombre_socio" },
                { "data": "total_pagar" },
                { "data": "entregado" },
                { "data": "vuelto" },
                { "data": "consumo" },
                { "data": "usu_reg" },
                { "data": "forma_pago" },
                { "data": "fecha_trans" }
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    titleAttr: 'Exportar a Excel',
                    className: 'btn btn-success',
                    title: "Informe de Pagos Diarios " + $("#dt_fecha_dia").val()
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-danger',
                    title: "Informe de Pagos Diarios",
                    orientation: 'landscape',
                    pageSize: 'LETTER'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Imprimir',
                    titleAttr: 'Imprimir',
                    className: 'btn btn-info',
                    title: "Informe de Pagos Diarios"
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
        $("#grid_pagos_diarios").dataTable().fnReloadAjax(base_url +"/Informes/Ctrl_informe_pagos_diarios/datatable_informe_pagos_diarios/"+$("#dt_fecha_dia").val());

    });

    $("#dt_fecha_mes").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_fecha_mes").blur();
    });

    $("#dt_fecha_mes").val(moment().format("MM-YYYY"));

    
});