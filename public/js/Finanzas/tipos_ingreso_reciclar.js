$(document).ready(function() {
	var grid_tipos_ingreso_reciclar = $("#grid_tipos_ingreso_reciclar").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_tipos_ingreso/datatable_tipos_ingreso_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_tipo_ingreso" },
            { "data": "tipo_ingreso" },
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

    $("#grid_tipos_ingreso_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_tipos_ingreso_reciclar.row(tr).data();
        var id_tipo_ingreso = data["id_tipo_ingreso"];
        var tipo_ingreso = data["tipo_ingreso"];
        
        Swal.fire({
            title: "¿Reciclar tipo_ingreso?",
            text: "¿Está seguro de reciclar el tipo_ingreso " + tipo_ingreso + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                cambiar_estado_tipo_ingreso("reciclar", result.value, id_tipo_ingreso);
            }
        });
    });
});