function imprimir_historico() {
	var id_socio = $("#txt_id_socio").val();
	var url = base_url + "/Informes/Ctrl_informe_historico_socio/imprimir_historico/" + id_socio;
    window.open(url, "Histórico Socio", "width=1200,height=800,location=0,scrollbars=yes");
}

$(document).ready(function() {
	$("#txt_id_socio").prop("disabled", true);
	$("#txt_rut_socio").prop("disabled", true);
	$("#txt_rol").prop("disabled", true);
	$("#txt_nombre_socio").prop("disabled", true);

	$("#btn_buscar_socio").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_arranques/v_buscar_socio/Ctrl_informe_historico_socio"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

    $("#btn_imprimir").on("click", function() {
    	if ($("#txt_id_socio").val() != "") {
    		imprimir_historico();
    	}
    });
});