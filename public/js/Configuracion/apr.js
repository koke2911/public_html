var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar (a, b) {
  $("#btn_nuevo").prop("disabled", b);
  $("#btn_modificar").prop("disabled", a);
  $("#btn_subir_logo").prop("disabled", a);
  $("#btn_subir_certificado").prop("disabled", a);
  $("#btn_aceptar").prop("disabled", a);
  $("#btn_cancelar").prop("disabled", a);

  $("#txt_rut_apr").prop("disabled", a);
  $("#txt_nombre_apr").prop("disabled", a);
  $("#txt_hash_sii").prop("disabled", a);
  $("#txt_codigo_comercio").prop("disabled", a);
  $("#cmb_region").prop("disabled", a);
  $("#cmb_tipo_integracion").prop("disabled", a);
  $("#cmb_provincia").prop("disabled", a);
  $("#cmb_comuna").prop("disabled", a);
  $("#txt_resto_direccion").prop("disabled", a);
  $("#txt_tope_subsidio").prop("disabled", a);
  $("#txt_tope_subsidio50").prop("disabled", a);
  $("#txt_fono").prop("disabled", a);
  $("#txt_ultimo").prop("disabled", a);
  $("#txt_octava").prop("disabled", a);
  $("#txt_octava_web").prop("disabled", a);
  $("#txt_horas_extras").prop("disabled", a);
}

function habilitaApr(id_apr,modo) {

  if(modo==false){
    estado=0;
  }else{
    estado=1;
  }
  

  $.ajax({
    url: base_url + "/Configuracion/Ctrl_apr/habilita_apr",
    type: "POST",
    async: false,
    data: {
      id_apr: id_apr,
      estado: estado
    },
    success: function (respuesta) {
      const OK = 1;
      if (respuesta == OK) {
        $("#grid_apr").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_apr/datatable_apr");
        $("#form_APR")[0].reset();
        des_habilitar(true, false);
        alerta.ok("alerta", "APR guardada con éxito");
        $("#datosAPR").collapse("hide");
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

function mostrar_datos_apr (data) {
  $("#txt_id_apr").val(data["id_apr"]);
  $("#txt_rut_apr").val(data["rut_apr"]);
  $("#txt_nombre_apr").val(data["nombre_apr"]);
  $("#txt_hash_sii").val(data["hash_sii"]);
  $("#txt_codigo_comercio").val(data["codigo_comercio"]);
  $("#cmb_region").val(data["id_region"]);
  $("#cmb_tipo_integracion").val(data["tipo_integracion"]);
  $("#cmb_provincia").val(data["id_provincia"]);
  $("#cmb_comuna").val(data["id_comuna"]);
  $("#txt_resto_direccion").val(data["resto_direccion"]);
  $("#txt_tope_subsidio").val(data["tope_subsidio"]);
  $("#txt_tope_subsidio50").val(data["tope_subsidio50"]);
  $("#txt_fono").val(data["fono"]);
  $("#cmb_email").val(data['email']);
  $("#cmb_email_dte").val(data['email_dte']);
  $("#txt_website").val(data['website']);

  $("#txt_ultimo").val(data['ultimo_folio']);
  $("#txt_octava").val(data['clave_dete']);
  $("#txt_octava_web").val(data['clave_appoct']);
  $("#txt_horas_extras").val(data['horas_extras']);
  $("#txt_nombre_apr").val(data['nombre_apr']);
  $("#cmb_comuna").val(data['id_comuna']);
  $("#cmb_calle").val(data['calle']);
  $("#cmb_numero").val(data['numero']);

  console.log(data);


  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + '/Configuracion/Ctrl_apr/get_data_apr/' + data["id_apr"],
  })
   .done(function (response) {
     
     // $("#cmb_calle").val(response.address);
     // if (response.county_id !== null) {
     //   // $("#cmb_comuna").val(response.county_id);
     // }
     $("#cmb_activity").val(response.activity);
     $("#cmb_economic_activity_id").val(response.economic_activity_id);
     $("#cmb_resolution_date").val(response.resolution_date);
     $("#cmb_resolution_number").val(response.resolution_number);
   });
}

function llenar_cmb_region () {
  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_region",
  }).done(function (data) {
    $("#cmb_region").html('');

    opciones_region = "<option value=\"\">Seleccione una region</option>";

    for (var i = 0; i < data.length; i++) {
      opciones_region += "<option value=\"" + data[i].id + "\">" + data[i].region + "</option>";
    }

    $("#cmb_region").append(opciones_region);
  }).fail(function (error) {
    respuesta = JSON.parse(error["responseText"]);
    alerta.error("alerta", respuesta.message);
  });
}

function llenar_cmb_tipoIntegra() {
  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_tipoIntegra",
  }).done(function (data) {
    $("#cmb_tipo_integracion").html('');

    opciones_tipo = "<option value=\"\">Seleccione un tipo de integración</option>";

    for (var i = 0; i < data.length; i++) {
      opciones_tipo += "<option value=\"" + data[i].id + "\">" + data[i].tipo + "</option>";
    }

    $("#cmb_tipo_integracion").append(opciones_tipo);
  }).fail(function (error) {
    respuesta = JSON.parse(error["responseText"]);
    alerta.error("alerta", respuesta.message);
  });
}

function llenar_cmb_economic_activity () {
  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + "/Configuracion/Ctrl_usuarios/llenar_cmb_economic_activities",
  }).done(function (data) {
    $("#cmb_economic_activity_id").html('');

    opciones_actividad = "<option value=\"\">Seleccione una actividad económica</option>";

    for (var i = 0; i < data.length; i++) {
      opciones_actividad += "<option value=\"" + data[i].id + "\">" + data[i].name + "</option>";
    }

    $("#cmb_economic_activity_id").append(opciones_actividad);
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

function guardar_apr () {

  $.ajax({
    url: base_url + "/Configuracion/Ctrl_apr/guardar_apr",
    type: "POST",
    async: false,
    data: {
      id_apr: $("#txt_id_apr").val(),
      rut_apr: $("#txt_rut_apr").val().split(".").join(""),
      nombre_apr: $("#txt_nombre_apr").val(),
      hash_sii: $("#txt_hash_sii").val(),
      codigo_comercio: $("#txt_codigo_comercio").val(),
      id_comuna: $("#cmb_comuna").val(),
      resto_direccion: $("#txt_resto_direccion").val(),
      tope_subsidio: $("#txt_tope_subsidio").val(),
      tope_subsidio50: $("#txt_tope_subsidio50").val(),
      fono: $("#txt_fono").val(),
      email: $("#cmb_email").val(),
      email_dte: $("#cmb_email_dte").val(),
      calle: $("#cmb_calle").val(),
      numero: $("#cmb_numero").val(),
      activity: $("#cmb_activity").val(),
      economic_activity_id: $("#cmb_economic_activity_id").val(),
      resolution_date: $("#cmb_resolution_date").val(),
      resolution_number: $("#cmb_resolution_number").val(),
      website: $("#txt_website").val(),
      ultimo_folio:$("#txt_ultimo").val(),
      clave_dete:$("#txt_octava").val(),
      clave_appoct:$("#txt_octava_web").val()   ,
      horas_extras:$("#txt_horas_extras").val()   ,
      tipo_integracion:$("#cmb_tipo_integracion").val()   ,

    },
    success: function (respuesta) {
      const OK = 1;
      if (respuesta == OK) {
        $("#grid_apr").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_apr/datatable_apr");
        $("#form_APR")[0].reset();
        des_habilitar(true, false);
        alerta.ok("alerta", "APR guardada con éxito");
        $("#datosAPR").collapse("hide");
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


$(document).ready(function () {
  $("#txt_id_apr").prop("disabled", true);
  des_habilitar(true, false);
  llenar_cmb_region();
  llenar_cmb_provincia();
  llenar_cmb_comuna();
  llenar_cmb_economic_activity();

  llenar_cmb_tipoIntegra();

  $("#btn_nuevo").on("click", function () {
    des_habilitar(false, true);
    $("#form_APR")[0].reset();

    $("#btn_modificar").prop("disabled", true);
    $("#btn_subir_logo").prop("disabled", true);
    $("#btn_subir_certificado").prop("disabled", true);
    $("#datosAPR").collapse("show");
  });

  $("#btn_modificar").on("click", function () {
    des_habilitar(false, true);
    $("#btn_modificar").prop("disabled", true);
    $("#btn_subir_logo").prop("disabled", true);
    $("#btn_subir_certificado").prop("disabled", true);
    datatable_enabled = false;
    $("#datosAPR").collapse("show");
  });

  $("#btn_aceptar").on("click", function () {
    if ($("#form_APR").valid()) {
      guardar_apr();
    }
  });

  $("#btn_cancelar").on("click", function () {
    $("#form_APR")[0].reset();
    des_habilitar(true, false);
    datatable_enabled = true;
    $("#datosAPR").collapse("hide");
  });

  $("#btn_subir_logo").on("click", function () {
    $("#divContenedorImportar").load(
     base_url + "/Configuracion/Ctrl_apr/v_importar_logo"
    );

    $('#dlg_importar_logo').modal('show');
  });

  $("#btn_subir_certificado").on("click", function () {
    $("#divContenedorImportar2").load(
     base_url + "/Configuracion/Ctrl_apr/v_importar_certificado"
    );

    $('#dlg_importar_certificado').modal('show');
  });

  $("#txt_rut_apr").on("blur", function () {
    if (Fn.validaRut(Fn.formatear(this.value))) {
      $("#txt_rut_apr").val(convertirMayusculas(Fn.formatear(this.value)));
    } else {
      alerta.error("alerta", "RUT incorrecto");
      $("#txt_rut_apr").val("");
      setTimeout(function () {
        $("#txt_rut_apr").focus();
      }, 100);
    }
  });

  $("#cmb_region").on("change", function () {
    llenar_cmb_provincia();
  });

  $("#cmb_tipo_integracion").on("change", function () {
    

    if($("#cmb_tipo_integracion").val() == 1){
      $('#txt_octava').prop('disabled', true);
      $('#txt_octava_web').prop('disabled', true);
    }else{
      $('#txt_octava').prop('disabled', false);
      $('#txt_octava_web').prop('disabled', false);
    }
    

  });

  $("#cmb_provincia").on("change", function () {
    llenar_cmb_comuna();
  });

  $("#txt_nombre_apr").on("blur", function () {
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

  $.validator.addMethod("maxnumero", function (value, element) {
    if (value > 100) {
      return false;
    } else {
      return true;
    }
  })

  $("#form_APR").validate({
    debug: true,
    errorClass: "my-error-class",
    highlight: function (element, required) {
      $(element).css('border', '2px solid #FDADAF');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).css('border', '1px solid #CCC');
    },
    rules: {
      txt_rut_apr: {
        rut: true,
        required: true,
        maxlength: 12
      },
      txt_nombre_apr: {
        required: true,
        letras: true,
        maxlength: 100
      },
      txt_hash_sii: {
        maxlength: 200
      },
      txt_codigo_comercio: {
        maxlength: 200
      },
      cmb_region: {
        required: true
      },
      cmb_tipo_integracion:{
        required: true
      },
      cmb_provincia: {
        required: true
      },
      cmb_comuna: {
        required: true
      },
      txt_resto_direccion: {
        charspecial: true,
        maxlength: 200
      },
      txt_tope_subsidio: {
        digits: true,
        maxlength: 3,
        maxnumero: true
      },
      txt_tope_subsidio50: {
        digits: true,
        maxlength: 3,
        maxnumero: true
      },
      txt_fono: {
        digits: true,
        maxlength: 11
      },
      txt_ultimo: {
        digits: true,
        maxlength: 11
      },
      txt_octava: {
          maxlength: 100
      }

    },
    messages: {
      txt_rut_apr: {
        rut: "Solo números o k",
        required: "El RUT de la APR es obligatorio",
        maxlength: "Máximo 10 caracteres"
      },
      txt_nombre_apr: {
        required: "El nombre de la APR es obligatorio",
        letras: "Solo puede ingresar letras",
        maxlength: "Máximo 100 caracteres"
      },
      txt_hash_sii: {
        maxlength: "Máximo 200 caracteres"
      },
      txt_codigo_comercio: {
        maxlength: "Máximo 200 caracteres"
      },
      cmb_region: {
        required: "La región es obligatoria"
      },
      cmb_tipo_integracion: {
        required: "El tipo de integracion es obligatorio"
      },
      cmb_provincia: {
        required: "La provincia es obligatoria"
      },
      cmb_comuna: {
        required: "La comuna es obligatoria"
      },
      txt_resto_direccion: {
        charspecial: "Tiene caracteres no permitidos",
        maxlength: "Máximo 200 caracteres"
      },
      txt_tope_subsidio: {
        digits: "Solo números",
        maxlength: "Máximo 3 números",
        maxnumero: "Máximo hasta 100"
      },
      txt_tope_subsidio50: {
        digits: "Solo números",
        maxlength: "Máximo 3 números",
        maxnumero: "Máximo hasta 100"
      },
      txt_fono: {
        digits: "Solo números",
        maxlength: "Máximo 11 números"
      },
      txt_ultimo: {
        digits: "Solo números",
        maxlength: "Máximo 11 números"
      },
      txt_octava: {
          maxlength: "Máximo 100 números"
      }
    }
  });

  
  var grid_apr = $("#grid_apr").DataTable({
    responsive: true,
    paging: true,
    // scrollY: '50vh',
    // scrollCollapse: true,
    destroy: true,
    select: {
      toggleable: false
    },
    ajax: base_url + "/Configuracion/Ctrl_apr/datatable_apr",
    orderClasses: true,
    columns: [
      {"data": "id_apr"},
      {"data": "rut_apr"},
      {"data": "nombre_apr"},
      {"data": "hash_sii"},
      {"data": "codigo_comercio"},
      {"data": "tope_subsidio"},
      { "data": "tope_subsidio50" },
      {"data": "id_region"},
      {"data": "id_provincia"},
      {"data": "id_comuna"},
      {"data": "comuna"},
      {"data": "calle"},
      {"data": "numero"},
      {"data": "resto_direccion"},
      {"data": "usuario"},
      {"data": "fecha","visible":false},
      {
        "data": "id_apr",
        "render": function (data, type, row) {
          return "<button type='button' class='traza_apr btn btn-warning' title='Traza APR'><i class='fas fa-shoe-prints'></i></button>";
        }
      },
      {
        "data": "estado",
        "render": function (data, type, row) {
          // console.log(row);
          if (data == 1) {
          return "<button type='button' class='btn_activo btn-success' style='width:100%;' onclick='habilitaApr(" + row.id_apr+",false)'>Activo</button>";
          } else {
            return "<button type='button' class='btn_desactivo btn-danger' style='width:100%;' onclick='habilitaApr(" + row.id_apr+",true)'>Desactivado</button>";
          }
        }
      }
     
    ],
    "columnDefs": [
      {"targets": [0, 3, 4, 5, 6, 7, 8, 12, 16], "visible": false, "searchable": false}
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

  $("#grid_apr tbody").on("click", "tr", function () {
    if (datatable_enabled) {
      var tr = $(this).closest('tr');
      if ($(tr).hasClass('child')) {
        tr = $(tr).prev();
      }

      var data = grid_apr.row(tr).data();
      mostrar_datos_apr(data);
      des_habilitar(true, false);
      $("#btn_modificar").prop("disabled", false);
      $("#btn_subir_logo").prop("disabled", false);
      $("#btn_subir_certificado").prop("disabled", false);
      $("#datosAPR").collapse("hide");
    }
  });

  $("#grid_apr tbody").on("click", "button.traza_apr", function () {
    if (datatable_enabled) {
      $("#divContenedorTrazaAPR").load(
       base_url + "/Configuracion/Ctrl_apr/v_apr_traza"
      );

      $('#dlg_traza_apr').modal('show');
    }
  });
});


