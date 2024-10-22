var base_url = $("#txt_base_url").val();
var id_apr = $("#txt_id_apr").val();

$(document).ready(function () {

  $("#btn_importar_folios").on("click", function () {

    if ($("#folios")[0].files.length === 0) {
      alert("alerta", "Debe seleccionar un archivo de folios");
      return false;
    }
    
      
       let form = $("#formFolios");
       let data = new FormData(form[0]);
     
      $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: base_url + "/Configuracion/Ctrl_importar_certificado/importar_folios/" + id_apr,
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        success: function (data) {
          if(data==0){
            alerta.ok("alerta", "Folios CAF subido con éxito");
            alert("Folios CAF subido con éxito");
            $("#folios").val('');
            $("#grid_folios").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_importar_certificado/mostrar_certificado/" + id_apr);
          }else{
            alerta.error("alerta", "Error al subir  Folios CAF");

          }
        }
      });
    });

  $("#btn_importar_certificado").on("click", function () {

    if ($("#certificate")[0].files.length === 0) {
      alert("alerta", "Debe seleccionar un archivo de certificado");
      return false;
    }
    
    let form = $("#form");
    let data = new FormData(form[0]);

    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: base_url + "/Configuracion/Ctrl_importar_certificado/importar_certificado/" + id_apr,
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000,
      success: function (data) {
        if (data == 0) {
          alert("Certificado subido con exito");
          $("#certificate").val('');
          $("#password").val(''); 
          // $("#grid_folios").dataTable().fnReloadAjax(base_url + "/Configuracion/Ctrl_importar_certificado/mostrar_certificado/" + id_apr);
        } else {
          alerta.error("alerta", "Error al subir el certificado");
          alert(data);

        }
      }
    });
  });


  var grid_folios = $("#grid_folios").DataTable({
    responsive: true,
    paging: true,
    destroy: true,
    select: {
      toggleable: false
    },
    ajax: base_url + "/Configuracion/Ctrl_importar_certificado/mostrar_certificado/" + id_apr,
    order: [[ 0, "desc" ]],
    columns: [
      { "data": "id" },
      { "data": "archivo",
        "render": function (data, type, row) {
          if(row.estado == 1){
            return "<span style='color:green'>"+data+"</span>";
          }else{
             return "<span style='color:red'>"+data+"</span>";
          }
        }
      },
      { "data": "desde",
        "render": function (data, type, row) {
          if(row.estado == 1){
            return "<span style='color:green'>"+data+"</span>";
          }else{
             return "<span style='color:red'>"+data+"</span>";
          }
        }
      },
      { "data": "hasta",
        "render": function (data, type, row) {
          if(row.estado == 1){
            return "<span style='color:green'>"+data+"</span>";
          }else{
             return "<span style='color:red'>"+data+"</span>";
          }
        }
      },
      { "data": "total",
        "render": function (data, type, row) {
          if(row.estado == 1){
            return "<span style='color:green'>"+data+"</span>";
          }else{
             return "<span style='color:red'>"+data+"</span>";
          }
        }
      },
      { "data": "fecha",
        "render": function (data, type, row) {
          if(row.estado == 1){
            return "<span style='color:green'>"+data+"</span>";
          }else{
             return "<span style='color:red'>"+data+"</span>";
          }
        }
      },
      { "data": "disponibles",
        "render": function (data, type, row) {
          if(row.estado == 1){
            return "<span style='color:green'>"+data+"</span>";
          }else{
             return "--";
          }
        }
      },
      { "data": "estado",
        "render": function (data, type, row) {
          if(row.estado == 1){
            return "<span style='color:green'>Activo</span>";
          }else{
            return "<span style='color:red'>Inactivo</span>";
          }
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

  

  // let getCertificate = function () {
  //   $.getJSON(base_url + "/Configuracion/Ctrl_importar_certificado/mostrar_certificado/" + id_apr, function (data) {
  //     if (data.id) {
  //       $("#grid_certificate > tbody").html("<tr>\n" +
  //        "                        <td>" + formatear(data.rut) + "</td>\n" +
  //        "                        <td>" + data.full_name + "</td>\n" +
  //        "                        <td>" + data.issuer + "</td>\n" +
  //        "                        <td>" + format_date(data.start) + "</td>\n" +
  //        "                        <td>" + format_date(data.end) + "</td></tr>");
  //       $("#old_certificate").css('display', 'inherit');
  //     }
  //   });
  // };

  // $("#form").on('submit', function (e) {
  //   e.preventDefault();
  //   let form = $("#form");
  //   let data = new FormData(form[0]);
  //   $.ajax({
  //     type: "POST",
  //     enctype: 'multipart/form-data',
  //     url: base_url + "/Configuracion/Ctrl_importar_certificado/importar_certificado/" + id_apr,
  //     data: data,
  //     processData: false,
  //     contentType: false,
  //     cache: false,
  //     timeout: 600000,
  //     success: function () {
  //       alerta.ok("alerta", "Certificado subido con éxito");
  //       getCertificate();
  //     }
  //   });
  // });

  // let formatear = function (rut) {
  //   var tmp = quitar_formato(rut);
  //   var rut = tmp.substring(0, tmp.length - 1), f = "";
  //   while (rut.length > 3) {
  //     f = '.' + rut.substr(rut.length - 3) + f;
  //     rut = rut.substring(0, rut.length - 3);
  //   }
  //   return ($.trim(rut) == '') ? '' : rut + f + "-" + tmp.charAt(tmp.length - 1);
  // };

  // let quitar_formato = function (rut) {
  //   rut = rut.split('-').join('').split('.').join('');
  //   return rut;
  // };

  // let format_date = function (date) {
  //   let object = new Date(date.split('T'));
  //   return object.getDate() + '/' + object.getMonth() + '/' + object.getFullYear();
  // };

  // getCertificate();
});