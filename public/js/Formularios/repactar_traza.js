$(document).ready(function() {
	var id_repactacion = $("#txt_id_repactacion").val();

	var grid_repactar_traza = $("#grid_repactar_traza").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Formularios/Ctrl_repactacion/datatable_repactar_traza/" + id_repactacion,
        orderClasses: true,
        columns: [
            { "data": "estado" },
            { "data": "observacion" },
            { "data": "usuario" },
            { "data": "fecha" }
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
            "select": {
                "rows": "<br/>%d Perfiles Seleccionados"
            },
	        "paginate": {
	            "first": "Primero",
	            "last": "Ultimo",
	            "next": "Sig.",
	            "previous": "Ant."
	        }
        }
	});
});