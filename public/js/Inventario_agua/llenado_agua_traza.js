$(document).ready(function() {
	var id_llenado = $("#txt_id_llenado").val();

	var grid_llenado_agua_traza = $("#grid_llenado_agua_traza").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Inventario_agua/Ctrl_llenado/datatable_llenado_traza/" + id_llenado,
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
	        "paginate": {
	            "first": "Primero",
	            "last": "Ultimo",
	            "next": "Sig.",
	            "previous": "Ant."
	        }
        }
	});
});