var base_url = $("#txt_base_url").val();

$(document).ready(function () {

  // alert(base_url);

  $("#dt_mes_consumo").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        $("#dt_mes_consumo").blur();
    });


    $("#dt_fecha_vencimiento").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    });

    
  // var tipo = $("#tipo").val();
// alert(tipo);

  $("#form").on('submit', function (e) {

    // $(".div_sample").JQLoader({
    //   theme: "standard",
    //   mask: true,
    //   background: "#fff",
    //   color: "#fff"
    // });

    // if(tipo=='SO'){
    //   var controlador='importar_socios';
    // }
    // if(tipo=='ME'){
    //   var controlador='importar_medidor';
    // }
    // if(tipo=='ARR'){
    //   var controlador='importar_arranque';
    // }
    // if(tipo=='DEU'){
    //   var controlador='importar_deuda';
    // }

    e.preventDefault();
    let form = $("#form");
    let data = new FormData(form[0]);
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: base_url + "/Consumo/Ctrl_lecturas_sector/importar_planilla",
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000,
      success: function ($data) {
        if($data!=0){
          alert($data);
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