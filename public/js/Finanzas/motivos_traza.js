$(document).ready(function() {
	var id_motivo = $("#txt_id_motivo").val();

	var grid_motivos_traza = $("#grid_motivos_traza").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_motivos/datatable_motivos_traza/" + id_motivo,
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