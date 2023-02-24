var base_url = $("#txt_base_url").val();
var datatable_enabled = true;





$(document).ready(function () {

  
  $("#btn_subir_socios").on("click", function () {
    $("#divContenedorImportar2").load(
     base_url + "/Configuracion/Ctrl_apr/v_importar_datos/SO"
    );

    $('#dlg_importar_certificado').modal('show');
  });

   $("#btn_subir_medidores").on("click", function () {
    $("#divContenedorImportar2").load(
     base_url + "/Configuracion/Ctrl_apr/v_importar_datos/ME"
    );

    $('#dlg_importar_certificado').modal('show');
  });


   $("#btn_subir_arranque").on("click", function () {
    $("#divContenedorImportar2").load(
     base_url + "/Configuracion/Ctrl_apr/v_importar_datos/ARR"
    );

    $('#dlg_importar_certificado').modal('show');
  });


   $("#btn_subir_deuda").on("click", function () {
    $("#divContenedorImportar2").load(
     base_url + "/Configuracion/Ctrl_apr/v_importar_datos/DEU"
    );

    $('#dlg_importar_certificado').modal('show');
  });



  



});