$(document).ready(function() {
	var grid_funcionarios_reciclar = $("#grid_funcionarios_reciclar").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_funcionarios/datatable_funcionarios_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_funcionario" },
            { "data": "rut_funcionario" },
            { "data": "nombre_funcionario" }
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

    $("#grid_funcionarios_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_funcionarios_reciclar.row(tr).data();
        var id_funcionario = data["id_funcionario"];
        var nombre = data["nombre"];
        
        Swal.fire({
            title: "¿Reciclar Funcionario?",
            text: "¿Está seguro de reciclar al funcionario " + nombre + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                cambiar_estado_funcionario("reciclar", result.value, id_funcionario);
            }
        });
    });
});