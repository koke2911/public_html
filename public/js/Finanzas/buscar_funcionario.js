$(document).ready(function() {
	var grid_buscar_funcionario = $("#grid_buscar_funcionario").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 2, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_ingresos/datatable_buscar_funcionario",
        orderClasses: true,
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

    $("#grid_buscar_funcionario tbody").on("dblclick", "tr", function () {
        var origen = $("#txt_origen").val();

        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_buscar_funcionario.row(tr).data();
        var id_funcionario = data["id_funcionario"];
        var rut_funcionario = data["rut_funcionario"];
        var nombre_funcionario = data["nombre_funcionario"];

        switch (origen) {
            case "ingresos":
                $("#txt_id_proveedor").val(id_funcionario);
                $("#txt_rut_proveedor").val(rut_funcionario);
                $("#txt_razon_social").val(nombre_funcionario);
                break;
            case "egresos":
                $("#txt_id_proveedor").val(id_funcionario);
                $("#txt_rut_proveedor").val(rut_funcionario);
                $("#txt_razon_social").val(nombre_funcionario);
                break;
            case "informe_ingresos":
                $("#txt_id_entidad").val(id_funcionario);
                break;
            case "informe_egresos":
                $("#txt_id_entidad").val(id_funcionario);
                break;
            case "llenado_agua":
                $("#txt_id_operador").val(id_funcionario);
                $("#txt_rut_operador").val(rut_funcionario);
                $("#txt_nombre_operador").val(nombre_funcionario);
                break;
            case "informe_llenado_agua":
                $("#txt_id_operador").val(id_funcionario);
                $("#txt_rut_operador").val(rut_funcionario);
                $("#txt_nombre_operador").val(nombre_funcionario);
                break;
            case "cambio_medidor":
                $("#txt_id_funcionario").val(id_funcionario);
                $("#txt_rut_funcionario").val(rut_funcionario);
                $("#txt_nombre_funcionario").val(nombre_funcionario);
                break;
        }
        
        $('#dlg_buscador').modal('hide');
    });
});