$(document).ready(function() {
	$("#dt_fecha_vencimiento_im").prop("disabled", true);
	$("#archivos").prop("disabled", true);

	$("#dt_fecha_ingreso_im").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function(value) {
        if (this.value != "") {
        	$('#dt_fecha_vencimiento_im').data("DateTimePicker").minDate(this.value);
        	$('#dt_fecha_vencimiento_im').prop("disabled", false);
        } else {
        	$('#dt_fecha_vencimiento_im').prop("disabled", true);
        }
    });

    $("#dt_fecha_vencimiento_im").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function(value) {
        if (this.value != "" && $("#dt_fecha_ingreso_im").val() != "") {
        	var fecha_ingreso = $("#dt_fecha_ingreso_im").val();
			var fecha_vencimiento = $("#dt_fecha_vencimiento_im").val();

			$("#archivos").fileinput("refresh", {
				uploadExtraData: {
		            fecha_ingreso: fecha_ingreso,
		            fecha_vencimiento: fecha_vencimiento
		        }
			});

			$("#archivos").fileinput("unlock");
			$("#archivos").fileinput("clear");
			$("#archivos").fileinput("reset");
        }
    });

    $("#archivos").fileinput({
		theme: "fas",
		uploadUrl: base_url + "/Consumo/Ctrl_importar_planilla/importar_planilla",
		allowedFileExtensions: ['xlsx', 'xls'],
		language: "es",
	    uploadAsync: false,
	    minFileCount: 1,
	    maxFileCount: 1,
		showUpload: false,
		showRemove: false
	}).on('filebatchselected', function(event) {
		$("#archivos").fileinput("upload");
	}).on('filebatchuploadsuccess', function(event, data) {
	  	$("#dt_fecha_ingreso_im").prop("disabled", true);
	  	$("#dt_fecha_vencimiento_im").prop("disabled", true);
	  	$("#grid_metros").dataTable().fnReloadAjax(base_url + "/Consumo/Ctrl_metros/datatable_metros");
	});

	$("#archivos").fileinput("lock");
});