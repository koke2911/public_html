var lectura_actual;

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

function obtener_consumo_mes() {
    var id_sector = $("#cmb_sectores").val();
    var mes_consumo = $("#dt_mes_consumo").val();

    if (id_sector == '' && mes_consumo == '') return

    var datos = {
        id_sector: id_sector,
        mes_consumo: mes_consumo
    }

	var datosBusqueda = JSON.stringify(datos);

    $("#grid_lecturas_sector").dataTable().fnReloadAjax(base_url + "/Consumo/Ctrl_lecturas_sector/datatable_lecturas_sector/" + datosBusqueda);
}

function ingresar_lectura(data, tipo_facturacion) {
    var id_socio = data.id_socio;
    var id_metros = data.id_metros;
    var lectura_anterior = data.lectura_anterior;
    var mes_consumo = $("#dt_mes_consumo").val();
    var fecha_vencimiento = $("#dt_fecha_vencimiento").val();
    id_metros = id_metros === null ? 0 : id_metros;
    
    $.ajax({
        url: base_url + "/Consumo/Ctrl_lecturas_sector/ingresar_lectura_sector",
        type: "POST",
        async: false,
        dataType: "json",
        data: { 
            id_socio: id_socio,
            id_metros: id_metros,
            lectura_anterior: lectura_anterior,
            lectura_actual: lectura_actual,
            mes_consumo: mes_consumo,
            fecha_vencimiento: fecha_vencimiento,
            tipo_facturacion: tipo_facturacion
        },
        success: function(respuesta) {
            if (respuesta.estado == "OK") {
                const table = $("#grid_lecturas_sector").DataTable()
                alerta.ok("alerta", respuesta.mensaje);
                table.ajax.reload(null, false);
            } else {
                alerta.error("alerta", respuesta.mensaje);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

function set_lectura_actual(value) {
    lectura_actual = value;
}

function obtener_promedio(data) {
    $.ajax({
        url: base_url + "/Consumo/Ctrl_lecturas_sector/obtener_promedio",
        type: "POST",
        async: false,
        dataType: "json",
        data: { 
            id_socio: data.id_socio
        },
        success: function(respuesta) {
            if (respuesta.estado == "OK") {
                const T_MEDIO = 2;
                lectura_actual = parseInt(data.lectura_anterior) + parseInt(respuesta.mensaje);
                ingresar_lectura(data, T_MEDIO);
            } else {
                alerta.error("alerta", respuesta.mensaje);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

$(document).ready(function() {
    llenar_cmb_sectores();

    $("#dt_mes_consumo").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        $("#dt_mes_consumo").blur();
    });

    $("#dt_mes_consumo").on("blur", function() {
        obtener_consumo_mes();
    });

    $("#dt_fecha_vencimiento").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    $("#cmb_sectores").on("change", function() {
        obtener_consumo_mes()
    });

    var grid_lecturas_sector = $("#grid_lecturas_sector").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        orderClasses: true,
        columns: [
            { "data": "id_socio" },
            { "data": "id_metros" },
            { "data": "ruta" },
            { "data": "rol_socio" },
            { "data": "socio" },
            { "data": "n_medidor" },
            { "data": "lectura_anterior" },
            { 
                "data": "lectura_actual",
                "render": function(data, type, row) {
                    return '<input type="text" class="txt_ingreso_lectura form-control border border-light" value="' + data + '" onChange="set_lectura_actual(this.value)" />'
                }
            },
            { 
                "data": "lectura_actual",
                "render": function(data, type, row) {
                    if (data !== '') {
                        return "...";
                    } else {
                        return "<button type='button' class='btn_promedio btn btn-success' title='Promedio'><i class='fas fa-book-reader'></i> Promedio</button>"
                    }
                }
            }
        ],
        "columnDefs": [
            { "targets": [0, 1], "visible": false, "searchable": false }
        ],
        order: [[ 2, 'asc' ]],
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

    $("#grid_lecturas_sector tbody").on("change", "input.txt_ingreso_lectura", function () {
	    var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }
        
        const NORMAL = 1;
        var data = grid_lecturas_sector.row(tr).data();
        ingresar_lectura(data, NORMAL);
    });

    $("#grid_lecturas_sector tbody").on("click", "button.btn_promedio", function () {
	    var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }
        
        var data = grid_lecturas_sector.row(tr).data();
        obtener_promedio(data);
    });
});