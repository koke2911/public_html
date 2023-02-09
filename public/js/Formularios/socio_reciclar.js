$(document).ready(function() {
	var grid_socio_reciclar = $("#grid_socio_reciclar").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Formularios/Ctrl_socios/datatable_socios_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_socio" },
            { "data": "rut_socio" },
            { "data": "rol_socio" },
            { "data": "nombre_socio" }
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

    $("#grid_socio_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_socio_reciclar.row(tr).data();
        var id_socio = data["id_socio"];
        var nombre_socio = data["nombre_socio"];
        
        Swal.fire({
            title: "¿Reciclar Socio?",
            text: "¿Está seguro de reciclar a " + nombre_socio + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                cambiar_estado_socio(result.value, id_socio, "reciclar");
            }
        });
    });
});