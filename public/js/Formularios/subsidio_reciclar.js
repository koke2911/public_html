$(document).ready(function() {
	var grid_subsidio_reciclar = $("#grid_subsidio_reciclar").DataTable({
		responsive: true,
		paging: true,
        // scrollY: '50vh',
        // scrollCollapse: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Formularios/Ctrl_subsidios/datatable_subsidio_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_subsidio" },
            { "data": "nombre_socio" },
            { "data": "n_decreto" },
            { "data": "fecha_decreto" },
            { "data": "porcentaje" },
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

    $("#grid_subsidio_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_subsidio_reciclar.row(tr).data();
        var id_subsidio = data["id_subsidio"];
        
        Swal.fire({
            title: "¿Reciclar Subsidio?",
            text: "¿Está seguro de reciclar este subsidio?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
               eliminar_subsidio("reciclar", result.value, id_subsidio);
            }
        });
    });
});