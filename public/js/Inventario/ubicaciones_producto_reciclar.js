$(document).ready(function() {
	var grid_ubicaciones_producto_reciclar = $("#grid_ubicaciones_producto_reciclar").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Inventario/Ctrl_ubicaciones_producto/datatable_ubicaciones_producto_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_ubicacion" },
            { "data": "ubicacion" },
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

    $("#grid_ubicaciones_producto_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_ubicaciones_producto_reciclar.row(tr).data();
        var id_ubicacion = data["id_ubicacion"];
        var ubicacion = data["ubicacion"];
        
        Swal.fire({
            title: "¿Reciclar Ubicación?",
            text: "¿Está seguro de reciclar la ubicación " + ubicacion + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                cambiar_estado_ubicacion("reciclar", result.value, id_ubicacion);
            }
        });
    });
});