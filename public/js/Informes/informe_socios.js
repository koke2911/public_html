var base_url = $("#txt_base_url").val();

$(document).ready(function() {
    var grid_socios = $("#grid_socios").DataTable({
		responsive: true,
        scrollCollapse: true,
        destroy: true,
        ajax: base_url + "/Informes/Ctrl_informe_socios/datatable_informe_socios",
        orderClasses: true,
        dom: 'Bfrtilp', 
        columns: [
            { "data": "id_socio" },
            { "data": "rut" },
            { "data": "rol" },
            { "data": "nombre_completo" },
            { "data": "fecha_entrada" },
            { "data": "fecha_nacimiento" },
            { "data": "sexo" },
            { "data": "calle" },
            { "data": "numero" },
            { "data": "resto_direccion" },
            { "data": "comuna" },
            { "data": "ruta" },
            { "data": "estado" }
        ],
        dom: 'Bfrtip',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Socios"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Socios",
                orientation: 'landscape',
                pageSize: 'LETTER'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Socios"
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