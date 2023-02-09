function llenar_cmb_sectores() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_sector",
    }).done( function(data) {
        $("#cmb_sectores").html('');

        var opciones = "<option value=\"\">Seleccione un sector</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].sector + "</option>";
        }

        $("#cmb_sectores").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function buscar_lecturas() {
    var id_sector = $("#cmb_sectores").val();

    $("#grid_lecturas_sector").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_lecturas_sector/datatable_informe_lecturas_sector/" + id_sector);
}


$(document).ready(function() {
    llenar_cmb_sectores();

    $("#cmb_sectores").on("change", function() {
        buscar_lecturas();
    });

    var grid_lecturas_sector = $("#grid_lecturas_sector").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        orderClasses: true,
        columns: [
            { "data": "ruta" },
            { "data": "rol_socio" },
            { "data": "socio" },
            { "data": "n_medidor" },
            { "data": "lectura_anterior" },
            { "data": "lectura_actual" }
        ],
        dom: 'Bfrtilp',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: function() {
                    var sector = $( "#cmb_sectores option:selected" ).text();
                    return "Lecturas del sector " + sector;
                }
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: function() {
                    var sector = $( "#cmb_sectores option:selected" ).text();
                    return "Lecturas del sector " + sector;
                },
                pageSize: 'LETTER'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: function() {
                    var sector = $( "#cmb_sectores option:selected" ).text();
                    return "Lecturas del sector " + sector;
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
});