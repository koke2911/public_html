$(document).ready(function() {
	var id_sector = $("#txt_id_sector").val();

	var grid_sector_reciclar = $("#grid_sector_reciclar").DataTable({
		responsive: true,
		paging: true,
        // scrollY: '50vh',
        // scrollCollapse: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Formularios/Ctrl_sectores/datatable_sector_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_sector" },
            { "data": "nombre" },
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

    $("#grid_sector_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_sector_reciclar.row(tr).data();
        var id_sector = data["id_sector"];
        var sector = data["nombre"];
        
        Swal.fire({
            title: "¿Reciclar Sector?",
            text: "¿Está seguro de reciclar el sector " + sector + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
               eliminar_sector("reciclar", result.value, id_sector);
            }
        });
    });
});