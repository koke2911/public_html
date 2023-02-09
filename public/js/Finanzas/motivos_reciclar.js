$(document).ready(function() {
	var grid_motivos_reciclar = $("#grid_motivos_reciclar").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_motivos/datatable_motivos_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_motivo" },
            { "data": "motivo" },
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

    $("#grid_motivos_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_motivos_reciclar.row(tr).data();
        var id_motivo = data["id_motivo"];
        var motivo = data["motivo"];
        
        Swal.fire({
            title: "¿Reciclar Motivo?",
            text: "¿Está seguro de reciclar el motivo " + motivo + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                eliminar_motivo("reciclar", result.value, id_motivo);
            }
        });
    });
});