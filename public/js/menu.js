var base_url = $("#txt_base_url").val();

function cargar_page(ruta) {
	$("#content").load(base_url + ruta);
}

function actualizar_clave() {
    var clave_actual = $("#txt_clave_actual").val();
    var clave_nueva = $("#txt_clave_nueva").val();
    var repetir = $("#txt_repetir").val();

    $.ajax({
        url: base_url + "/Ctrl_menu/actualizar_clave",
        type: "POST",
        async: false,
        data: {
            clave_actual: clave_actual,
            clave_nueva: clave_nueva,
            repetir: repetir
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                Swal.fire({
                    icon: "success",
                    title: "Clave",
                    text: "La clave se actualizó con éxito",
                    footer: "Actualizar Clave"
                });
                $('#dlg_actualizar_clave').modal('hide');
                $("#form_actualizar_clave")[0].reset();
            } else {
                alerta.error("alerta", respuesta);
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

$(document).ready(function() {
	$.ajax({
	    type: "POST",
	    dataType: "json",
	    url: base_url + "/Ctrl_menu/permisos_usuario",
	}).done( function(data) {
    	var menu = "";
    	var id_grupo;
        var id_subgrupo;
        var cierre_subgrupo = 0;
        var subgrupo

        for (var i = 0; i < data.length; i++) {
            if (id_subgrupo != data[i].id_subgrupo && cierre_subgrupo == 1) {
                menu += "</nav></div>";
                cierre_subgrupo = 0;
            }

        	if (id_grupo != data[i].id_grupo) {
        		if (i > 0) {
	            	menu += "</nav></div>"
	        	}
	        	menu += '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#' + data[i].collapse + '" aria-expanded="false" aria-controls="' + data[i].collapse + '">\
                            <div class="sb-nav-link-icon"><i class="' + data[i].icono_grupo + '"></i></div>\
                            ' + data[i].grupo + '\
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>\
                        </a>\
                        <div class="collapse" id="' + data[i].collapse + '" aria-labelledby="headingOne" data-parent="#sidenavAccordion">\
                        	<nav class="sb-sidenav-menu-nested nav accordion" id="' + data[i].collapse + 'Accordion">';
            	id_grupo = data[i].id_grupo;
        	}

            if (data[i].id_subgrupo != null && cierre_subgrupo == 0) {
                menu += '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#' + data[i].collapse_subgrupo + '" aria-expanded="false" aria-controls="' + data[i].collapse_subgrupo + '">\
                            <div class="sb-nav-link-icon"><i class="' + data[i].icono_subgrupo + '"></i></div>\
                            ' + data[i].subgrupo + '\
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>\
                        </a>\
                        <div class="collapse" id="' + data[i].collapse_subgrupo + '" aria-labelledby="headingOne" data-parent="#' + data[i].collapse + 'Accordion">\
                            <nav class="sb-sidenav-menu-nested nav">';
                
                cierre_subgrupo = 1;
            }

        	menu += '<a class="nav-link" href="#" id="' + data[i].div_id + '" onclick="cargar_page(\'' + String(data[i].ruta) + '\')">\
                        <div class="sb-nav-link-icon"><i class="' + data[i].icono + '"></i></div> ' + data[i].permiso + '\
                    </a>';

            id_subgrupo = data[i].id_subgrupo;
        }

        $("#menu").html(menu);
	});

    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $("#form_actualizar_clave").validate({
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_clave_actual: {
                required: true,
                maxlength: 20,
                charspecial: true
            },
            txt_clave_nueva: {
                required: true,
                maxlength: 20,
                charspecial: true
            },
            txt_repetir: {
                required: true,
                maxlength: 20,
                charspecial: true,
                equalTo: "#txt_clave_nueva"
            }
        },
        messages: {
            txt_clave_actual: {
                required: "Obligatorio",
                maxlength: "Máximo 10 caracteres",
                charspecial: "Caracter no permitido"
            },
            txt_clave_nueva: {
                required: "Obligatorio",
                maxlength: "Máximo 10 caracteres",
                charspecial: "Caracter no permitido"
            },
            txt_repetir: {
                required: "Obligatorio",
                maxlength: "Máximo 10 caracteres",
                charspecial: "Caracter no permitido",
                equalTo: "Las claves tienen que coincidir"
            }
        }
    });

    $("#btn_actualizar_clave").on("click", function() {
        $("#form_actualizar_clave")[0].reset();
        $('#dlg_actualizar_clave').modal('show');
    });

    $("#btn_actualizar").on("click", function() {
        if ($("#form_actualizar_clave").valid()) {
            actualizar_clave();
        }
    });

    cargar_page("/ctrl_menu/dashboard");
});