$(document).ready(function() {
    var grid_facturas_muni = $("#grid_facturas_muni").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        order: [[ 0, "desc" ]],
        ajax: base_url + "/Pagos/Ctrl_imprimir_facturas_muni/datatable_facturas_muni",
        orderClasses: true,
        columns: [
            { "data": "folio_factura" },
            { "data": "mes_facturado" },
            { "data": "usuario" },
            { "data": "fecha" },
            { 
                "data": "folio_factura",
                "render": function(data, type, row) {
                    return "<button type='button' class='imprimir_factura btn btn-info' title='Imprimir Factura'><i class='fas fa-print'></i></button>";
                }
            }
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

    $("#grid_facturas_muni tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_facturas_muni.row(tr).data();
        
        var url = base_url + "/Informes/Ctrl_informe_municipal/imprimir_factura/" + data["folio_factura"];
        window.open(url, "Factura Electrónica", "width=1200,height=800,location=0,scrollbars=yes");
    });

});