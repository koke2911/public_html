var base_url = $("#txt_base_url").val();

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

function buscar_balance() {
    var mes_consumo = $("#dt_mes_consumo").val();
	$("#grid_mensual").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_mensual/datatable_informe_mensual/" + mes_consumo);
}

$(document).ready(function() {
	$("#dt_mes_consumo").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        buscar_balance();
    });

	var grid_mensual = $("#grid_mensual").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        select: {
            toggleable: false
        },
        orderClasses: true,
        dom: 'Bfrtilp',
        columns: [
            { "data": "fecha_pago" },
            { "data": "cantidad_boletas" },
            { 
                "data": "subtotal",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "subsidios",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "multas",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "servicios",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "total_pagado",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            $( api.column(2).footer()).html(
                peso.formateaNumero(api.column(2, {page:'current'} ).data().sum())
            );

            $( api.column(3).footer()).html(
                peso.formateaNumero(api.column(3, {page:'current'} ).data().sum())
            );

            $( api.column(4).footer()).html(
                peso.formateaNumero(api.column(4, {page:'current'} ).data().sum())
            );

            $( api.column(5).footer()).html(
                peso.formateaNumero(api.column(5, {page:'current'} ).data().sum())
            );

            $( api.column(6).footer()).html(
                peso.formateaNumero(api.column(6, {page:'current'} ).data().sum())
            );
        },
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Mensualidad",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column == 2 || column == 3 || column == 4 || column == 5 || column == 6) {
                                return peso.quitar_formato(data);
                            } else {
                                return data;
                            }
                        },
                        footer: function ( data, row, column, node ) {
                            return peso.quitar_formato(data);
                        }
                    }
                }
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Mensualidad",
                pageSize: 'LETTER',
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Mensualidad",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
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
});