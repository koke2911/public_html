var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

$(document).ready(function () {

  
  $("#btn_subir_planilla").on("click", function () {
    $("#divContenedorImportar2").load(
     base_url + "/Consumo/Ctrl_metros/v_importar_planilla"
    );

    $('#dlg_importar_certificado').modal('show');
  });

  $("#btn_exporta_planilla").on("click", function () {
    $("#divContenedorImportar2").load(
     window.open(base_url + "/Consumo/Ctrl_lecturas_sector/tomaLectura")
    );

    // $('#dlg_importar_certificado').modal('show');
  });
  
});