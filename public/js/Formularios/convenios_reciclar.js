$(document).ready(function() {
	var grid_convenios_reciclar = $("#grid_convenios_reciclar").DataTable({
		responsive: true,
		paging: true,
        // scrollY: '50vh',
        // scrollCollapse: true,
        destroy: true,
        order: [[ 3, "desc" ]],
        ajax: base_url + "/Formularios/Ctrl_convenios/datatable_convenios_reciclar",
        orderClasses: true,
        columns: [
            { "data": "id_convenio" },
            { "data": "nombre_socio" },
            { "data": "servicio" },
            { "data": "fecha_servicio" },
            { "data": "numero_cuotas" },
            { "data": "fecha_pago" },
            { "data": "costo_servicio" },
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

    $("#grid_convenios_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_convenios_reciclar.row(tr).data();
        var id_convenio = data["id_convenio"];
        
        Swal.fire({
            title: "¿Reciclar Convenio?",
            text: "¿Está seguro de reciclar el convenio?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
               eliminar_convenio("reciclar", result.value, id_convenio);
            }
        });
    });
});