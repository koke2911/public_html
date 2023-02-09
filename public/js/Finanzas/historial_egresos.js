function anular_egreso(observacion, id_egreso) {
    $.ajax({
        url: base_url + "/Finanzas/Ctrl_egresos/anular_egreso",
        type: "POST",
        async: false,
        data: {
            id_egreso: id_egreso,
            observacion: observacion
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_egresos").dataTable().fnReloadAjax(base_url + "/Finanzas/Ctrl_egresos/datatable_historial_egresos");
                alerta.ok("alerta", "Egreso anulado con éxito");
            } else {
                alerta.error("alerta", respuesta);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

$(document).ready(function() {
    var grid_egresos = $("#grid_egresos").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Finanzas/Ctrl_egresos/datatable_historial_egresos",
        orderClasses: true,
        columns: [
            { "data": "id_egreso" },
            { "data": "tipo_egreso" },
            { "data": "usuario" },
            { "data": "fecha" },
            { "data": "estado" },
            { 
                "data": "id_egreso",
                "render": function(data, type, row) {
                    return "<button type='button' class='detalle_egreso btn btn-info' title='Detalle Egreso'><i class='fas fa-info-circle'></i></button>"
                }
            },
            { 
                "data": "estado",
                "render": function(data, type, row) {
                    if (data == "Anulado") {
                        return "..."
                    } else {
                        return "<button type='button' class='anular_egreso btn btn-danger' title='Anular Egreso'><i class='fas fa-trash'></i></button>"
                    }
                }
            },
            { 
                "data": "id_egreso",
                "render": function(data, type, row) {
                    return "<button type='button' class='traza_egreso btn btn-warning' title='Traza Egreso'><i class='fas fa-shoe-prints'></i></button>"
                }
            }
        ],
        order: [[ 0, "desc" ]],
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

    $("#grid_egresos tbody").on("click", "button.detalle_egreso", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();
        }

        var data = grid_egresos.row(tr).data();
        var id_egreso = data["id_egreso"];

        if (data["tipo_egreso"] == "Compra") {
            var vista = "v_compra";
        } else {
            var vista = "v_egreso_simple";
        }

        $("#divContenedorDetalleEgreso").load(
            base_url + "/Finanzas/Ctrl_egresos/" + vista + "/" + id_egreso
        ); 

        $('#dlg_detalle_egresos').modal('show');
    });

    $("#grid_egresos tbody").on("click", "button.traza_egreso", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_egresos.row(tr).data();
        $("#txt_id_egreso").val(data["id_egreso"]);

        $("#divContenedorTrazaEgreso").load(
            base_url + "/Finanzas/Ctrl_egresos/v_egresos_traza"
        ); 

        $('#dlg_traza_egresos').modal('show');
    });

    $("#grid_egresos tbody").on("click", "button.anular_egreso", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_egresos.row(tr).data();
        var id_egreso = data["id_egreso"];
        $("#txt_id_egreso").val(data["id_egreso"]);

        Swal.fire({
            title: "¿Anular Egreso?",
            text: "¿Está seguro de anular el egreso " + id_egreso + "?",
            input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_egreso = $("#txt_id_egreso").val();
                anular_egreso(result.value, id_egreso);
            }
        });
    });
});