$(document).ready(function() {
	var grid_buscar_cuentas = $("#grid_buscar_cuentas").DataTable({
		responsive: true,
		paging: true,
        destroy: true,
        order: [[ 2, "desc" ]],
        ajax: base_url + "/Finanzas/Ctrl_egresos/datatable_buscar_cuenta",
        orderClasses: true,
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

    $("#grid_buscar_cuentas tbody").on("dblclick", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_buscar_cuentas.row(tr).data();

        var id_cuenta = data["id_cuenta"];
        var nombre_banco = data["nombre_banco"];
        var tipo_cuenta = data["tipo_cuenta"];
        var n_cuenta = data["n_cuenta"];
        var rut_cuenta = data["rut_cuenta"];
        var nombre_cuenta = data["nombre_cuenta"];
        var email = data["email"];

        $("#txt_id_cuenta").val(id_cuenta);
        $("#txt_rut_cuenta").val(rut_cuenta);
        $("#txt_nombre_cuenta").val(nombre_cuenta);

        $("#txt_id_cuenta").val(id_cuenta);
        $("#txt_banco").val(nombre_banco);
        $("#txt_tipo_cuenta").val(tipo_cuenta);
        $("#txt_n_cuenta").val(n_cuenta);
        $("#txt_rut").val(rut_cuenta);
        $("#txt_nombre").val(nombre_cuenta);
        $("#txt_email").val(email);

        $('#dlg_buscador').modal('hide');
    });
});