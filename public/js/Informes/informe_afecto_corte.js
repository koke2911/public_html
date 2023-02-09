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

function buscar_afecto_corte() {
    var n_meses = $("#txt_n_meses").val();
	$("#grid_afecto_corte").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_afecto_corte/datatable_informe_afecto_corte/" + n_meses);
}

$(document).ready(function() {
	$("#txt_n_meses").on("keypress", function(e)  {
        if (e.which == 13) {
            buscar_afecto_corte();
        }
    });

	var grid_afecto_corte = $("#grid_afecto_corte").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        select: {
            toggleable: false
        },
        orderClasses: true,
        dom: 'Bfrtilp',
        columns: [
            { "data": "rol_socio" },
            { "data": "rut" },
            { "data": "nombre_socio" },
            { "data": "meses_pendientes" },
            { 
                "data": "total_deuda",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            }
        ],
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Afecto a Corte",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            if (column == 4) {
                                return peso.quitar_formato(data);
                            } else {
                                return data;
                            }
                        }
                    }
                }
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Afecto a Corte",
                pageSize: 'LETTER',
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
                title: "Informe de Afecto a Corte",
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