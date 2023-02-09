$(document).ready(function() {
	var grid_servicios_reciclar = $("#grid_servicios_reciclar").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Formularios/Ctrl_servicios/datatable_servicios_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_servicio" },
            { "data": "servicio" },
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

    $("#grid_servicios_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_servicios_reciclar.row(tr).data();
        var id_servicio = data["id_servicio"];
        var servicio = data["servicio"];
        
        Swal.fire({
            title: "¿Reciclar Servicio?",
            text: "¿Está seguro de reciclar el servicio " + servicio + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                eliminar_servicio("reciclar", result.value, id_servicio);
            }
        });
    });
});