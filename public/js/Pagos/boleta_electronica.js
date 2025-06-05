var base_url = $("#txt_base_url").val();

var txt_bafecta = $("#txt_bafecta").val();
var txt_fafecta = $("#txt_fafecta").val();
var txt_fexenta = $("#txt_fexenta").val();

function opciones_anular(idSii, id_metros,id_tipo_documento){
  Swal.fire({
    title: 'Opciones de la boleta',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: 'Anular',
    denyButtonText: 'Reenviar',
    cancelButtonText: 'Estado SII',
  }).then((result) => {
    if (result.isConfirmed) {
      anular_boleta(idSii, id_metros);
    } else if (result.isDenied) {
      reenviar_boleta(idSii, id_metros);
    } else if (result.isDismissed) {
      ver_estado_sii(idSii, id_tipo_documento);
    }
  });

function reenviar_boleta(idSii, id_metros) {
  $.ajax({
    url: base_url + "/Pagos/Ctrl_boleta_electronica/reenviar_boleta",
    type: "POST",
    data: { idSii: idSii, id_metros: id_metros },
    success: function (respuesta) {
      const OK = 1;
      if (respuesta == OK) {
        buscar_boletas();
        alerta.ok("alerta", "Boletas agregada a la cola con éxito");
        
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Errores',
          html: respuesta,
          footer: 'Procedimiento terminado con errores'
        });
      }
    }
  });
}
}

function ver_estado_sii(idSii, id_tipo_documento){
  $.ajax({
    url: base_url + "/Pagos/Ctrl_boleta_electronica/ver_estado_SII",
    type: "POST",
    data: { idSii: idSii, id_tipo_documento: id_tipo_documento },
    success: function (respuesta) {

      var data = JSON.parse(respuesta);
      var tableHtml = "<table border='1'>";
      for (var key in data.item) {
          tableHtml += "<tr><td>" + key + "</td><td>" + data.item[key] + "</td></tr>";
      }

      if (data.item.GlosaEstadoSII == "Documento Recibido por el SII. Datos Coinciden con los Registrados") {
        tableHtml += "<tr><td style='background-color:green;' colspan='2'>" + data.item.GlosaEstadoSII + "</td></tr>";
      }
      tableHtml += "</table>";
      Swal.fire({
        title: 'Estado SII',
        html: tableHtml,
      });
      // Display the tableHtml to the user

      
      }
  });
}

function anular_boleta(idSii, id_metros) {
  Swal.fire({
    title: '¿Está seguro de anular la boleta?',
    text: "No podrá revertir esta acción, debe realizar la regularización correspondiente en el sitio de SII- Para Factura Afecta se genera la NOTA DE CREDITO",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, anular'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        // url: base_url + "/Pagos/Ctrl_boleta_electronica/anular_boleta",
        url: base_url + "/Pagos/Ctrl_boleta_electronica/emitir_nota_credito",

        type: "POST",
        dataType: 'json',
        data: { idSii: idSii, folio_metros: id_metros},
        success: function (data) {
          if (data.codigo == 2 || data.codigo==1) {
            
            if(data.codigo==1){
              window.open(data.mensaje);
            }
            Swal.fire({
              title: 'Boleta anulada correctamente',
              icon: 'success',
              text: data.mensaje,
              confirmButtonText: 'Aceptar'
            });
            
            buscar_boletas();
          }else{  
            Swal.fire({
              title: 'Ha ocurrido un error',
              text: respuesta,
              icon: 'error',
              confirmButtonText: 'Aceptar'
            });
          }

        }
      });
    }
  });
}

function llenar_cmb_sector () {
  $.ajax({
    type: "GET",
    dataType: "json",
    url: base_url + "/Formularios/Ctrl_arranques/llenar_cmb_sector",
  }).done(function (data) {
    $("#cmb_sector").html('');

    var opciones = "<option value=\"\">Seleccione un sector</option>";

    for (var i = 0; i < data.length; i++) {
      opciones += "<option value=\"" + data[i].id + "\">" + data[i].sector + "</option>";
    }

    $("#cmb_sector").append(opciones);
  }).fail(function (error) {
    respuesta = JSON.parse(error["responseText"]);
    alerta.error("alerta", respuesta.message);
  });
}

function buscar_boletas () {
  var id_socio = $("#txt_id_socio").val();
  var mes_año = $("#dt_mes_año").val();
  var id_sector = $("#cmb_sector").val();

  var chk_boleta_exenta = $("#chk_boleta_exenta").is(":checked");
  var chk_boleta_afecta = $("#chk_boleta_afecta").is(":checked");
  var chk_factura_exenta = $("#chk_factura_exenta").is(":checked");
  var chk_factura_afecta = $("#chk_factura_afecta").is(":checked");

  if (!chk_boleta_exenta && !chk_boleta_afecta && !chk_factura_exenta && !chk_factura_afecta) {
    alerta.error("alerta", "Debe seleccionar al menos un tipo DTE (Exenta o Afecta)");
    return;
  }

  var dte=[];

  if (chk_boleta_exenta){
    var bExenta='1';
    dte.push(bExenta);
  }
  if (chk_boleta_afecta) {
    var bAfecta = '3';
     dte.push(bAfecta);
  }
  
  if (chk_factura_exenta) {
    var fExenta = '2';
     dte.push(fExenta);
  }

  if (chk_factura_afecta) {
    var fAfecta = '4';
     dte.push(fAfecta);
  }

  if (id_socio != "" || mes_año != "" || id_sector != "") {
    var datosBusqueda = [id_socio, mes_año, id_sector];

    $("#grid_boletas").dataTable().fnReloadAjax(base_url + "/Pagos/Ctrl_boleta_electronica/datatable_boleta_electronica/" + datosBusqueda+"/"+dte);
  } else {
    alerta.aviso("alerta", "Debe seleccionar un items");
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

function emitir_dte () {
  var data = $("#grid_boletas").DataTable().rows('.selected').data();
  var arr_boletas = [];
  $(data).each(function (i, fila) {
    if (fila.folio_bolect == 0) {
      arr_boletas.push(fila.id_metros);
    }
  });

  if (arr_boletas.length > 0) {
    $(".div_sample").JQLoader({
      theme: "standard",
      mask: true,
      background: "#fff",
      color: "#fff"
    });

    setTimeout(function () {
      $.ajax({
        url: base_url + "/Pagos/Ctrl_boleta_electronica/emitir_dte_new",
        type: "POST",
        async: false,
        data: {arr_boletas: arr_boletas, comments: $("#comments").val(), type: $("#type").val()},
        success: function (respuesta) {
          const OK = 1;
          if (respuesta == OK) {
            buscar_boletas();
            alerta.ok("alerta", "Boletas agregada a la cola con éxito");
            $('#dlg_emitir_boletas').modal('hide');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Errores',
              html: respuesta,
              footer: 'Procedimiento terminado con errores'
            });
          }
          $(".div_sample").JQLoader({
            theme: "standard",
            mask: true,
            background: "#fff",
            color: "#fff",
            action: "close"
          });
        },
        error: function (error) {
          $(".div_sample").JQLoader({
            theme: "standard",
            mask: true,
            background: "#fff",
            color: "#fff",
            action: "close"
          });
          respuesta = JSON.parse(error["responseText"]);
          alerta.error("alerta", respuesta.message);
        }
      });
    }, 500);
  } else {
    alerta.error("alerta", "Seleccione al menos una boleta, sin folio SII")
  }
}

function enviar_punto_blue(){
    var data = $("#grid_boletas").DataTable().rows('.selected').data();
    var arr_boletas = [];

    $(data).each(function (i, fila) {
      if (fila.punto_blue == 'NO') {
        arr_boletas.push(fila.id_metros);
      }
    });

    if (arr_boletas.length > 0) {

      $.ajax({
        url: base_url + "/Pagos/Ctrl_boleta_electronica/enviar_punto_blue/" + arr_boletas,
        type: "POST",
        success: function (respuesta) {
          buscar_boletas();
          $(".div_sample").JQLoader({
            theme: "standard",
            mask: true,
            background: "#fff",
            color: "#fff",
            action: "close"
          });
        },
        error: function (error) {
          $(".div_sample").JQLoader({
            theme: "standard",
            mask: true,
            background: "#fff",
            color: "#fff",
            action: "close"
          });
          alerta.error("alerta", "Ha ocurrido un error");
        }
      });

    } else {
      alerta.error("alerta", "Seleccionar al menos una deuda que no sea visible en punto blue")
      $(".div_sample").JQLoader({
        theme: "standard",
        mask: true,
        background: "#fff",
        color: "#fff",
        action: "close"
      });
    }
}
function enviarMail(){
 
  var data = $("#grid_boletas").DataTable().rows('.selected').data();
  var arr_boletas = [];

  $(data).each(function (i, fila) {
    if (fila.folio_bolect > 0) {
      arr_boletas.push(fila.id_metros);
    }
  });

  if (arr_boletas.length > 0) {
    
      $.ajax({
      url: base_url + "/Pagos/Ctrl_boleta_electronica/envia_mail/"+arr_boletas,
      type: "POST",
      success: function (respuesta) {
        buscar_boletas();
        $(".div_sample").JQLoader({
          theme: "standard",
          mask: true,
          background: "#fff",
          color: "#fff",
          action: "close"
        });
      },
        error: function (error) {
          $(".div_sample").JQLoader({
            theme: "standard",
            mask: true,
            background: "#fff",
            color: "#fff",
            action: "close"
          });
          alerta.error("alerta", "Ha ocurrido un error");
        }
    });

  } else {
    alerta.error("alerta", "Seleccione al menos una boleta, con folio SII")
    $(".div_sample").JQLoader({
      theme: "standard",
      mask: true,
      background: "#fff",
      color: "#fff",
      action: "close"
    });
  }


}

function imprimir_dte () {
  var data = $("#grid_boletas").DataTable().rows('.selected').data();
  var arr_boletas = [];
  $(data).each(function (i, fila) {
    if (fila.folio_bolect > 0) {
      arr_boletas.push(fila.id_metros);
    }
  });

  if (arr_boletas.length > 0) {
    var url = base_url + "/Pagos/Ctrl_boleta_electronica/imprimir_dte_new/" + arr_boletas;
    window.open(url, "DTE", "width=1200,height=800,location=0,scrollbars=yes");
  } else {
    alerta.error("alerta", "Seleccione al menos una boleta, con folio SII")
  }
}

function imprimir_aviso_cobranza () {
  var data = $("#grid_boletas").DataTable().rows('.selected').data();
  var arr_boletas = [];
  $(data).each(function (i, fila) {
    arr_boletas.push(fila.id_metros);
  });

  if (arr_boletas.length > 0) {
    var url = base_url + "/Pagos/Ctrl_boleta_electronica/imprimir_aviso_cobranza/" + arr_boletas;
    window.open(url);
  } else {
    alerta.error("alerta", "Seleccione al menos una boleta")
  }
}

$(document).ready(function () {
  $("#txt_id_socio").prop("readonly", true);
  $("#txt_rut_socio").prop("readonly", true);
  $("#txt_rol").prop("readonly", true);
  $("#txt_nombre_socio").prop("readonly", true);

  if(txt_bafecta == 'true'){
      $("#chk_boleta_afecta").prop("disabled", false);
  }else{
      $("#chk_boleta_afecta").prop("disabled", true);
  }

  if (txt_fexenta == 'true') {
    $("#chk_factura_exenta").prop("disabled", false);
  } else {
    $("#chk_factura_exenta").prop("disabled", true);
  }

  if (txt_fafecta == 'true') {
    $("#chk_factura_afecta").prop("disabled", false);
  } else {
    $("#chk_factura_afecta").prop("disabled", true);
  }
 

  llenar_cmb_sector();

  $("#btn_buscar_socio").on("click", function () {
    $("#divContenedorBuscarSocio").load(
     base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_boleta_electronica"
    );

    $('#dlg_buscar_socio').modal('show');
  });

  $("#dt_mes_año").datetimepicker({
    format: "MM-YYYY",
    useCurrent: false,
    locale: moment.locale("es")
  });

  $("#btn_buscar").on("click", function () {
    buscar_boletas();
    $("#datosBuscarSocios").collapse("hide");
  });

  $("#btn_limpiar").on("click", function () {
    $("#form_boleta_electronica")[0].reset();
  });

  $("#btn_emitir").on("click", function () {
    $.ajax({
      url: base_url + "/Pagos/Ctrl_boleta_electronica/observaciones",
      type: "POST",
      async: false,
      success: function (respuesta) {
        $("#comments").val(respuesta);
      }
    });
    $('#dlg_emitir_boletas').modal('show');
  });

  $("#btn_enviar_mail").on("click", function () {

    $(".div_sample").JQLoader({
      theme: "standard",
      mask: true,
      background: "#fff",
      color: "#fff"
    });

    enviarMail();    
  });


  $("#btn_punto_blue").on("click", function () {

    $(".div_sample").JQLoader({
      theme: "standard",
      mask: true,
      background: "#fff",
      color: "#fff"
    });

    enviar_punto_blue();
  });

  $("#btn_imprimir").on("click", function () {
    imprimir_dte();
  });

  $("#btn_aviso_cobranza").on("click", function () {
    imprimir_aviso_cobranza();
  });

  $("#emitir_boletas").on('submit', function (ev) {
    ev.preventDefault();
    emitir_dte();
  });

  

  var grid_boletas = $("#grid_boletas").DataTable({
    responsive: true,
    paging: true,
    destroy: true,
    select: {
      style: "multi"
    },
    orderClasses: true,
    columns: [
      { "data": "punto_blue",
          "render": function (data, type, row) {
            if (data != 'SI') {
              return 'No Visible';
            } else {
              return 'Visible';
            }
          }
       },
      {        
        "data": "estado_mail",
        "render": function ( data, type, row ) {
          if (data!='OK'){
            return "<img src='"+base_url+"/email.png' width='25px' height='25px' title='Boleta No enviada'/><a></a>";
          }else{
             return "<img src='"+base_url+"/email_ok.png' width='20' height='20px' title='Boleta enviada' /><a style='font-size: xx-small;color:white'>Ok</a>";
          }
        }
      },
      {"data": "id_metros"},
      {
        "data": "folio_bolect",
        "render": function(data, type, row) {
          if (data != 0) {
            return "<a href='#' title='Anular boleta' onclick='opciones_anular("+ data + "," + row.id_metros + ","+row.id_tipo_documento+")'>"+ data + "</a>";
          } else {
            return data;
          }
        }
      },
      {
        "data": "url_boleta",
        "render": function ( data, type, row ) {
          if (data!='--'){
            return "<a href='"+data+"'  target='_blank'>Ver B.</a>";
          }else{
            return 'No Disp.';
          }

        }
      },
      
      {"data": "ruta"},
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
      { "data": "id_tipo_documento" ,visible:false}
    ],
    order: [[2, "asc"]],
    "columnDefs": [
      {
        "targets": [5, 6, 10, 11, 12, 13, 14, 15, 17, 18, 20, 21, 22, 23], "visible": false, "searchable": false}
    ],
    dom: 'Bfrtip',
    buttons: [
      'selectAll',
      'selectNone',
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
        "rows": "<br/>%d Boletas Seleccionadas"
      },
      "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Sig.",
        "previous": "Ant."
      }
    }
  });
});