var base_url = $("#txt_base_url").val();

$(document).ready(function () {
  $("#btn_emitir").on('click', function (ev) {
    ev.preventDefault();
    $('#dlg_buscar_socio').modal('show');
  });

  $("#btn_add").on('click', function (ev) {
    ev.preventDefault();
    var count = $("#details tr").length;
    $("#details > tbody").append('<tr><td><div class="form-group">\n' +
     '                <input name="quantity[]" id="quantity[]" type="number" class="form-control">\n' +
     '              </div></td>\n' +
     '                  <th><div class="form-group">\n' +
     '                <input name="name[]" id="name[]" class="form-control">\n' +
     '              </div></th>\n' +
     '                  <th><div class="form-group">\n' +
     '                <input name="price[]" id="price[]" class="form-control">\n' +
     '              </div></th>\n' +
     '                  <th><div class="form-group"><select name="tax[]" id="tax[]" required class="form-control">\n' +
     '                  <option value="1">Afecto</option>\n' +
     '                  <option value="0">Exento</option>\n' +
     '                  <option value="-1">No facturable</option>\n' +
     '                </select></div></th>\n' +
     '                  <td></td></tr>');
  });

  $("#form").on('submit', function (ev) {
    ev.preventDefault();
    $.ajax({
      url: base_url + "/Finanzas/Ctrl_registro_ventas/crear",
      type: "POST",
      async: false,
      data: $(this).serializeArray(),
      success: function (respuesta) {
        var json = $.parseJSON(respuesta);
        var url = base_url + "/Finanzas/Ctrl_registro_ventas/imprimir_dte/" + json.documents_type_id + '/' + json.number;
        window.open(url, "DTE", "width=1200,height=800,location=0,scrollbars=yes");
        $('#dlg_buscar_socio').modal('hide');
      },
      error: function (error) {
        respuesta = JSON.parse(error["responseText"]);
        alerta.error("alerta", respuesta.message);
      }
    });
  });
});