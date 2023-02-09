$(document).ready(function() {
	var id_proveedor = $("#txt_id_proveedor").val();

	var grid_proveedores_traza = $("#grid_proveedores_traza").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_proveedores/datatable_proveedores_traza/" + id_proveedor,
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