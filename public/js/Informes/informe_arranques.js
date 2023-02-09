var base_url = $("#txt_base_url").val();

$(document).ready(function() {
    var grid_arranques = $("#grid_arranques").DataTable({
		responsive: true,
        scrollCollapse: true,
        destroy: true,
        ajax: base_url + "/Informes/Ctrl_informe_arranques/datatable_informe_arranques",
        orderClasses: true,
        dom: 'Bfrtilp', 
        columns: [
            { "data": "id_arranque" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" },
            { "data": "numero_medidor" },
            { "data": "calle" },
            { "data": "numero" },
            { "data": "resto_direccion" },
            { "data": "alcantarillado" },
            { "data": "cuota_socio" },
            { "data": "tipo_documento" },
            { "data": "descuento" }
        ],
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Arranques"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Arranques",
                orientation: 'landscape',
                pageSize: 'LETTER'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Arranques"
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