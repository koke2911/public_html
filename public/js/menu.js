var base_url = $("#txt_base_url").val();

// Variable global para guardar los datos originales recibidos de la BD
window.menuDataOriginal = [];

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

/**
 * Función que construye el HTML del menú dinámico
 * Mantiene intacta la jerarquía de categorías, grupos y subgrupos.
 */
/** 
 * Función que construye el HTML del menú dinámico 
 * Agrupa de forma correcta Categorías, Grupos y Subgrupos.
 */
/** 
 * Función que construye el HTML del menú dinámico 
 * Mantiene el ORDEN EXACTO del JSON de la BD.
 */
function renderizarMenu(data) {
    if (!data || data.length === 0) {
        $("#menu").html("");
        return;
    }

    var menu = "";
    var categoriaActual = null;
    var grupoActual = null;
    var subgrupoActual = null;

    for (var i = 0; i < data.length; i++) {
        var item = data[i];

        // 1. CAMBIO DE CATEGORÍA
        if (categoriaActual !== item.categoria) {
            // Cerrar subgrupo si estaba abierto
            if (subgrupoActual !== null) {
                menu += "</nav></div>";
                subgrupoActual = null;
            }
            // Cerrar grupo si estaba abierto
            if (grupoActual !== null) {
                menu += "</nav></div>";
                grupoActual = null;
            }

            categoriaActual = item.categoria;
            menu += '<div class="sb-sidenav-menu-heading categoria-menu">' + categoriaActual + '</div>';
        }

        // 2. CAMBIO DE GRUPO
        if (grupoActual !== item.id_grupo) {
            // Cerrar subgrupo si quedó abierto en el grupo anterior
            if (subgrupoActual !== null) {
                menu += "</nav></div>";
                subgrupoActual = null;
            }

            // Cerrar grupo anterior
            if (grupoActual !== null) {
                menu += "</nav></div>";
            }

            grupoActual = item.id_grupo;

            menu +=
                '<a class="nav-link collapsed grupo-item" href="#" data-toggle="collapse" ' +
                'data-target="#' + item.collapse + '" aria-expanded="false" aria-controls="' + item.collapse + '">' +
                '<div class="sb-nav-link-icon"><i class="' + item.icono_grupo + '"></i></div>' +
                '<span class="nombre-grupo">' + item.grupo + '</span>' +
                '<div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>' +
                '</a>' +
                '<div class="collapse" id="' + item.collapse + '" aria-labelledby="headingOne" data-parent="#sidenavAccordion">' +
                '<nav class="sb-sidenav-menu-nested nav accordion" id="' + item.collapse + 'Accordion">';
        }

        // 3. CAMBIO / EVALUACIÓN DE SUBGRUPO
        var tieneSubgrupo = (item.id_subgrupo !== null && item.id_subgrupo !== undefined && item.id_subgrupo !== "");

        if (tieneSubgrupo) {
            if (subgrupoActual !== item.id_subgrupo) {
                // Si había otro subgrupo abierto anteriormente, lo cerramos
                if (subgrupoActual !== null) {
                    menu += "</nav></div>";
                }

                subgrupoActual = item.id_subgrupo;

                menu +=
                    '<a class="nav-link collapsed subgrupo-item" href="#" data-toggle="collapse" ' +
                    'data-target="#' + item.collapse_subgrupo + '" aria-expanded="false" aria-controls="' + item.collapse_subgrupo + '">' +
                    '<div class="sb-nav-link-icon"><i class="' + item.icono_subgrupo + '"></i></div>' +
                    '<span class="nombre-subgrupo">' + item.subgrupo + '</span>' +
                    '<div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>' +
                    '</a>' +
                    '<div class="collapse" id="' + item.collapse_subgrupo + '" aria-labelledby="headingOne" data-parent="#' + item.collapse + 'Accordion">' +
                    '<nav class="sb-sidenav-menu-nested nav">';
            }
        } else {
            // Si el ítem actual no tiene subgrupo pero veníamos de uno, cerramos el subgrupo anterior
            if (subgrupoActual !== null) {
                menu += "</nav></div>";
                subgrupoActual = null;
            }
        }

        // 4. PERMISO FINAL (ENLACE)
        menu +=
            '<a class="nav-link item-permiso" href="#" id="' + item.div_id + '" onclick="cargar_page(\'' + String(item.ruta) + '\')">' +
            '<div class="sb-nav-link-icon"><i class="">•</i></div>' +
            '<span class="nombre-permiso">' + item.permiso + '</span>' +
            '</a>';
    }

    // 5. CERRAR CONTENEDORES ABIERTOS AL FINALIZAR
    if (subgrupoActual !== null) {
        menu += "</nav></div>";
    }

    if (grupoActual !== null) {
        menu += "</nav></div>";
    }

    $("#menu").html(menu);
}
/**
 * Filtra el menú dinámico preservando el formato acordeón
 */
function filtrarMenuPorRol(rol) {
    if (!window.menuDataOriginal || window.menuDataOriginal.length === 0) return;

    if (rol === 'administrador') {
        renderizarMenu(window.menuDataOriginal);
        return;
    }

    const permisosRutas = {
        operador: [
            "/ctrl_menu/socios", "/ctrl_menu/arranques", "/ctrl_menu/sectores", "/ctrl_menu/medidores", "/ctrl_menu/cambio_medidor", "/ctrl_menu/desinfeccion",
            "/ctrl_menu/metros", "/ctrl_menu/lectura_sector", "/ctrl_menu/lectura_planilla", "/ctrl_menu/lecturas_sector_mobile",
            "/ctrl_menu/llenado_agua",
            "/ctrl_menu/caja", "/ctrl_menu/caja_expres", "/ctrl_menu/historial_pagos",
            "/ctrl_menu/productos", "/ctrl_menu/estados_producto", "/ctrl_menu/ubicaciones_producto"
        ],
        tesorero: [
            "/ctrl_menu/socios", "/ctrl_menu/subsidios", "/ctrl_menu/convenios", "/ctrl_menu/repactar",
            "/ctrl_menu/caja", "/ctrl_menu/caja_expres", "/ctrl_menu/historial_pagos", "/ctrl_menu/boleta_electronica", "/ctrl_menu/abonar", "/ctrl_menu/imprimir_facturas_muni",
            "/ctrl_menu/finanzas_ingresos", "/ctrl_menu/finanzas_egresos", "/ctrl_menu/proveedores", "/ctrl_menu/cuentas", "/ctrl_menu/compras", "/ctrl_menu/historial_egresos", "/ctrl_menu/registro_ventas", "/ctrl_menu/multas", "/ctrl_menu/banco",
            "/ctrl_menu/informe_pagos_diarios", "/ctrl_menu/informe_arqueo", "/ctrl_menu/informe_balance", "/ctrl_menu/informe_afecto_corte", "/ctrl_menu/informe_ingresos", "/ctrl_menu/informe_egresos_simples", "/ctrl_menu/informe_deudores", "/ctrl_menu/deudores_det", "/ctrl_menu/libro_caja",
            "/ctrl_menu/liquidaciones", "/ctrl_menu/vacaciones", "/ctrl_menu/inasistencias"
        ],
        directiva: [
            "/ctrl_menu/socios", "/ctrl_menu/arranques", "/ctrl_menu/convenios",
            "/ctrl_menu/llenado_agua", "/ctrl_menu/informe_llenado_agua", "/ctrl_menu/informe_consumo_agua", "/ctrl_menu/informe_cuadratura_agua",
            "/ctrl_menu/informe_socios", "/ctrl_menu/informe_balance", "/ctrl_menu/informe_afecto_corte", "/ctrl_menu/informe_deudores", "/ctrl_menu/deudores_det", "/ctrl_menu/trimestral", "/ctrl_menu/informe_diferencia_consumo"
        ]
    };

    var rutasPermitidas = permisosRutas[rol] || [];

    // Filtrar elementos basándonos en la propiedad 'ruta'
    var menuFiltrado = window.menuDataOriginal.filter(function (item) {
        var rutaNormalizada = String(item.ruta).toLowerCase().trim();
        return rutasPermitidas.some(function (r) {
            return r.toLowerCase().trim() === rutaNormalizada;
        });
    });

    renderizarMenu(menuFiltrado);
}

$(document).ready(function () {

    var cajaExpress = 0;

    $.ajax({
        type: "POST",
        dataType: "json",
        async: false,
        url: base_url + "/Ctrl_menu/permisos_usuario",
    }).done(function (data) {

        // Guardar copia original de los datos
        window.menuDataOriginal = data;

        // Renderizar menú completo por defecto
        renderizarMenu(data);

        // Verificar Caja Express
        for (var i = 0; i < data.length; i++) {
            if (String(data[i].ruta) === "/ctrl_menu/caja_expres") {
                cajaExpress = 1;
                break;
            }
        }
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
            $("#menu .item-permiso, #menu .grupo-item, #menu .subgrupo-item").show();
            $("#menu .collapse").removeClass("show").css("display", "");
            $("#menu a.nav-link").addClass("collapsed").attr("aria-expanded", "false");
            return;
        }

        $("#menu .item-permiso, #menu .grupo-item, #menu .subgrupo-item").hide();
        $("#menu .collapse").removeClass("show").css("display", "none");

        $("#menu .item-permiso").each(function () {
            var $permiso = $(this);
            var textoPermiso = limpiarTexto($permiso.text());

            if (textoPermiso.indexOf(valor) !== -1) {
                $permiso.show();

                $permiso.parents(".collapse").each(function () {
                    var $collapseParent = $(this);
                    $collapseParent.addClass("show").css("display", "block");

                    var $btnPadre = $collapseParent.prev("a.nav-link");
                    $btnPadre.show()
                        .removeClass("collapsed")
                        .attr("aria-expanded", "true");
                });
            }
        });

        $("#menu .grupo-item, #menu .subgrupo-item").each(function () {
            var $padre = $(this);
            var textoPadre = limpiarTexto($padre.text());

            if (textoPadre.indexOf(valor) !== -1) {
                $padre.show().removeClass("collapsed").attr("aria-expanded", "true");

                $padre.parents(".collapse").each(function () {
                    $(this).addClass("show").css("display", "block");
                    $(this).prev("a.nav-link").show().removeClass("collapsed").attr("aria-expanded", "true");
                });

                var targetId = $padre.attr("data-target");
                var $targetCollapse = $(targetId);

                if ($targetCollapse.length > 0) {
                    $targetCollapse.addClass("show").css("display", "block");
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