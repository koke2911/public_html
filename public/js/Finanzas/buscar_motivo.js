$(document).ready(function() {
	var grid_buscar_motivos = $("#grid_buscar_motivos").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 1, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_ingresos/datatable_buscar_motivo",
        orderClasses: true,
        columns: [
            { "data": "id_motivo" },
            { "data": "motivo" }
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

    $("#grid_buscar_motivos tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_buscar_motivos.row(tr).data();
        var id_motivo = data["id_motivo"];
        var motivo = data["motivo"];

        $("#txt_id_motivo").val(id_motivo);
        $("#txt_motivo").val(motivo);

        $('#dlg_buscador').modal('hide');
    });
});