var lectura_actual;
var datos_globales = [];

function llenar_cmb_sectores() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_sector",
    }).done(function (data) {
        $("#cmb_sectores").html('<option value="">Seleccione un sector</option>');
        $.each(data, function (i, item) {
            $("#cmb_sectores").append('<option value="' + item.id + '">' + item.sector + '</option>');
        });
    }).fail(function (error) {
        if (error.responseText) {
            var respuesta = JSON.parse(error.responseText);
            if (typeof alerta !== "undefined") alerta.error("alerta", respuesta.message);
        }
    });
}

function obtener_consumo_mes() {
    var id_sector = $("#cmb_sectores").val();
    var mes_consumo = $("#dt_mes_consumo").val();

    if (!id_sector || id_sector == '' || !mes_consumo || mes_consumo == '') return;

    var datos = { id_sector: id_sector, mes_consumo: mes_consumo };
    var datosBusqueda = JSON.stringify(datos);

    // Cargar DataTables Escritorio
    if ($.fn.DataTable.isDataTable('#grid_lecturas_sector')) {
        $("#grid_lecturas_sector").dataTable().fnReloadAjax(base_url + "/Consumo/Ctrl_lecturas_sector/datatable_lecturas_sector/" + datosBusqueda);
    }

    // Cargar Tarjetas Móviles
    $.ajax({
        type: "GET",
        dataType: "json",
        url: base_url + "/Consumo/Ctrl_lecturas_sector/datatable_lecturas_sector/" + datosBusqueda
    }).done(function (res) {
        datos_globales = res.data || [];
        renderizar_cards_mobile(datos_globales);
    });
}

function renderizar_cards_mobile(datos) {
    var $container = $("#cards_container");
    $container.empty();

    if (!datos || datos.length === 0) {
        $container.html('<div class="text-center text-muted my-4"><p>No se encontraron socios en este sector.</p></div>');
        return;
    }

    var htmlBuscador = '<div class="form-group mb-3">' +
        '<input type="text" id="txt_buscar_mobile" class="form-control form-control-lg shadow-sm" placeholder="🔍 Buscar socio, medidor o ROL...">' +
        '</div><div id="lista_cards"></div>';
    $container.append(htmlBuscador);

    renderizar_lista_cards(datos);

    $("#txt_buscar_mobile").on("keyup", function () {
        var term = $(this).val().toLowerCase();
        var filtrados = datos.filter(function (item) {
            return (item.socio && item.socio.toLowerCase().includes(term)) ||
                (item.n_medidor && item.n_medidor.toString().toLowerCase().includes(term)) ||
                (item.rol_socio && item.rol_socio.toString().toLowerCase().includes(term));
        });
        renderizar_lista_cards(filtrados);
    });
}

function renderizar_lista_cards(lista) {
    var $lista = $("#lista_cards");
    $lista.empty();

    $.each(lista, function (i, row) {
        var tieneLectura = row.lectura_actual !== "";
        var cardClass = tieneLectura ? "card-socio-lectura ingresada" : "card-socio-lectura";

        var cardHtml = '<div class="card mb-3 shadow-sm ' + cardClass + '" data-index="' + i + '">' +
            '<div class="card-body p-3">' +
            '<div class="d-flex justify-content-between align-items-center mb-1">' +
            '<span class="badge badge-secondary">Ruta: ' + (row.ruta || '-') + '</span>' +
            '<span class="badge badge-light">ROL: ' + (row.rol_socio || '-') + '</span>' +
            '</div>' +
            '<h6 class="font-weight-bold mb-1 text-dark">' + row.socio + '</h6>' +
            '<p class="small text-muted mb-2"><i class="fas fa-tachometer-alt mr-1"></i>Medidor: <strong>' + row.n_medidor + '</strong></p>' +

            '<div class="row align-items-center mt-2">' +
            '<div class="col-6 text-center border-right">' +
            '<span class="small text-muted d-block">L. Anterior</span>' +
            '<strong class="h6 text-dark mb-0">' + row.lectura_anterior + '</strong>' +
            '</div>' +
            '<div class="col-6">' +
            '<label class="small text-muted d-block text-center mb-1">L. Actual</label>' +
            '<input type="number" pattern="[0-9]*" class="form-control input-mobile-lectura txt_ingreso_lectura_mobile" ' +
            'value="' + row.lectura_actual + '" data-id_socio="' + row.id_socio + '" placeholder="0" />' +
            '</div>' +
            '</div>' +

            '<div class="mt-2 text-right">' +
            (!tieneLectura ?
                '<button type="button" class="btn btn-sm btn-outline-success btn_promedio_mobile"><i class="fas fa-calculator mr-1"></i>Usar Promedio</button>' :
                '<small class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Ingresado</small>') +
            '</div>' +
            '</div>' +
            '</div>';

        $lista.append(cardHtml);
    });

    $(".txt_ingreso_lectura_mobile").on("change", function () {
        var $input = $(this);
        var index = $input.closest('.card').data('index');
        var dataRow = lista[index];
        set_lectura_actual($input.val());

        const NORMAL = 1;
        ingresar_lectura(dataRow, NORMAL);
    });

    $(".btn_promedio_mobile").on("click", function () {
        var index = $(this).closest('.card').data('index');
        var dataRow = lista[index];
        obtener_promedio(dataRow);
    });
}

function promedio3meses(mes_consumo, lectura_actual, id_socio, lectura_anterior) {
    $.ajax({
        url: base_url + "/Consumo/Ctrl_lecturas_sector/promedio3_meses/" + mes_consumo + "/" + lectura_actual + "/" + id_socio + "/" + lectura_anterior,
        type: "POST",
        async: false,
        dataType: "json",
        data: {
            lectura_actual: lectura_actual,
            mes_consumo: mes_consumo,
            id_socio: id_socio,
            lectura_anterior: lectura_anterior
        },
        success: function (respuesta) {
            if (typeof alerta !== "undefined") {
                alerta.ok("alerta", 'Lectura Ingresada Correctamente <br><strong>' + respuesta.mensaje + '</strong>');
            }
        },
        error: function (error) {
            if (error.responseText) {
                var respuesta = JSON.parse(error.responseText);
                if (typeof alerta !== "undefined") alerta.error("alerta", respuesta.message);
            }
        }
    });
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
        success: function (respuesta) {
            if (respuesta.estado == "OK") {
                obtener_consumo_mes();
                promedio3meses(mes_consumo, lectura_actual, id_socio, lectura_anterior);
            } else {
                if (typeof alerta !== "undefined") alerta.error("alerta", respuesta.mensaje);
            }
        },
        error: function (error) {
            if (error.responseText) {
                var respuesta = JSON.parse(error.responseText);
                if (typeof alerta !== "undefined") alerta.error("alerta", respuesta.message);
            }
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
        data: { id_socio: data.id_socio },
        success: function (respuesta) {
            if (respuesta.estado == "OK") {
                const T_MEDIO = 2;
                lectura_actual = parseInt(data.lectura_anterior) + parseInt(respuesta.mensaje);
                ingresar_lectura(data, T_MEDIO);
            } else {
                if (typeof alerta !== "undefined") alerta.error("alerta", respuesta.mensaje);
            }
        },
        error: function (error) {
            if (error.responseText) {
                var respuesta = JSON.parse(error.responseText);
                if (typeof alerta !== "undefined") alerta.error("alerta", respuesta.message);
            }
        }
    });
}

function calcular_fecha_vencimiento() {
    var mes_consumo_val = $("#dt_mes_consumo").val(); // Formato MM-YYYY

    if (mes_consumo_val) {
        // Interpreta el primer día del mes seleccionado y le suma 28 días
        var fecha_vencimiento = moment(mes_consumo_val, "MM-YYYY").add(31, 'days').format("DD-MM-YYYY");
        $("#dt_fecha_vencimiento").val(fecha_vencimiento);
    }
}

$(document).ready(function () {
    llenar_cmb_sectores();

    if ($.fn.datetimepicker) {
        $("#dt_mes_consumo").datetimepicker({
            format: "MM-YYYY",
            useCurrent: false,
            locale: moment.locale("es"),
            widgetPositioning: {
                horizontal: 'auto',
                vertical: 'bottom'
            }
        }).on("dp.change", function () {
            calcular_fecha_vencimiento(); // Reclacula vencimiento al cambiar el mes
            obtener_consumo_mes();
        });

        $("#dt_fecha_vencimiento").datetimepicker({
            format: "DD-MM-YYYY",
            useCurrent: false,
            locale: moment.locale("es"),
            widgetPositioning: {
                horizontal: 'auto',
                vertical: 'bottom'
            }
        });
    }
    // Evento al seleccionar un sector
    $("#cmb_sectores").off("change").on("change", function () {
        var id_sector = $(this).val();

        if (id_sector !== "") {
            $("#dt_mes_consumo").prop("disabled", false);
            $("#dt_fecha_vencimiento").prop("disabled", false);

            if (!$("#dt_mes_consumo").val()) {
                var mesActual = moment().format("MM-YYYY");
                $("#dt_mes_consumo").val(mesActual);
            }

            // Calcula automáticamente la fecha de vencimiento (+28 días)
            // calcular_fecha_vencimiento();

            // obtener_consumo_mes();
        } else {
            $("#dt_mes_consumo").prop("disabled", true).val("");
            $("#dt_fecha_vencimiento").prop("disabled", true).val("");
        }
    });


    $("#dt_fecha_vencimiento").on("dp.change", function () {
        obtener_consumo_mes();
    })
    var grid_lecturas_sector = $("#grid_lecturas_sector").DataTable({
        responsive: true,
        paging: false,
        destroy: true,
        columns: [
            { "data": "id_socio" },
            { "data": "id_metros" },
            { "data": "ruta" },
            { "data": "rol_socio" },
            { "data": "socio" },
            {
                "data": "n_medidor",
                "render": function (data, type, row) {
                    return row.lectura_actual != "" ? '<span class="badge badge-success">' + data + '</span>' : data;
                }
            },
            { "data": "lectura_anterior" },
            {
                "data": "lectura_actual",
                "render": function (data, type, row) {
                    return '<input type="number" pattern="[0-9]*" class="txt_ingreso_lectura form-control form-control-sm" value="' + data + '" onChange="set_lectura_actual(this.value)" />';
                }
            },
            {
                "data": "lectura_actual",
                "render": function (data, type, row) {
                    return data !== '' ? '<i class="fas fa-check-circle text-success"></i>' :
                        '<button type="button" class="btn_promedio btn btn-sm btn-success"><i class="fas fa-calculator"></i> Promedio</button>';
                }
            }
        ],
        "columnDefs": [
            { "targets": [0, 1, 2, 3], "visible": false, "searchable": false }
        ],
        order: [[2, 'asc']],
        dom: 'Bfrtilp',
        buttons: [
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-sm btn-success' },
            { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-sm btn-danger' },
            { extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-sm btn-info' }
        ],
        language: {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados"
        }
    });

    $('#grid_lecturas_sector tbody').off('keydown').on('keydown', 'input.txt_ingreso_lectura', function (e) {
        if ((e.keyCode || e.which) === 13) {
            var cell = $(this).closest('td');
            var nextCell = cell.closest('tr').next().find('td:eq(' + cell.index() + ')');
            nextCell.find('input.txt_ingreso_lectura').focus();
        }
    });

    $("#grid_lecturas_sector tbody").off("change").on("change", "input.txt_ingreso_lectura", function () {
        var tr = $(this).closest('tr');
        var data = grid_lecturas_sector.row(tr).data();
        ingresar_lectura(data, 1);
    });

    $("#grid_lecturas_sector tbody").off("click").on("click", "button.btn_promedio", function () {
        var tr = $(this).closest('tr');
        var data = grid_lecturas_sector.row(tr).data();
        obtener_promedio(data);
    });
});