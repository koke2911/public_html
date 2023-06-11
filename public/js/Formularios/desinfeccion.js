var base_url = $("#txt_base_url").val();

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    $("#btn_modificar").prop("disabled", a);
    $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);

    $("#txt_id_socio1").prop("disabled", a);
    $("#txt_rut_socio1").prop("disabled", a);
    $("#txt_rol1").prop("disabled", a);
    $("#txt_nombre_socio1").prop("disabled", a);
    $("#btn_buscar_socio1").prop("disabled", a);

    $("#txt_id_socio2").prop("disabled", a);
    $("#txt_rut_socio2").prop("disabled", a);
    $("#txt_rol2").prop("disabled", a);
    $("#txt_nombre_socio2").prop("disabled", a);
    $("#btn_buscar_socio2").prop("disabled", a);


    // $("#txt_id_desinfeccion").prop("disabled",a);
    $("#txt_hora_ap").prop("disabled",a);
    $("#txt_cloro_ap").prop("disabled",a);

    $("#txt_frecuencia").prop("disabled",a);
    $("#txt_desp").prop("disabled",a);
    $("#txt_medidor_caudal").prop("disabled",a);
    $("#txt_electricidad").prop("disabled",a);
    $("#txt_horometro").prop("disabled",a);

    $("#dt_hora_socio2").prop("disabled",a);
    $("#txt_cloro_socio2").prop("disabled",a);
    $("#dt_fecha_dia").prop("disabled",a);

    $("#dt_hora_socio1").prop("disabled",a);
    $("#txt_cloro_socio1").prop("disabled",a);
    
    
}

function mostrar_datos(data) {
    $("#txt_id_desinfeccion").val(data["id"]);
    $("#dt_fecha_dia").val(data["dia"]);
    $("#txt_hora_ap").val(data["hora_ap"]);
    $("#txt_cloro_ap").val(data["cloro_ap"]);
    $("#txt_id_socio1").val(data["id_socio1"]);
    $("#dt_hora_socio1").val(data["hora_socio1"]);
    $("#txt_cloro_socio1").val(data["cloro_socio1"]);
    $("#txt_id_socio2").val(data["id_socio2"]);
    $("#dt_hora_socio2").val(data["hora_socio2"]);
    $("#txt_cloro_socio2").val(data["cloro_socio2"]);
    $("#txt_frecuencia").val(data["frecuencia"]);
    $("#txt_desp").val(data["desp"]);
    $("#txt_medidor_caudal").val(data["medidor_caudal"]);
    $("#txt_electricidad").val(data["electricidad"]);
    $("#txt_horometro").val(data["horometro"]);

    $("#txt_rut_socio1").val(data["rut_socio1"]);
    $("#txt_rol1").val(data["rol_socio1"]);
    $("#txt_nombre_socio1").val(data["nombre_socio1"]);
    $("#txt_rut_socio2").val(data["rut_socio2"]);
    $("#txt_rol2").val(data["rol_socio2"]);
    $("#txt_nombre_socio2").val(data["nombre_socio2"]);

    
    
    
    
    
}

function guardar_desinfeccion() {
    var id=$("#txt_id_desinfeccion").val();
    var dia=$("#dt_fecha_dia").val();
    var hora_ap=$("#txt_hora_ap").val();
    var cloro_ap=$("#txt_cloro_ap").val();
    var id_socio1=$("#txt_id_socio1").val();
    var hora_socio1=$("#dt_hora_socio1").val();
    var cloro_socio1=$("#txt_cloro_socio1").val();
    var id_socio2=$("#txt_id_socio2").val();
    var hora_socio2=$("#dt_hora_socio2").val();
    var cloro_socio2=$("#txt_cloro_socio2").val();
    var frecuencia=$("#txt_frecuencia").val();
    var desp=$("#txt_desp").val();
    var medidor_caudal=$("#txt_medidor_caudal").val();
    var electricidad=$("#txt_electricidad").val();
    var horometro=$("#txt_horometro").val();
    

    $.ajax({
        url: base_url + "/Formularios/Ctrl_desinfeccion/guardar_desinfeccion",
        type: "POST",
        async: false,
        data: {
          id: id,
          dia: dia,
          hora_ap:  hora_ap,
          cloro_ap: cloro_ap,
          id_socio1:    id_socio1,
          hora_socio1:  hora_socio1,
          cloro_socio1: cloro_socio1,
          id_socio2:    id_socio2,
          hora_socio2:  hora_socio2,
          cloro_socio2: cloro_socio2,
          frecuencia:   frecuencia,
          desp: desp,
          medidor_caudal:   medidor_caudal,
          electricidad: electricidad,
          horometro:    horometro
          
        },
        success: function(respuesta) {
            if(respuesta!=1){
                alerta.error("alerta", respuesta);
            }else{
                alerta.ok("alerta", 'Se ha Guardado exitosamente');
                des_habilitar(true, false);
                $("#datosDesinfeccion").collapse("hide");
                $("#form_desinfeccion")[0].reset();
                $("#grid_desinfecciones").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_desinfeccion/datatable_desinfecciones");
            }
        },
        error: function(error) {
            respuesta = JSON.parse(error["responseText"]);
            alerta.error("alerta", respuesta.message);
        }
    });
}

function eliminar_desinfecciones(id_desinfeccion) {

    // var id_desinfeccion=$("#txt_id_desinfeccion").val();    
    $.ajax({
        url: base_url + "/Formularios/Ctrl_desinfeccion/eliminar_registro",
        type: "POST",
        async: false,
        data: { 
            id: id_desinfeccion,
            estado: 0
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                alerta.ok("alerta", 'Se ha Eliminado exitosamente');
                $("#grid_desinfecciones").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_desinfeccion/datatable_desinfecciones");
                des_habilitar(true, false);
                $("#form_desinfeccion")[0].reset();
                           
            } else {
                alerta.error("alerta", respuesta);
            }
        }
    });
}

// function convertirMayusculas(texto) {
//     var text = texto.toUpperCase().trim();
//     return text;
// }



$(document).ready(function() {

    $("#txt_id_socio1").prop("readonly", true);
    $("#txt_rut_socio1").prop("readonly", true);
    $("#txt_rol1").prop("readonly", true);
    $("#txt_nombre_socio1").prop("readonly", true);

    $("#txt_id_socio2").prop("readonly", true);
    $("#txt_rut_socio2").prop("readonly", true);
    $("#txt_rol2").prop("readonly", true);
    $("#txt_nombre_socio2").prop("readonly", true);

    $("#txt_id_desinfeccion").prop("disabled",true);
    $("#txt_hora_ap").prop("disabled",true);
    $("#txt_cloro_ap").prop("disabled",true);

    $("#txt_frecuencia").prop("disabled",true);
    $("#txt_desp").prop("disabled",true);
    $("#txt_medidor_caudal").prop("disabled",true);
    $("#txt_electricidad").prop("disabled",true);
    $("#txt_horometro").prop("disabled",true);

    $("#dt_hora_socio2").prop("disabled",true);
    $("#txt_cloro_socio2").prop("disabled",true);
    $("#dt_fecha_dia").prop("disabled",true);

    $("#dt_hora_socio1").prop("disabled",true);
    $("#txt_cloro_socio1").prop("disabled",true);
    
    des_habilitar(true, false);

    $("#dt_fecha_dia").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_fecha_dia").blur();
    });


    $("#dt_mes_export").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#dt_mes_export").blur();
    });


    $("#txt_hora_ap").datetimepicker({
        format: "HH:mm",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#txt_hora_ap").blur();
    });

    $("#dt_hora_socio1").datetimepicker({
        format: "HH:mm",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#txt_hora_ap").blur();
    });

    $("#dt_hora_socio2").datetimepicker({
        format: "HH:mm",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function() {
        $("#txt_hora_ap").blur();
    });
    

    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_desinfeccion")[0].reset();

        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosDesinfeccion").collapse("show");
    });

    $("#btn_export").on("click", function() {
        // des_habilitar(false, true);
        var mes_consulta=$("#dt_mes_export").val();
        if(mes_consulta!=""){
            window.open(base_url+"/Informes/Ctrl_informe_pagos_diarios/reporte_desinfeccion/"+mes_consulta);
        }else{
            alerta.error("alerta", 'Debe Seleccionar un mes para exportar');
        }
        
    });

    $("#btn_modificar").on("click", function() {
        des_habilitar(false, true);
        $("#btn_modificar").prop("disabled", true);
        $("#btn_eliminar").prop("disabled", true);
        $("#datosDesinfeccion").collapse("show");
        $("#dt_fecha_dia").prop("disabled", true);
    });

    $("#btn_eliminar").on("click", function() {
        Swal.fire({
            title: "¿Eliminar el Registro?",
            text: "¿Está seguro de eliminar el Registro?",
            // input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                var id_desinfeccion = $("#txt_id_desinfeccion").val();
                eliminar_desinfecciones(id_desinfeccion);
            }
        });
    });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_desinfeccion").valid()) {
            guardar_desinfeccion();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_desinfeccion")[0].reset();
        des_habilitar(true, false);
       

        $("#datosDesinfeccion").collapse("hide");
    });

    

    $("#btn_buscar_socio1").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_desinfeccion/v_buscar_socio/Ctrl_desinfeccion"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

    $("#btn_buscar_socio2").on("click", function() {
        $("#divContenedorBuscarSocio").load(
            base_url + "/Formularios/Ctrl_desinfeccion/v_buscar_socio/Ctrl_desinfeccion2"
        ); 

        $('#dlg_buscar_socio').modal('show');
    });

    
    $("#form_desinfeccion").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_id_socio: {
                required: true
            },
            txt_id_socio1:{
                required:true
            }
            ,txt_id_socio2:{
                required:true
            }
            ,txt_hora_ap:{
                required:true
            }
            ,txt_cloro_ap:{
                required:true,
                number: true
            }
            ,txt_frecuencia:{
                required:true
            }
            ,txt_desp:{
                required:true
            }
            ,txt_medidor_caudal:{
                required:true
            }
            ,txt_electricidad:{
                required:true
            }
            ,txt_horometro:{
                required:true
            }
            ,dt_hora_socio2:{
                required:true
            }
            ,txt_cloro_socio2:{
                required:true,
                number: true
            },
            dt_fecha_dia:{
                required:true
            }
            ,dt_hora_socio1:{
                required:true
            }
            ,txt_cloro_socio1:{
                required:true,
                number: true
            }
            
        },
        messages: {
            
            txt_id_socio1:{
                required:"El valor es obligatorio"
            }
            ,txt_id_socio2:{
                required:"El valor es obligatorio"
            }
            ,txt_hora_ap:{
                required:"El valor es obligatorio"
            }
            ,txt_cloro_ap:{
                required:"El valor es obligatorio",
                number:"Valor no permitiro solo valores numericos y . para el decimal"
            }
            ,txt_frecuencia:{
                required:"El valor es obligatorio"
            }
            ,txt_desp:{
                required:"El valor es obligatorio"
            }
            ,txt_medidor_caudal:{
                required:"El valor es obligatorio"
            }
            ,txt_electricidad:{
                required:"El valor es obligatorio"
            }
            ,txt_horometro:{
                required:"El valor es obligatorio"
            }
            ,dt_hora_socio2:{
                required:"El valor es obligatorio"
            }
            ,txt_cloro_socio2:{
                required:"El valor es obligatorio",
                number:"Valor no permitiro solo valores numericos y . para el decimal"

            },
            dt_fecha_dia:{
                required:"El valor es obligatorio"
            }
            ,dt_hora_socio1:{
                required:"El valor es obligatorio"
            }
            ,txt_cloro_socio1:{
                required:"El valor es obligatorio",
                number:"Valor no permitiro solo valores numericos y . para el decimal"

            },
        }
    });

    var grid_desinfecciones = $("#grid_desinfecciones").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        pageLength:30,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_desinfeccion/datatable_desinfecciones",
        orderClasses: true,
        columns: [
            { "data": "id" },
            { "data": "id_apr" },
            { "data": "dia" },
            { "data": "hora_ap" },
            { "data": "cloro_ap" },
            { "data": "id_socio1" },
            { "data": "hora_socio1" },
            { "data": "cloro_socio1" },
            { "data": "id_socio2" },
            { "data": "hora_socio2" },
            { "data": "cloro_socio2" },
            { "data": "frecuencia" },
            { "data": "desp" },
            { "data": "medidor_caudal" },
            { "data": "electricidad" },
            { "data": "horometro" },
            { "data": "nombre_socio1" },
            { "data": "nombre_socio2" },
            { "data": "rut_socio1" },
            { "data": "rut_socio2" },
            { "data": "rol_socio1" },
            { "data": "rol_socio2" }
            
        ],
        "columnDefs": [
            { "targets": [1,5,6,7,8,9,10,11,12,13,14,15,18,19,20,21], "visible": false, "searchable": false }
        ],
        dom: 'Bfrtip',
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe de Desinfecciones"
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe de Desinfecciones",
                orientation: 'landscape',
                pageSize: 'TABLOID'
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe de Desinfecciones"
            },
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
                "rows": "<br/>%d Perfiles Seleccionados"
            },
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
	});

    $("#grid_desinfecciones tbody").on("click", "tr", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_desinfecciones.row(tr).data();
        mostrar_datos(data);
        des_habilitar(true, false);
        $("#btn_modificar").prop("disabled", false);
        $("#btn_eliminar").prop("disabled", false);
        $("#datosDesinfeccion").collapse("hide");
    });

    
});