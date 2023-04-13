var base_url = $("#txt_base_url").val();

function des_habilitar (a, b) {
  $("#btn_nuevo").prop("disabled", b);
  $("#btn_modificar").prop("disabled", a);
  $("#btn_eliminar").prop("disabled", a);
  $("#btn_aceptar").prop("disabled", a);
  $("#btn_cancelar").prop("disabled", a);
  $("#btn_importar").prop("disabled", b);

  $("#txt_id_socio").prop("disabled", a);
  $("#txt_rut_socio").prop("disabled", a);
  $("#txt_rol").prop("disabled", a);
  $("#txt_nombre_socio").prop("disabled", a);
  $("#btn_buscar_socio").prop("disabled", a);
  $("#txt_id_arranque").prop("disabled", a);
  $("#txt_sector").prop("disabled", a);
  $("#txt_diametro").prop("disabled", a);
  $("#txt_subsidio").prop("disabled", a);
  $("#txt_tope_subsidio").prop("disabled", a);
  $("#txt_monto_subsidio").prop("disabled", a);
  $("#dt_fecha_ingreso").prop("disabled", a);
  $("#dt_fecha_vencimiento").prop("disabled", a);
  $("#txt_c_anterior").prop("disabled", a);
  $("#txt_c_actual").prop("disabled", a);
  $("#txt_metros").prop("disabled", a);
  $("#txt_total_metros").prop("disabled", a);
  $("#txt_subtotal").prop("disabled", a);
  $("#txt_multa").prop("disabled", a);
  $("#txt_total_servicios").prop("disabled", a);
  $("#txt_cuota_repactacion").prop("disabled", a);
  $("#txt_monto_facturable").prop("disabled", a);
  $("#txt_total_mes").prop("disabled", a);
  $("#txt_alcantarillado").prop("disabled", a);
  $("#txt_cuota_socio").prop("disabled", a);
  $("#txt_otros").prop("disabled", a);
}

function mostrar_datos_metros (data) {
  $("#txt_id_metros").val(data["id_metros"]);
  $("#txt_id_socio").val(data["id_socio"]);
  $("#txt_rut_socio").val(data["rut_socio"]);
  $("#txt_rol").val(data["rol_socio"]);
  $("#txt_nombre_socio").val(data["nombre_socio"]);
  $("#txt_id_arranque").val(data["id_arranque"]);
  $("#txt_subsidio").val(data["subsidio"]);
  $("#txt_tope_subsidio").val(data["tope_subsidio"]);
  $("#txt_monto_subsidio").val(peso.formateaNumero(data["monto_subsidio"]));
  $("#txt_sector").val(data["sector"]);
  $("#txt_diametro").val(data["diametro"]);
  $("#dt_fecha_ingreso").val(data["fecha_ingreso"]);
  $("#dt_fecha_vencimiento").val(data["fecha_vencimiento"]);
  $("#txt_c_anterior").val(data["consumo_anterior"]);
  $("#txt_c_actual").val(data["consumo_actual"]);
  $("#txt_metros").val(data["metros"]);
  $("#txt_subtotal").val(peso.formateaNumero(data["subtotal"]));
  $("#txt_multa").val(peso.formateaNumero(data["multa"]));
  $("#txt_total_servicios").val(peso.formateaNumero(data["total_servicios"]));
  $("#txt_cuota_repactacion").val(peso.formateaNumero(data["cuota_repactacion"]));
  $("#txt_monto_facturable").val(peso.formateaNumero(data["monto_facturable"]));
  $("#txt_cargo_fijo").val(peso.formateaNumero(data["cargo_fijo"]));
  $("#txt_total_mes").val(peso.formateaNumero(data["total_mes"]));
  $("#txt_cuota_socio").val(peso.formateaNumero(data["cuota_socio"]));
  $("#txt_otros").val(peso.formateaNumero(data["otros"]));

  $("#grid_costo_metros").dataTable().fnReloadAjax(base_url + "/Consumo/Ctrl_metros/datatable_costo_metros/" + data["metros"] + "/" + data["id_diametro"]);

}

function guardar_metros () {
  var id_metros = $("#txt_id_metros").val();
  var id_socio = $("#txt_id_socio").val();
  var monto_subsidio = peso.quitar_formato($("#txt_monto_subsidio").val());
  var fecha_ingreso = $("#dt_fecha_ingreso").val();
  var fecha_vencimiento = $("#dt_fecha_vencimiento").val();
  var consumo_anterior = $("#txt_c_anterior").val();
  var consumo_actual = $("#txt_c_actual").val();
  var metros = $("#txt_metros").val();
  var subtotal = peso.quitar_formato($("#txt_subtotal").val());
  var multa = peso.quitar_formato($("#txt_multa").val());
  var total_servicios = peso.quitar_formato($("#txt_total_servicios").val());
  var cuota_repactacion = peso.quitar_formato($("#txt_cuota_repactacion").val());
  var total_mes = peso.quitar_formato($("#txt_total_mes").val());
  var cargo_fijo = peso.quitar_formato($("#txt_cargo_fijo").val());
  var monto_facturable = peso.quitar_formato($("#txt_monto_facturable").val());
  var alcantarillado = peso.quitar_formato($("#txt_alcantarillado").val());
  var cuota_socio = peso.quitar_formato($("#txt_cuota_socio").val());
  var otros = peso.quitar_formato($("#txt_otros").val());

  $.ajax({
    url: base_url + "/Consumo/Ctrl_metros/guardar_metros",
    type: "POST",
    async: false,
    data: {
      id_metros: id_metros,
      id_socio: id_socio,
      monto_subsidio: monto_subsidio,
      fecha_ingreso: fecha_ingreso,
      fecha_vencimiento: fecha_vencimiento,
      consumo_anterior: consumo_anterior,
      consumo_actual: consumo_actual,
      metros: metros,
      subtotal: subtotal,
      multa: multa,
      total_servicios: total_servicios,
      cuota_repactacion: cuota_repactacion,
      total_mes: total_mes,
      cargo_fijo: cargo_fijo,
      monto_facturable: monto_facturable,
      alcantarillado: alcantarillado,
      cuota_socio: cuota_socio,
      otros: otros
    },
    success: function (respuesta) {
      if (respuesta == 1) {
        $("#grid_metros").dataTable().fnReloadAjax(base_url + "/Consumo/Ctrl_metros/datatable_metros");
        $("#form_metros")[0].reset();
        des_habilitar(true, false);
        $("#grid_costo_metros").DataTable().clear().draw();
        alerta.ok("alerta", "Consumo de metros, guardado con éxito");
        $("#datosMetros").collapse("hide");
      } else {
        alerta.error("alerta", respuesta);
      }
    },
    error: function (error) {
      respuesta = JSON.parse(error["responseText"]);
      alerta.error("alerta", respuesta.message);
    }
  });
}

function eliminar_metros (observacion, id_metros) {
  $.ajax({
    url: base_url + "/Consumo/Ctrl_metros/eliminar_metros",
    type: "POST",
    async: false,
    data: {
      id_metros: id_metros,
      observacion: observacion
    },
    success: function (respuesta) {
      const OK = 1;

      if (respuesta == OK) {
        alerta.ok("alerta", "Consumo de metros, eliminado con éxito");
        $("#grid_metros").dataTable().fnReloadAjax(base_url + "/Consumo/Ctrl_metros/datatable_metros");
      } else {
        alerta.error("alerta", respuesta);
      }
    },
    error: function (error) {
      respuesta = JSON.parse(error["responseText"]);
      alerta.error("alerta", respuesta.message);
    }
  });
}

function convertirMayusculas (texto) {
  var text = texto.toUpperCase().trim();
  return text;
}

function habilita_consumo_actual () {
  if ($("#dt_fecha_ingreso").valid() && $("#dt_fecha_vencimiento").valid()) {
    $("#txt_c_actual").prop("readonly", false);
  } else {
    $("#txt_c_actual").val("");
    $("#txt_c_actual").prop("readonly", true);
  }
}

function calcular_total_servicios () {
  var fecha_vencimiento = $("#dt_fecha_vencimiento").val();
  var id_socio = $("#txt_id_socio").val();

  $.ajax({
    url: base_url + "/Consumo/Ctrl_metros/calcular_total_servicios",
    type: "POST",
    async: false,
    dataType: "json",
    data: {
      fecha_vencimiento: fecha_vencimiento,
      id_socio: id_socio
    },
    success: function (respuesta) {
      $("#txt_total_servicios").val(peso.formateaNumero(respuesta.total_servicios));
      $("#txt_cuota_repactacion").val(peso.formateaNumero(respuesta.cuota_repactacion));
    },
    error: function (error) {
      respuesta = JSON.parse(error["responseText"]);
      alerta.error("alerta", respuesta.message);
    }
  });
}

function calcular_montos () {
  var consumo_anterior = $("#txt_c_anterior").val();
  var consumo_actual = $("#txt_c_actual").val();
  var tope_subsidio = $("#txt_tope_subsidio").val();

  if (parseInt(consumo_actual) >= parseInt(consumo_anterior)) {
    var metros_consumidos = parseInt(consumo_actual) - parseInt(consumo_anterior);
    $("#txt_metros").val(metros_consumidos);
    var subtotal = 0;
    var base = 0;
    var total_subsidio = 0;

    for (var i = 0; i <= metros_consumidos; i++) {
      var e = 0;
      $("#grid_costo_metros").DataTable().rows().data().each(function (value) {
        if (i >= parseInt(value.desde) && i <= parseInt(value.hasta)) {
          if (value.id_costo_metros == 0) {
            subtotal = parseInt(value.costo);
            base = parseInt(value.costo);
          } else {
            subtotal += parseInt(value.costo);
          }

          if (i <= parseInt(tope_subsidio)) {
            total_subsidio = subtotal - base;
          }

          if (value.id_costo_metros == 0) {
            var cantidad = i - parseInt(value.desde);
          } else {
            var cantidad = (i + 1) - parseInt(value.desde);
          }

          this.cell({row: e, column: 1}).data(parseInt(cantidad));
        }

        e++;
      });
    }
    $("#txt_subtotal").val(peso.formateaNumero(subtotal));
    var subsidio_arr = $("#txt_subsidio").val().split("%");
    var subsidio = parseInt(subsidio_arr[0]);
    var monto_subsidio = total_subsidio * subsidio / 100;
    $("#txt_monto_subsidio").val(peso.formateaNumero(Math.round(monto_subsidio)));
    calcular_total();
  } else {
    $("#txt_c_actual").val("");
    alerta.aviso("alerta", "El <strong>consumo actual</strong>, no puede ser menor al <strong>consumo anterior</strong>");
  }
}

var peso = {
  validaEntero: function (value) {
    var RegExPattern = /[0-9]+$/;
    return RegExPattern.test(value);
  },
  formateaNumero: function (value) {
    if (peso.validaEntero(value)) {
      var retorno = '';
      value = value.toString().split('').reverse().join('');
      var i = value.length;
      while (i > 0) retorno += ((i % 3 === 0 && i != value.length) ? '.' : '') + value.substring(i--, i);
      return retorno;
    }
    return 0;
  },
  quitar_formato: function (numero) {
    numero = numero.split('.').join('');
    return numero;
  }
}

function calcular_total () {

  var subtotal = $("#txt_subtotal").val() == "" ? 0 : peso.quitar_formato($("#txt_subtotal").val());
  var multa = $("#txt_multa").val() == "" ? 0 : peso.quitar_formato($("#txt_multa").val());
  var total_servicios = $("#txt_total_servicios").val() == "" ? 0 : peso.quitar_formato($("#txt_total_servicios").val());
  var cuota_repactacion = $("#txt_cuota_repactacion").val() == "" ? 0 : peso.quitar_formato($("#txt_cuota_repactacion").val());
  var monto_subsidio = $("#txt_monto_subsidio").val() == "" ? 0 : peso.quitar_formato($("#txt_monto_subsidio").val());
  var alcantarillado = $("#txt_alcantarillado").val() == "" ? 0 : peso.quitar_formato($("#txt_alcantarillado").val());
  var cuota_socio = $("#txt_cuota_socio").val() == "" ? 0 : peso.quitar_formato($("#txt_cuota_socio").val());
  var otros = $("#txt_otros").val() == "" ? 0 : peso.quitar_formato($("#txt_otros").val());

  var monto_facturable = parseInt(monto_subsidio) > parseInt(subtotal) ? total_mes = 0 : parseInt(subtotal) - parseInt(monto_subsidio);
  var total_mes =
   parseInt(monto_facturable) + parseInt(cuota_repactacion) + parseInt(multa) + parseInt(total_servicios) + parseInt(alcantarillado) + parseInt(cuota_socio) + parseInt(otros);
  $("#txt_total_mes").val(peso.formateaNumero(total_mes));
  $("#txt_monto_facturable").val(peso.formateaNumero(monto_facturable));
}

function existe_consumo_mes () {
  var id_socio = $("#txt_id_socio").val();
  var fecha_ingreso = $("#dt_fecha_ingreso").val();

  $.ajax({
    url: base_url + "/Consumo/Ctrl_metros/existe_consumo_mes",
    type: "POST",
    async: false,
    data: {
      id_socio: id_socio,
      fecha_ingreso: fecha_ingreso
    },
    success: function (respuesta) {
      if (parseInt(respuesta) > 0) {
        $("#dt_fecha_ingreso").val("");
        $("#dt_fecha_vencimiento").val("");
        alerta.aviso("alerta", "Hay un consumo ingresado este mes, para este socio");
      } else {
        habilita_consumo_actual();
      }
    },
    error: function (error) {
      respuesta = JSON.parse(error["responseText"]);
      alerta.error("alerta", respuesta.message);
    }
  });
}

$(document).ready(function () {
  $("#txt_id_metros").prop("disabled", true);
  $("#txt_id_socio").prop("readonly", true);
  $("#txt_rut_socio").prop("readonly", true);
  $("#txt_rol").prop("readonly", true);
  $("#txt_nombre_socio").prop("readonly", true);
  $("#txt_id_arranque").prop("readonly", true);
  $("#txt_sector").prop("readonly", true);
  $("#txt_diametro").prop("readonly", true);
  $("#txt_subsidio").prop("readonly", true);
  $("#txt_tope_subsidio").prop("readonly", true);
  $("#txt_monto_subsidio").prop("readonly", true);
  $("#txt_c_anterior").prop("readonly", false);
  $("#txt_metros").prop("readonly", true);
  $("#txt_subtotal").prop("readonly", true);
  $("#txt_total_servicios").prop("readonly", true);
  $("#txt_cuota_repactacion").prop("readonly", true);
  $("#txt_total_mes").prop("readonly", true);
  $("#txt_c_actual").prop("readonly", true);
  $("#dt_fecha_ingreso").prop("readonly", true);
  $("#dt_fecha_vencimiento").prop("readonly", true);
  $("#txt_txt_total_mes_fijo").prop("readonly", true);
  $("#txt_monto_facturable").prop("readonly", true);
  $("#txt_alcantarillado").prop("readonly", true);
  $("#txt_cuota_socio").prop("readonly", true);
  $("#txt_otros").prop("readonly", true);

  des_habilitar(true, false);

  $("#btn_nuevo").on("click", function () {
    des_habilitar(false, true);
    $("#dt_fecha_ingreso").val("01-01-2021");
    $("#dt_fecha_vencimiento").val("01-01-2021");
    $("#form_metros")[0].reset();
    $("#grid_costo_metros").DataTable().clear().draw();
    $("#btn_modificar").prop("disabled", true);
    $("#btn_eliminar").prop("disabled", true);
    $("#datosMetros").collapse("show");
  });

  $("#btn_modificar").on("click", function () {
    des_habilitar(false, true);
    $("#btn_modificar").prop("disabled", true);
    $("#btn_eliminar").prop("disabled", true);
    $("#dt_fecha_ingreso").prop("readonly", false);
    $("#dt_fecha_vencimiento").prop("readonly", false);
    $("#txt_c_actual").prop("readonly", false);
    $("#datosMetros").collapse("show");
  });

  $("#btn_eliminar").on("click", function () {
    Swal.fire({
      title: "¿Eliminar consumo de metros?",
      text: "¿Está seguro de eliminar el consumo de metros?",
      input: 'text',
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si",
      cancelButtonText: "No"
    }).then((result) => {
      if (result.isConfirmed) {
        var id_metros = $("#txt_id_metros").val();
        eliminar_metros(result.value, id_metros);
      }
    });
  });

  $("#btn_aceptar").on("click", function () {
    if ($("#form_metros").valid()) {
      guardar_metros();
    }
  });

  $("#btn_cancelar").on("click", function () {
    $("#form_metros")[0].reset();
    des_habilitar(true, false);
    $("#grid_costo_metros").DataTable().clear().draw();
    $("#datosMetros").collapse("hide");
  });

  $("#btn_importar").on("click", function () {
    $("#divContenedorImportar").load(
     base_url + "/Consumo/Ctrl_metros/v_importar_planilla"
    );

    $('#dlg_importar_planilla').modal('show');
  });

  $("#btn_buscar_socio").on("click", function () {
    $("#divContenedorBuscarSocio").load(
     base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_metros"
    );

    $('#dlg_buscar_socio').modal('show');
  });

  $("#dt_fecha_ingreso").datetimepicker({
    format: "MM-YYYY",
    useCurrent: false,
    locale: moment.locale("es")
  }).on("dp.change", function () {
    $("#dt_fecha_ingreso").blur();
  });

  $("#dt_fecha_ingreso").on("blur", function () {
    existe_consumo_mes();
  });

  $("#dt_fecha_vencimiento").datetimepicker({
    format: "DD-MM-YYYY",
    useCurrent: false,
    locale: moment.locale("es")
  }).on("dp.change", function () {
    $("#dt_fecha_vencimiento").blur();
  });

  $("#dt_fecha_vencimiento").on("blur", function () {
    habilita_consumo_actual();
    calcular_total_servicios();
  });

  $("#txt_c_actual").on("blur", function () {
    calcular_montos();
  });

  $("#txt_c_actual").keypress(function (event) {
    if (event.keyCode == 13) {
      this.blur();
    }
  });

  $("#txt_multa").on("blur", function () {
    var numero = peso.quitar_formato(this.value);
    this.value = peso.formateaNumero(numero);
    calcular_montos();
  });

  $("#txt_multa").keypress(function (event) {
    if (event.keyCode == 13) {
      this.blur();
    }
  });

  $("#form_metros").validate({
    debug: true,
    errorClass: "my-error-class",
    highlight: function (element, required) {
      $(element).css('border', '2px solid #FDADAF');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).css('border', '1px solid #CCC');
    },
    rules: {
      txt_id_socio: {
        required: true
      },
      dt_fecha_ingreso: {
        required: true
      },
      dt_fecha_vencimiento: {
        required: true
      },
      txt_c_anterior: {
        required: true,
        digits: true,
        maxlength: 11
      },
      txt_c_actual: {
        required: true,
        digits: true,
        maxlength: 11
      },
      txt_multa: {
        maxlength: 12
      }
    },
    messages: {
      txt_id_socio: {
        required: "Seleccione un socio, botón buscar"
      },
      dt_fecha_ingreso: {
        required: "Ingrese fecha de ingreso"
      },
      dt_fecha_vencimiento: {
        required: "Ingrese fecha de vencimiento"
      },
      txt_c_anterior: {
        required: "El consumo consumo_anterior es obligatorio",
        digits: "Solo números",
        maxlength: "Máximo 11 números"
      },
      txt_c_actual: {
        required: "El consumo actual es obligatorio",
        digits: "Solo números",
        maxlength: "Máximo 11 números"
      },
      txt_multa: {
        maxlength: "Máximo 11 números"
      }
    }
  });

  var grid_costo_metros = $("#grid_costo_metros").DataTable({
    responsive: true,
    paging: true,
    destroy: true,
    order: [[2, "asc"]],
    orderClasses: true,
    columns: [
      {"data": "id_costo_metros"},
      {"data": "metros"},
      {"data": "desde"},
      {"data": "hasta"},
      {"data": "costo"}
    ],
    "columnDefs": [
      {"targets": [0], "visible": false, "searchable": false}
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
        "rows": "<br/>%d Perfiles Seleccionados"
      },
      "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Sig.",
        "previous": "Ant."
      }
    }
  });

  var grid_metros = $("#grid_metros").DataTable({
    responsive: true,
    paging: true,
    destroy: true,
    order: [[0, "desc"]],
    select: {
      toggleable: false
    },
    ajax: base_url + "/Consumo/Ctrl_metros/datatable_metros",
    orderClasses: true,
    columns: [
      {"data": "id_metros"},
      {"data": "id_socio"},
      {"data": "rut_socio"},
      {"data": "rol_socio"},
      {"data": "nombre_socio"},
      {"data": "id_arranque"},
      {"data": "subsidio"},
      {
        "data": "monto_subsidio",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      },
      {"data": "sector"},
      {"data": "id_diametro"},
      {"data": "diametro"},
      {"data": "fecha_ingreso"},
      {"data": "fecha_vencimiento"},
      {"data": "consumo_anterior"},
      {"data": "consumo_actual"},
      {"data": "metros"},
      {
        "data": "subtotal",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      },
      {
        "data": "multa",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      },
      {
        "data": "total_servicios",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      },
      {
        "data": "total_mes",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      },
      {
        "data": "monto_facturable",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      },
      {
        "data": "cargo_fijo",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      },
      {"data": "usuario"},
      {"data": "fecha"},
      {
        "data": "id_metros",
        "render": function (data, type, row) {
          return "<button type='button' class='traza_metros btn btn-warning' title='Traza Metros'><i class='fas fa-shoe-prints'></i></button>";
        }
      },
      {
        "data": "cuota_repactacion",
        "render": function (data, type, row) {
          return peso.formateaNumero(data);
        }
      }
    ],
    "columnDefs": [
      {"targets": [0, 1, 2, 5, 6, 9, 10, 11, 12, 13, 14, 17, 18, 20, 21, 22, 25], "visible": false, "searchable": false}
    ],
    dom: 'Bfrtip',
    buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Socios"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Socios",
                orientation: 'landscape',
                pageSize: 'TABLOID'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Socios"
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
      "select": {
        "rows": "<br/>%d Perfiles Seleccionados"
      },
      "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Sig.",
        "previous": "Ant."
      }
    }
  });

  $("#grid_metros tbody").on("click", "tr", function () {
    var tr = $(this).closest('tr');
    if ($(tr).hasClass('child')) {
      tr = $(tr).prev();
    }

    var data = grid_metros.row(tr).data();
    mostrar_datos_metros(data);
    des_habilitar(true, false);
    $("#btn_modificar").prop("disabled", false);
    $("#btn_eliminar").prop("disabled", false);
    $("#datosMetros").collapse("hide");
  });

  $("#grid_metros tbody").on("click", "button.traza_metros", function () {
    $("#divContenedorTrazaMetros").load(
     base_url + "/Consumo/Ctrl_metros/v_metros_traza"
    );

    $('#dlg_traza_metros').modal('show');
  });
});