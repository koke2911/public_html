var base_url = $("#txt_base_url").val();

$(document).ready(function () {
  var id_apr = $("#txt_id_apr").val();

  let getCertificate = function () {
    $.getJSON(base_url + "/Configuracion/Ctrl_importar_certificado/mostrar_certificado/" + id_apr, function (data) {
      if (data.id) {
        $("#grid_certificate > tbody").html("<tr>\n" +
         "                        <td>" + formatear(data.rut) + "</td>\n" +
         "                        <td>" + data.full_name + "</td>\n" +
         "                        <td>" + data.issuer + "</td>\n" +
         "                        <td>" + format_date(data.start) + "</td>\n" +
         "                        <td>" + format_date(data.end) + "</td></tr>");
        $("#old_certificate").css('display', 'inherit');
      }
    });
  };

  $("#form").on('submit', function (e) {
    e.preventDefault();
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
      success: function () {
        alerta.ok("alerta", "Certificado subido con éxito");
        getCertificate();
      }
    });
  });

  let formatear = function (rut) {
    var tmp = quitar_formato(rut);
    var rut = tmp.substring(0, tmp.length - 1), f = "";
    while (rut.length > 3) {
      f = '.' + rut.substr(rut.length - 3) + f;
      rut = rut.substring(0, rut.length - 3);
    }
    return ($.trim(rut) == '') ? '' : rut + f + "-" + tmp.charAt(tmp.length - 1);
  };

  let quitar_formato = function (rut) {
    rut = rut.split('-').join('').split('.').join('');
    return rut;
  };

  let format_date = function (date) {
    let object = new Date(date.split('T'));
    return object.getDate() + '/' + object.getMonth() + '/' + object.getFullYear();
  };

  getCertificate();
});