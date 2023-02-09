function mostrar_permisos_usuario() {
    var id_usuario = $("#txt_id_usuario").val();

    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Configuracion/Ctrl_usuarios/data_permisos_usuario/" + id_usuario
    }).done( function(respuesta) {
        $("#grid_usuario_permisos td").each(function() {
            $(this).closest("tr").removeClass("selected");
        });

        for (var i = 0; i < respuesta.length; i++) {
            var id_permiso = respuesta[i].id_permiso;
            $("#grid_usuario_permisos td").each(function() {
                var cellText = $(this).html();

                if(cellText == id_permiso){
                    $(this).closest("tr").addClass("selected");
                }
            });
        }
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta_permisos", respuesta.message);
    });
}

function guardar_permisos_usuario(id_permiso, opcion) {
    var id_usuario = $("#txt_id_usuario").val();

    $.ajax({
        url: base_url + "/Configuracion/Ctrl_usuarios/guardar_permisos_usuario",
        type: "POST",
        async: false,
        data: {
            id_usuario: id_usuario,
            id_permiso: id_permiso,
            opcion: opcion
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta != OK) {
                alerta.error("alerta_permisos", respuesta, 5000);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta_permisos", respuesta.message);
        }
    });
}

$(document).ready(function() {
    var grid_usuario_permisos = $("#grid_usuario_permisos").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        select: {
            style: "multi"
        },
        ajax: base_url + "/Configuracion/Ctrl_usuarios/datatable_usuarios_permisos",
        orderClasses: true,
        columns: [
            { "data": "id_permiso" },
            { "data": "permiso" },
            { "data": "grupo" },
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
                "rows": "<br/>%d Permisos Seleccionados"
            }
        }
	});

    grid_usuario_permisos.on("select", function (e, dt, type, indexes) {
        var rowData =  grid_usuario_permisos.rows(indexes).data().toArray();
        guardar_permisos_usuario(rowData[0].id_permiso, "guardar");
    }).on("deselect", function (e, dt, type, indexes) {
        var rowData = grid_usuario_permisos.rows(indexes).data().toArray();
        guardar_permisos_usuario(rowData[0].id_permiso, "eliminar");
    });

    setTimeout("mostrar_permisos_usuario()", 100);
});