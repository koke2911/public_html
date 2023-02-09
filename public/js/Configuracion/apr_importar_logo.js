var base_url = $("#txt_base_url").val();

$(document).ready(function() {
	var id_apr = $("#txt_id_apr").val();

    $("#archivos").fileinput({
		theme: "fas",
		uploadUrl: base_url + "/Configuracion/Ctrl_importar_logo/importar_logo/" + id_apr,
		allowedFileExtensions: ['png'],
		language: "es",
	    uploadAsync: false,
	    minFileCount: 1,
	    maxFileCount: 1,
		showUpload: false,
		showRemove: false
	}).on('filebatchselected', function(event) {
		$("#archivos").fileinput("upload");
	}).on('filebatchuploadsuccess', function(event, data) {
	  	alerta.ok("alerta", "Logo subido con éxito");	
	});
});