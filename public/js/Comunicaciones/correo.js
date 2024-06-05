var base_url = $("#txt_base_url").val();

$(document).ready(function () {

    function enviarCorreo(){
        var asunto = $("#txt_asunto").val();
        var cuerpo = $("#txt_cuerpo").val();

        var data = $("#grid_socios").DataTable().rows('.selected').data();
        var arr_socios = [];

        $(data).each(function (i, fila) {           
            arr_socios.push(fila.id_socio);                
        });

        if (arr_socios.length > 0) {
            
            $.ajax({
                url: base_url + "/Comunicaciones/Ctrl_correo/envia_mail/" + arr_socios,
                type: "POST",
                data: {
                    asunto: asunto,
                    cuerpo: cuerpo
                },
                success: function (respuesta) {
                    $("#grid_socios").DataTable().rows().deselect();
                    $("#txt_asunto").val('');
                    $("#txt_cuerpo").val('');
                    if (respuesta==1){
                     alerta.ok("alerta", "Se ha enviado el correo correctamente");
                     $("#grid_enviados").dataTable().fnReloadAjax(base_url + "/Comunicaciones/Ctrl_correo/datatable_correos");
                    $(".div_sample").JQLoader({
                        theme: "standard",
                        mask: true,
                        background: "#fff",
                        color: "#fff",
                        action: "close"
                    });

                    }else{
                      alerta.error("alerta", "Ha ocurrido un error");
                    }                 
                },
                error: function (error) {
                    alerta.error("alerta", "Ha ocurrido un error");
                }
            });

        }else{
            alerta.error("alerta", 'Debe seleccionar al menos un socio');
        }

    }
  
    $("#btn_enviar").on('click', function (ev) {
        var asunto = $("#txt_asunto").val();
        var cuerpo = $("#txt_cuerpo").val();

        if(asunto.trim() === "" || cuerpo.trim() === "") {
            alerta.error("alerta", 'Debe completar el asunto y cuerpo del correo');
        } else {
            $(".div_sample").JQLoader({
                theme: "standard",
                mask: true,
                background: "#fff",
                color: "#fff"
            });
            enviarCorreo();
        }
    });
    

    var grid_socios = $("#grid_socios").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        select: {
            style: "multi"
        },
        ajax: base_url + "/Formularios/Ctrl_socios/datatable_socios_correo",
        orderClasses: true,
        columns: [
            { "data": "id_socio" },
            { "data": "rut" },
            { "data": "rol" },
            { "data": "nombres" },
            { "data": "ape_pat" },
            { "data": "ape_mat" },
            { "data": "nombre_completo" },
            { "data": "fecha_entrada" },
            { "data": "fecha_nacimiento" },
            { "data": "email" },
            { "data": "id_region" },
            { "data": "id_provincia" },
            { "data": "id_comuna" },
            { "data": "comuna" },
            { "data": "calle" },
            { "data": "numero" },
            { "data": "resto_direccion" },
            { "data": "usuario" },
            { "data": "fecha" },
            {
                "data": "id_socio",
                "render": function (data, type, row) {
                    return "<button type='button' class='traza_socio btn btn-warning' title='Traza socio'><i class='fas fa-shoe-prints'></i></button>";
                }
            },
            { "data": "ruta" },
            {
                "data": "id_socio",
                "render": function (data, type, row) {
                    return "<button type='button' class='btn_certificado btn btn-primary' title='Imprimir'><i class='fas fa-print'></i></button>"
                }
            },
        ],
        "columnDefs": [
            { "targets": [7,8,10,11,12,13,14,15,16,17,18,19,20,21], "visible": false, "searchable": false }
        ],
        dom: 'Bfrtip',
        buttons: [
            'selectAll',
            'selectNone',
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "select": {
                "rows": "<br/>%d Boletas Seleccionadas"
            },
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
        
    });


    var grid_enviados = $("#grid_enviados").DataTable({
        responsive: true,
        paging: true,
        destroy: true,        
        ajax: base_url + "/Comunicaciones/Ctrl_correo/datatable_correos",
        orderClasses: true,
        columns: [
            { "data": "id" },
            { "data": "fecha" },
            { "data": "asunto" },
            { "data": "cuerpo" },
            { "data": "id_usuario" },
            {
                "data": "id",
                "render": function (data, type, row) {
                    return "<button type='button' class='detalle_correo btn btn-warning' title='Detalle de envio'><i class='fas fa-shoe-prints'></i></button>";
                }
            }
        ],        
        dom: 'Bfrtip',
        buttons: [
            'selectAll',
            'selectNone',
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "select": {
                "rows": "<br/>%d Boletas Seleccionadas"
            },
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }

    });


    var grid_detalle = $("#grid_detalle").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        // ajax: base_url + "/Formularios/Ctrl_socios/datatable_socios_correo",
        orderClasses: true,
        columns: [
            { "data": "id" },
            { "data": "socio" }
        ],
        dom: 'Bfrtip',
        buttons: [
            'selectAll',
            'selectNone',
        ],
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "select": {
                "rows": "<br/>%d Boletas Seleccionadas"
            },
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }

    });

    $("#grid_enviados tbody").on("click", "button.detalle_correo", function () {       
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child')) {
            tr = $(tr).prev();
        }

        var data = grid_enviados.row(tr).data();
        var id_correo = data.id;

        $("#grid_detalle").dataTable().fnReloadAjax(base_url + "/Comunicaciones/Ctrl_correo/datatable_detalle/" + id_correo);

        $('#dlg_traza_socio').modal('show');
    });






});

