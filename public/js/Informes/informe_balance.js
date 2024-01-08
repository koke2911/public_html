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
        var numero = numero.split('.').join('');
        return numero;
    }
}

function buscar_balance() {
    var mes_consumo = $("#dt_mes_consumo").val();
	$("#grid_balance").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_balance/datatable_informe_balance/" + mes_consumo);
}

$(document).ready(function() {
	$("#dt_mes_consumo").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        buscar_balance();
    });

	var grid_balance = $("#grid_balance").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        select: {
            toggleable: false
        },
        orderClasses: true,
        dom: 'Bfrtilp',
        columns: [
            { "data": "id_metros" },
            { "data": "folio_bolect"},
            { "data": "rol_socio" },
            { "data": "rut" },
            { "data": "nombre_socio" },
            { "data": "consumo_anterior" },
            { "data": "consumo_actual" },
            { "data": "metros" },
            { 
                "data": "subtotal",
                "render": function(data, type, row) {
                    return data;
                }
            },
            { 
                "data": "multa",
                "render": function(data, type, row) {
                    return data;
                }
            },
            { 
                "data": "total_servicios",
                "render": function(data, type, row) {
                    return data;
                }
            },
            { 
                "data": "monto_subsidio",
                "render": function(data, type, row) {
                    return data;
                }
            },
            { 
                "data": "saldo_anterior",
                "render": function(data, type, row) {
                    return data;
                }
            },
            { 
                "data": "total_mes",
                "render": function(data, type, row) {
                    return data;
                }
            },
            { "data": "estado" }
        ],
        footerCallback: function (row, data, start, end, display) {
          
        },
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Balance",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column == 7 || column == 8 || column == 9 || column == 10 || column == 11) {
                                                              
                                return data;
                            } else {
                                return data;
                            }
                        },
                        footer: function ( data, row, column, node ) {
                            return data;
                        }
                    }
                }
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Balance",
                pageSize: 'TABLOID',
                orientation: 'landscape',
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
                title: "Informe de Balance",
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