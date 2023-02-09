$(document).ready(function() {
	var id_usuario = $("#txt_id_usuario").val();

	var grid_usuario_traza = $("#grid_usuario_traza").DataTable({
		responsive: true,
		paging: true,
        // scrollY: '50vh',
        // scrollCollapse: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Configuracion/Ctrl_usuarios/datatable_usuarios_traza/" + id_usuario,
        orderClasses: true,
        columns: [
            { "data": "estado" },
            { "data": "observacion" },
            { "data": "usuario" },
            { "data": "fecha" },
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