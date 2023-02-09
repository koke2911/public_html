$(document).ready(function() {
	var grid_buscar_socio = $("#grid_buscar_socio").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 2, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_ingresos/datatable_buscar_socio",
        orderClasses: true,
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
	        "paginate": {
	            "first": "Primero",
	            "last": "Ultimo",
	            "next": "Sig.",
	            "previous": "Ant."
	        }
        }
	});

    $("#grid_buscar_socio tbody").on("dblclick", "tr", function () {
        var origen = $("#txt_origen").val();

        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_buscar_socio.row(tr).data();
        var id_socio = data["id_socio"];
        var rut_socio = data["rut_socio"];
        var rol_socio = data["rol_socio"];
        var nombre_socio = data["nombre_socio"];

        switch (origen) {
            case "ingresos":
                $("#txt_id_proveedor").val(id_socio);
                $("#txt_rut_proveedor").val(rut_socio);
                $("#txt_razon_social").val(nombre_socio);
                break;
            case "egresos":
                $("#txt_id_proveedor").val(id_socio);
                $("#txt_rut_proveedor").val(rut_socio);
                $("#txt_razon_social").val(nombre_socio);
                break;
            case "compras":
                $("#txt_id_proveedor").val(id_socio);
                $("#txt_rut_proveedor").val(rut_socio);
                $("#txt_razon_social").val(nombre_socio);
                break;
            case "informe_ingresos":
                $("#txt_id_entidad").val(id_socio);
                break;
            case "informe_egresos":
                $("#txt_id_entidad").val(id_socio);
                break;
            case "informe_consumo_agua":
                $("#txt_id_socio").val(id_socio);
                $("#txt_rut_socio").val(rut_socio);
                $("#txt_nombre_socio").val(nombre_socio);
                break;
            case "cambio_medidor":
                $("#txt_id_socio").val(id_socio);
                $("#txt_rut_socio").val(rut_socio);
                $("#txt_nombre_socio").val(nombre_socio);
                break;
            case "repactacion":
                $("#txt_id_socio").val(id_socio);
                $("#txt_rut_socio").val(rut_socio);
                $("#txt_rol").val(rol_socio);
                $("#txt_nombre_socio").val(nombre_socio);
                buscar_deuda();
                break;
        }

        $('#dlg_buscador').modal('hide');
    });
});