$(document).ready(function() {
    var grid_cuentas_reciclar = $("#grid_cuentas_reciclar").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_cuentas/datatable_cuentas_reciclar",
        orderClasses: true,
        select: {
            toggleable: false
        },
        columns: [
            { "data": "id_cuenta" },
            { "data": "nombre_banco" },
            { "data": "tipo_cuenta" },
            { "data": "n_cuenta" },
            { "data": "rut_cuenta" },
            { "data": "nombre_cuenta" },
            { "data": "email" }
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

    $("#grid_cuentas_reciclar tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_cuentas_reciclar.row(tr).data();
        var id_cuenta = data["id_cuenta"];
        var nombre_cuenta = data["nombre_cuenta"];
        
        Swal.fire({
            title: "¿Reciclar Proveedor?",
            text: "¿Está seguro de reciclar a " + nombre_cuenta + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                cambiar_estado_cuenta(result.value, id_cuenta, "reciclar");
            }
        });
    });
});