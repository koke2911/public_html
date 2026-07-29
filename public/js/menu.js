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
        success: function (respuesta) {
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
        error: function (error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

$(document).ready(function () {

    var cajaExpress;

    $.ajax({
        type: "POST",
        dataType: "json",
        async: false,
        url: base_url + "/Ctrl_menu/permisos_usuario",
    }).done(function (data) {
        var menu = "";
        var id_grupo;
        var id_subgrupo;
        var cierre_subgrupo = 0;

        for (var i = 0; i < data.length; i++) {
            if (id_subgrupo != data[i].id_subgrupo && cierre_subgrupo == 1) {
                menu += "</nav></div>";
                cierre_subgrupo = 0;
            }

            if (id_grupo != data[i].id_grupo) {
                if (i > 0) {
                    menu += "</nav></div>";
                }
                menu += '<a class="nav-link collapsed grupo-item" href="#" data-toggle="collapse" data-target="#' + data[i].collapse + '" aria-expanded="false" aria-controls="' + data[i].collapse + '">\
                            <div class="sb-nav-link-icon"><i class="' + data[i].icono_grupo + '"></i></div>\
                            <span class="nombre-grupo">' + data[i].grupo + '</span>\
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>\
                        </a>\
                        <div class="collapse" id="' + data[i].collapse + '" aria-labelledby="headingOne" data-parent="#sidenavAccordion">\
                            <nav class="sb-sidenav-menu-nested nav accordion" id="' + data[i].collapse + 'Accordion">';
                id_grupo = data[i].id_grupo;
            }

            if (data[i].id_subgrupo != null && cierre_subgrupo == 0) {
                menu += '<a class="nav-link collapsed subgrupo-item" href="#" data-toggle="collapse" data-target="#' + data[i].collapse_subgrupo + '" aria-expanded="false" aria-controls="' + data[i].collapse_subgrupo + '">\
                            <div class="sb-nav-link-icon"><i class="' + data[i].icono_subgrupo + '"></i></div>\
                            <span class="nombre-subgrupo">' + data[i].subgrupo + '</span>\
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>\
                        </a>\
                        <div class="collapse" id="' + data[i].collapse_subgrupo + '" aria-labelledby="headingOne" data-parent="#' + data[i].collapse + 'Accordion">\
                            <nav class="sb-sidenav-menu-nested nav">';

                cierre_subgrupo = 1;
            }

            menu += '<a class="nav-link item-permiso" href="#" id="' + data[i].div_id + '" onclick="cargar_page(\'' + String(data[i].ruta) + '\')">\
                        <div class="sb-nav-link-icon"><i class="' + data[i].icono + '"></i></div> <span class="nombre-permiso">' + data[i].permiso + '</span>\
                    </a>';

            if (String(data[i].ruta) === "/ctrl_menu/caja_expres") {
                cajaExpress = 1;
            }

            id_subgrupo = data[i].id_subgrupo;
        }

        $("#menu").html(menu);
    });

    // =========================================================
    // BUSCADOR CON DESPLIEGUE MÚLTIPLE EN TODOS LOS NIVELES
    // =========================================================
    $(document).on("keyup", "#txt_buscar_menu", function () {

        function limpiarTexto(texto) {
            return texto
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .trim();
        }

        var valor = limpiarTexto($(this).val());

        if (valor === "") {
            // Restablecer el menú completo a su estado original (oculto y cerrado)
            $("#menu .item-permiso, #menu .grupo-item, #menu .subgrupo-item").show();
            $("#menu .collapse").removeClass("show").css("display", "");
            $("#menu a.nav-link").addClass("collapsed").attr("aria-expanded", "false");
            return;
        }

        // 1. Ocultar todos los ítems y cerrar todos los contenedores collapse
        $("#menu .item-permiso, #menu .grupo-item, #menu .subgrupo-item").hide();
        $("#menu .collapse").removeClass("show").css("display", "none");

        // 2. BUSCAR EN PERMISOS FINALES (2do / 3er Nivel)
        $("#menu .item-permiso").each(function () {
            var $permiso = $(this);
            var textoPermiso = limpiarTexto($permiso.text());

            if (textoPermiso.indexOf(valor) !== -1) {
                $permiso.show(); // Muestra la opción coincidente

                // Forzar apertura de TODOS sus ancestros desplegables (.collapse)
                $permiso.parents(".collapse").each(function () {
                    var $collapseParent = $(this);

                    // Aplicar visibilidad directa para anular el acordeón nativo de Bootstrap
                    $collapseParent.addClass("show").css("display", "block");

                    // Mostrar y actualizar el botón padre del submenú
                    var $btnPadre = $collapseParent.prev("a.nav-link");
                    $btnPadre.show()
                        .removeClass("collapsed")
                        .attr("aria-expanded", "true");
                });
            }
        });

        // 3. BUSCAR EN GRUPOS Y SUBGRUPOS (1er / 2do Nivel)
        $("#menu .grupo-item, #menu .subgrupo-item").each(function () {
            var $padre = $(this);
            var textoPadre = limpiarTexto($padre.text());

            if (textoPadre.indexOf(valor) !== -1) {
                $padre.show().removeClass("collapsed").attr("aria-expanded", "true");

                // 3a. Abrir hacia ARRIBA (si coincide un Subgrupo, abrir el Grupo Superior)
                $padre.parents(".collapse").each(function () {
                    $(this).addClass("show").css("display", "block");
                    $(this).prev("a.nav-link").show().removeClass("collapsed").attr("aria-expanded", "true");
                });

                // 3b. Abrir hacia ABAJO (mostrar todos sus ítems e hijos contenidos)
                var targetId = $padre.attr("data-target");
                var $targetCollapse = $(targetId);

                if ($targetCollapse.length > 0) {
                    // Desplegar el contenedor del grupo/subgrupo
                    $targetCollapse.addClass("show").css("display", "block");

                    // Mostrar todas las opciones y subniveles internos
                    $targetCollapse.find(".item-permiso, .subgrupo-item").show();
                    $targetCollapse.find(".collapse").addClass("show").css("display", "block");
                    $targetCollapse.find("a.nav-link").show().removeClass("collapsed").attr("aria-expanded", "true");
                }
            }
        });
    });

    // Validaciones de formulario...
    $.validator.addMethod("charspecial", function (value, element) {
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
        rules: {
            txt_clave_actual: { required: true, maxlength: 20, charspecial: true },
            txt_clave_nueva: { required: true, maxlength: 20, charspecial: true },
            txt_repetir: { required: true, maxlength: 20, charspecial: true, equalTo: "#txt_clave_nueva" }
        },
        messages: {
            txt_clave_actual: { required: "Obligatorio", maxlength: "Máximo 10 caracteres", charspecial: "Caracter no permitido" },
            txt_clave_nueva: { required: "Obligatorio", maxlength: "Máximo 10 caracteres", charspecial: "Caracter no permitido" },
            txt_repetir: { required: "Obligatorio", maxlength: "Máximo 10 caracteres", charspecial: "Caracter no permitido", equalTo: "Las claves tienen que coincidir" }
        }
    });

    $("#btn_actualizar_clave").on("click", function () {
        $("#form_actualizar_clave")[0].reset();
        $('#dlg_actualizar_clave').modal('show');
    });

    $("#btn_actualizar").on("click", function () {
        if ($("#form_actualizar_clave").valid()) {
            actualizar_clave();
        }
    });

    if (cajaExpress === 1) {
        cargar_page("/ctrl_menu/caja_expres");
        $("body").toggleClass("sb-sidenav-toggled");
    } else {
        cargar_page("/ctrl_menu/dashboard");
    }

});