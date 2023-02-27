var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar (a, b) {
  $("#btn_nuevo").prop("disabled", b);
  $("#btn_modificar").prop("disabled", a);
  $("#btn_eliminar").prop("disabled", a);
  $("#btn_aceptar").prop("disabled", a);
  $("#btn_cancelar").prop("disabled", a);
  $("#btn_reciclar").prop("disabled", b);
  $("#txt_rut").prop("disabled", a);
  $("#txt_rol").prop("disabled", a);
  $("#txt_nombres").prop("disabled", a);
  $("#txt_ape_pat").prop("disabled", a);
  $("#txt_ape_mat").prop("disabled", a);
  $("#dt_email").prop("disabled", a);
  $("#dt_fecha_nacimiento").prop("disabled", a);
  $("#dt_fecha_entrada").prop("disabled", a);
  $("#cmb_sexo").prop("disabled", a);
  $("#cmb_region").prop("disabled", a);
  $("#cmb_provincia").prop("disabled", a);
  $("#cmb_comuna").prop("disabled", a);
  $("#txt_calle").prop("disabled", a);
  $("#txt_numero").prop("disabled", a);
  $("#txt_resto_direccion").prop("disabled", a);
  $("#txt_ruta").prop("disabled", a);
}

function mostrar_datos_socios (data) {
  $("#txt_id_socio").val(data["id_socio"]);
  if (data["rut"] != null) {
    $("#txt_rut").val(Fn.formatear(data["rut"]));
  }
  if (data["rol"] != null) {
    $("#txt_rol").val(data["rol"]);
  }
  if (data["nombres"] != null) {
    $("#txt_nombres").val(data["nombres"]);
  }
  if (data["ape_pat"] != null) {
    $("#txt_ape_pat").val(data["ape_pat"]);
  }
  if (data["ape_mat"] != null) {
    $("#txt_ape_mat").val(data["ape_mat"]);
  }
  if (data["fecha_entrada"] != null) {
    $("#dt_fecha_entrada").val(data["fecha_entrada"]);
  }
  if (data["fecha_nacimiento"] != null) {
    $("#dt_fecha_nacimiento").val(data["fecha_nacimiento"]);
  }
  if (data["id_sexo"] != null) {
    $("#cmb_sexo").val(data["id_sexo"]);
  }
  if (data["id_region"] != null) {
    $("#cmb_region").val(data["id_region"]);
  }
  if (data["id_provincia"] != null) {
    $("#cmb_provincia").val(data["id_provincia"]);
  }
  if (data["id_comuna"] != null) {
    $("#cmb_comuna").val(data["id_comuna"]);
  }
  if (data["calle"] != null) {
    $("#txt_calle").val(data["calle"]);
  }
  if (data["numero"] != null) {
    $("#txt_numero").val(data["numero"]);
  }
  if (data["resto_direccion"] != null) {
    $("#txt_resto_direccion").val(data["resto_direccion"]);
  }
  if (data["ruta"] != null) {
    $("#txt_ruta").val(data["ruta"]);
  }
  if (data["email"] != null) {
    $("#dt_email").val(data["email"]);
  }
}

function llenar_cmb_region () {
  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_region",
  }).done(function (data) {
    $("#cmb_region").html('');

    var opciones = "<option value=\"\">Seleccione una region</option>";

    for (var i = 0; i < data.length; i++) {
      opciones += "<option value=\"" + data[i].id + "\">" + data[i].region + "</option>";
    }

    $("#cmb_region").append(opciones);
  }).fail(function (error) {
    respuesta = JSON.parse(error["responseText"]);
    alerta.error("alerta", respuesta.message);
  });
}

function llenar_cmb_provincia () {
  var id_region = $("#cmb_region").val();

  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_provincia/" + id_region,
  }).done(function (data) {
    $("#cmb_provincia").html('');

    var opciones = "<option value=\"\">Seleccione una provincia</option>";

    for (var i = 0; i < data.length; i++) {
      opciones += "<option value=\"" + data[i].id + "\">" + data[i].provincia + "</option>";
    }

    $("#cmb_provincia").append(opciones);
  }).fail(function (error) {
    respuesta = JSON.parse(error["responseText"]);
    alerta.error("alerta", respuesta.message);
  });
}

function llenar_cmb_comuna () {
  var id_provincia = $("#cmb_provincia").val();

  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_comuna/" + id_provincia,
  }).done(function (data) {
    $("#cmb_comuna").html('');

    var opciones = "<option value=\"\">Seleccione una comuna</option>";

    for (var i = 0; i < data.length; i++) {
      opciones += "<option value=\"" + data[i].id + "\">" + data[i].comuna + "</option>";
    }

    $("#cmb_comuna").append(opciones);
  }).fail(function (error) {
    respuesta = JSON.parse(error["responseText"]);
    alerta.error("alerta", respuesta.message);
  });
}

function guardar_socio () {
  var id_socio = $("#txt_id_socio").val();
  var rut = $("#txt_rut").val();
  rut = rut.split('.').join('');
  var rol = $("#txt_rol").val();
  var nombres = $("#txt_nombres").val();
  var ape_pat = $("#txt_ape_pat").val();
  var ape_mat = $("#txt_ape_mat").val();
  var fecha_entrada = $("#dt_fecha_entrada").val();
  var fecha_nacimiento = $("#dt_fecha_nacimiento").val();
  var id_sexo = $("#cmb_sexo").val();
  var id_region = $("#cmb_region").val();
  var id_provincia = $("#cmb_provincia").val();
  var id_comuna = $("#cmb_comuna").val();
  var calle = $("#txt_calle").val();
  var numero = $("#txt_numero").val();
  var resto_direccion = $("#txt_resto_direccion").val();
  var ruta = $("#txt_ruta").val();
  var email = $("#dt_email").val();

  $.ajax({
    url: base_url + "/Formularios/Ctrl_socios/guardar_socio",
    type: "POST",
    async: false,
    data: {
      id_socio: id_socio,
      rut: rut,
      rol: rol,
      nombres: nombres,
      ape_pat: ape_pat,
      ape_mat: ape_mat,
      fecha_entrada: fecha_entrada,
      fecha_nacimiento: fecha_nacimiento,
      id_sexo: id_sexo,
      id_region: id_region,
      id_provincia: id_provincia,
      id_comuna: id_comuna,
      calle: calle,
      numero: numero,
      resto_direccion: resto_direccion,
      ruta: ruta,
      email: email
    },
    success: function (respuesta) {
      const OK = 1;
      if (respuesta == OK) {
        $("#grid_socios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_socios/datatable_socios");
        $("#form_socios")[0].reset();
        des_habilitar(true, false);
        alerta.ok("alerta", "Socio guardado con éxito");
        $("#datosSocio").collapse("hide");
        datatable_enabled = true;
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

var Fn = {
  // Valida el rut con su cadena completa "XXXXXXXX-X"
  validaRut: function (rutCompleto) {
    var rutCompleto_ = rutCompleto.replace(/\./g, "");

    if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rutCompleto_))
      return false;
    var tmp = rutCompleto_.split('-');
    var digv = tmp[1];
    var rut = tmp[0];
    if (digv == 'K') digv = 'k';
    return (Fn.dv(rut) == digv);
  },
  dv: function (T) {
    var M = 0, S = 1;
    for (; T; T = Math.floor(T / 10))
      S = (S + T % 10 * (9 - M++ % 6)) % 11;
    return S ? S - 1 : 'k';
  },
  formatear: function (rut) {
    var tmp = this.quitar_formato(rut);
    var rut = tmp.substring(0, tmp.length - 1), f = "";
    while (rut.length > 3) {
      f = '.' + rut.substr(rut.length - 3) + f;
      rut = rut.substring(0, rut.length - 3);
    }
    return ($.trim(rut) == '') ? '' : rut + f + "-" + tmp.charAt(tmp.length - 1);
  },
  quitar_formato: function (rut) {
    rut = rut.split('-').join('').split('.').join('');
    return rut;
  }
}

function cambiar_estado_socio (observacion, id_socio, estado) {
  $.ajax({
    url: base_url + "/Formularios/Ctrl_socios/cambiar_estado_socio",
    type: "POST",
    async: false,
    data: {
      id_socio: id_socio,
      estado: estado,
      observacion: observacion
    },
    success: function (respuesta) {
      const OK = 1;
      if (respuesta == OK) {
        $("#grid_socios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_socios/datatable_socios");
        $("#form_socios")[0].reset();
        des_habilitar(true, false);
        if (estado == "eliminar") {
          alerta.ok("alerta", "Socio eliminado con éxito");
        } else {
          $('#dlg_reciclar_socio').modal('hide');
          alerta.ok("alerta", "Socio recuperado con éxito");
        }
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

$(document).ready(function () {
  $("#txt_id_socio").prop("disabled", true);
  des_habilitar(true, false);
  llenar_cmb_region();
  llenar_cmb_provincia();
  llenar_cmb_comuna();

  $("#btn_nuevo").on("click", function () {
    des_habilitar(false, true);
    $("#form_socios")[0].reset();

    $("#btn_modificar").prop("disabled", true);
    $("#btn_eliminar").prop("disabled", true);
    $('#dt_fecha_entrada').data("DateTimePicker").defaultDate(new Date());
    $("#datosSocio").collapse("show");
  });

  $("#btn_modificar").on("click", function () {
    des_habilitar(false, true);
    $("#btn_modificar").prop("disabled", true);
    $("#btn_eliminar").prop("disabled", true);
    datatable_enabled = false;
    $("#datosSocio").collapse("show");
  });

  $("#btn_eliminar").on("click", function () {
    var nombres = $("#txt_nombres").val();
    var ape_pat = $("#txt_ape_pat").val();
    var ape_mat = $("#txt_ape_mat").val();

    var nombre_socio = nombres + " " + ape_pat + " " + ape_mat;
    Swal.fire({
      title: "¿Eliminar Socio?",
      text: "¿Está seguro de eliminar a " + nombre_socio + "?",
      input: 'text',
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si",
      cancelButtonText: "No"
    }).then((result) => {
      if (result.isConfirmed) {
        var id_socio = $("#txt_id_socio").val();
        cambiar_estado_socio(result.value, id_socio, "eliminar");
      }
    });
  });

  $("#btn_reciclar").on("click", function () {
    $("#divContenedorReciclarSocio").load(
     base_url + "/Formularios/Ctrl_socios/v_socio_reciclar"
    );

    $('#dlg_reciclar_socio').modal('show');
  });

  $("#btn_aceptar").on("click", function () {
    if ($("#form_socios").valid()) {
      guardar_socio();
    }
  });

  $("#btn_cancelar").on("click", function () {
    $("#form_socios")[0].reset();
    des_habilitar(true, false);
    datatable_enabled = true;
    $("#datosSocio").collapse("hide");
  });

  $("#txt_rut").on("blur", function () {
    if (this.value != "") {
      if (Fn.validaRut(Fn.formatear(this.value))) {
        this.value = convertirMayusculas(Fn.formatear(this.value));
      } else {
        alerta.error("alerta", "RUT incorrecto");
        this.value = "";
        setTimeout(function () {
          $("#txt_rut").focus();
        }, 100);
      }
    }
  });

  $("#cmb_region").on("change", function () {
    llenar_cmb_provincia();
  });

  $("#cmb_provincia").on("change", function () {
    llenar_cmb_comuna();
  });

  $("#txt_nombres").on("blur", function () {
    this.value = convertirMayusculas(this.value);
  });

  $("#txt_ape_pat").on("blur", function () {
    this.value = convertirMayusculas(this.value);
  });

  $("#txt_ape_mat").on("blur", function () {
    this.value = convertirMayusculas(this.value);
  });

  $("#dt_fecha_entrada").datetimepicker({
    format: "DD-MM-YYYY",
    useCurrent: false,
    locale: moment.locale("es")
  });

  $("#dt_fecha_nacimiento").datetimepicker({
    format: "DD-MM-YYYY",
    useCurrent: false,
    locale: moment.locale("es")
  });

  $("#txt_calle").on("blur", function () {
    this.value = convertirMayusculas(this.value);
  });

  $("#txt_resto_direccion").on("blur", function () {
    this.value = convertirMayusculas(this.value);
  });

  $.validator.addMethod("letras", function (value, element) {
    return this.optional(element) || /^[a-zA-ZñÑáÁéÉíÍóÓúÚ ]*$/.test(value);
  });

  $.validator.addMethod("charspecial", function (value, element) {
    return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
  });

  $.validator.addMethod("rut", function (value, element) {
    return this.optional(element) || /^[0-9-.Kk]*$/.test(value);
  });

  $("#form_socios").validate({
    debug: true,
    errorClass: "my-error-class",
    highlight: function (element, required) {
      $(element).css('border', '2px solid #FDADAF');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).css('border', '1px solid #CCC');
    },
    rules: {
      txt_rut: {
        rut: true,
        maxlength: 12
      },
      txt_rol: {
        required: true,
        charspecial: true,
        maxlength: 45
      },
      txt_nombres: {
        required: true,
        letras: true,
        maxlength: 45
      },
      txt_ape_pat: {
        required: true,
        letras: true,
        maxlength: 45
      },
      txt_ape_mat: {
        letras: true,
        maxlength: 45
      },
      dt_fecha_entrada: {
        required: true,
      },
      txt_calle: {
        charspecial: true,
        maxlength: 60
      },
      txt_numero: {
        digits: true,
        maxlength: 6
      },
      txt_resto_direccion: {
        charspecial: true,
        maxlength: 200
      },
      txt_ruta: {
        charspecial: true,
        maxlength: 45
      }
    },
    messages: {
      txt_rut: {
        rut: "Solo números o k",
        maxlength: "Máximo 10 caracteres"
      },
      txt_rol: {
        required: "ROL es obliatorio",
        charspecial: "Hay caracteres extraños no permitdos",
        maxlength: "Máximo 45 caracteres"
      },
      txt_nombres: {
        required: "El nombre de socio es obliatorio",
        letras: "Solo puede ingresar letras",
        maxlength: "Máximo 45 caracteres"
      },
      txt_ape_pat: {
        required: "El apellido paterno es obligatorio",
        letras: "Solo puede ingresar letras",
        maxlength: "Máximo 45 caracteres"
      },
      txt_ape_mat: {
        letras: "Solo puede ingresar letras",
        maxlength: "Máximo 45 caracteres"
      },
      dt_fecha_entrada: {
        required: "La fecha de entrada es obligatoria"
      },
      txt_calle: {
        charspecial: "Ha ingresado caracteres no permitdos",
        maxlength: "Máximo 60 caracteres"
      },
      txt_numero: {
        digits: "Solo números",
        maxlength: "Máximo 6 dígitos"
      },
      txt_resto_direccion: {
        charspecial: "Hay caracteres extraños no permitdos",
        maxlength: "Máximo 200 caracteres"
      },
      txt_ruta: {
        charspecial: "Hay caracteres extraños no permitdos",
        maxlength: "Máximo 45 caracteres"
      }
    }
  });

  var grid_socios = $("#grid_socios").DataTable({
    responsive: true,
    paging: true,
    // scrollY: '50vh',
    // scrollCollapse: true,
    destroy: true,
    select: {
      toggleable: false
    },
    ajax: base_url + "/Formularios/Ctrl_socios/datatable_socios",
    orderClasses: true,
    columns: [
      {"data": "id_socio"},
      {"data": "rut"},
      {"data": "rol"},
      {"data": "nombres"},
      {"data": "ape_pat"},
      {"data": "ape_mat"},
      {"data": "nombre_completo"},
      {"data": "fecha_entrada"},
      {"data": "fecha_nacimiento"},
      {"data": "id_sexo"},
      {"data": "id_region"},
      {"data": "id_provincia"},
      {"data": "id_comuna"},
      {"data": "comuna"},
      {"data": "calle"},
      {"data": "numero"},
      {"data": "resto_direccion"},
      {"data": "usuario"},
      {"data": "fecha"},
      {
        "data": "id_socio",
        "render": function (data, type, row) {
          return "<button type='button' class='traza_socio btn btn-warning' title='Traza socio'><i class='fas fa-shoe-prints'></i></button>";
        }
      },
      {"data": "ruta"}
    ],
    "columnDefs": [
      {"targets": [0, 3, 4, 5, 9, 10, 11, 12, 14, 15, 16, 20], "visible": false, "searchable": false}
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
                pageSize: 'LETTER'
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

  $("#grid_socios tbody").on("click", "tr", function () {
    if (datatable_enabled) {
      var tr = $(this).closest('tr');
      if ($(tr).hasClass('child')) {
        tr = $(tr).prev();
      }

      var data = grid_socios.row(tr).data();
      mostrar_datos_socios(data);
      des_habilitar(true, false);
      $("#btn_modificar").prop("disabled", false);
      $("#btn_eliminar").prop("disabled", false);
      $("#datosSocio").collapse("hide");
    }
  });

  $("#grid_socios tbody").on("click", "button.traza_socio", function () {
    if (datatable_enabled) {
      $("#divContenedorTrazaSocio").load(
       base_url + "/Formularios/Ctrl_socios/v_socio_traza"
      );

      $('#dlg_traza_socio').modal('show');
    }
  });
});