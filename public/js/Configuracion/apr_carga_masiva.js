var base_url = $("#txt_base_url").val();

$(document).ready(function () {
  var tipo = $("#tipo").val();
// alert(tipo);

  $("#form").on('submit', function (e) {

    $(".div_sample").JQLoader({
      theme: "standard",
      mask: true,
      background: "#fff",
      color: "#fff"
    });

    if(tipo=='SO'){
      var controlador='importar_socios';
    }
    if(tipo=='ME'){
      var controlador='importar_medidor';
    }
    if(tipo=='ARR'){
      var controlador='importar_arranque';
    }
    if(tipo=='DEU'){
      var controlador='importar_deuda';
    }

    e.preventDefault();
    let form = $("#form");
    let data = new FormData(form[0]);
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: base_url + "/Configuracion/Ctrl_importar/"+controlador,
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000,
      success: function ($data) {
        if($data!=0){
          alerta.error("alerta", $data);
        }else{
          alerta.ok("alerta", "Archivo cargado con éxito");
        }

        $(".div_sample").JQLoader({
            theme: "standard",
            mask: true,
            background: "#fff",
            color: "#fff",
            action: "close"
          });

        $('#dlg_importar_certificado').modal('hide');
      }
    });
  });

  
});