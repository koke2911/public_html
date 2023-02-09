function llenar_cmb_estado() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Inventario/Ctrl_productos/llenar_cmb_estado",
    }).done( function(data) {
        $("#cmb_estado").html('');

        var opciones = "<option value=\"\">Seleccione un estado</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].estado + "</option>";
        }

        $("#cmb_estado").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_ubicacion() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Inventario/Ctrl_productos/llenar_cmb_ubicacion",
    }).done( function(data) {
        $("#cmb_ubicacion").html('');

        var opciones = "<option value=\"\">Seleccione un ubicación</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].ubicacion + "</option>";
        }

        $("#cmb_ubicacion").append(opciones);
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function llenar_cmb_productos(id_producto){
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Finanzas/Ctrl_egresos/llenar_cmb_productos",
    }).done( function(data) {
        $("#cmb_productos").html('');

        var opciones = "<option value=\"\">Seleccione un producto</option>";
        
        for (var i = 0; i < data.length; i++) {
            opciones += "<option value=\"" + data[i].id + "\">" + data[i].producto + "</option>";
        }

        $("#cmb_productos").append(opciones);

        if (id_producto != "") {
            $("#cmb_productos").val(id_producto);
        }
    }).fail(function(error){
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
    });
}

function buscar_productos() {
    var productos = $("#cmb_productos").val();
    var estado = $("#cmb_estado").val();
    var ubicacion = $("#cmb_ubicacion").val();
    var codigo_barras = $("#txt_codigo_barras").val();

    var datos = {
        productos: productos,
        estado: estado,
        ubicacion: ubicacion,
        codigo_barras: codigo_barras
    }

    var datosBusqueda = JSON.stringify(datos);

    $("#grid_inventario").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_inventario/datatable_informe_inventario/" + datosBusqueda);
}

$(document).ready(function() {
    llenar_cmb_productos();
    llenar_cmb_ubicacion();
    llenar_cmb_estado();

    $("#btn_buscar").on("click", function() {
        buscar_productos();
        $("#informeInventario").collapse("hide");
    });

    $("#btn_limpiar").on("click", function() {
        $("#form_inf_inventario")[0].reset();
    });

    var productos = $("#cmb_productos").val();
    var estado = $("#cmb_estado").val();
    var ubicacion = $("#cmb_ubicacion").val();
    var codigo_barras = $("#txt_codigo_barras").val();

    var datos = {
        productos: productos,
        estado: estado,
        ubicacion: ubicacion,
        codigo_barras: codigo_barras
    }

    var datosBusqueda = JSON.stringify(datos);

    var grid_inventario = $("#grid_inventario").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        orderClasses: true,
        ajax: base_url + "/Informes/Ctrl_informe_inventario/datatable_informe_inventario/" + datosBusqueda,
        columns: [
            { "data": "id_producto" },
            { "data": "producto" },
            { "data": "marca" },
            { "data": "modelo" },
            { "data": "codigo_barras" },
            { "data": "estado" },
            { "data": "ubicacion" }
        ],
        dom: 'Bfrtilp',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Inventario"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Inventario",
                pageSize: 'LETTER'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Inventario"
            },
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