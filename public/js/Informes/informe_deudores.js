var base_url = $("#txt_base_url").val();

$(document).ready(function() {
    var columnas = [];
    
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Informes/Ctrl_informe_deudores/header_informe_deudores",
        async: false,
    }).done( function(data) {
        for (var i = 0; i < data.column.length; i++) {
            columnas.push({
                data: data.column[i]
            });
        }

        var grid = '\
        <div class="table-responsive">\
            <table id="grid_deudores" class="table table-bordered" width="100%">\
                <thead class="thead-dark">\
				    <tr>\
        ';

        for (var i = 0; i < data.header.length; i++) {
            grid += '<th>' + data.header[i] + '</th>';
            
        }
        
        grid += '\
                    </tr>\
                </thead>\
            </table>\
        </div>';

        $("#datatable_deudores").html(grid);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });

    var grid_deudores = $("#grid_deudores").DataTable({
        ajax: base_url + "/Informes/Ctrl_informe_deudores/datatable_informe_deudores",
		responsive: true,
        paging: false,
        destroy: true,
        orderClasses: true,
        dom: 'Bfrtilp',
        columns: columnas,
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe Deudores"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe Deudores",
                orientation: 'landscape',
                pageSize: 'LETTER'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe Deudores"
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