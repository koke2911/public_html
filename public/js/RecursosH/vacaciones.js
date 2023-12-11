var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar(a, b) {
    $("#btn_nuevo").prop("disabled", b);
    // $("#btn_modificar").prop("disabled", a);
    // $("#btn_eliminar").prop("disabled", a);
    $("#btn_aceptar").prop("disabled", a);
    $("#btn_cancelar").prop("disabled", a);
    // $("#btn_reciclar").prop("disabled", b);

    $("#txt_rut").prop("disabled", a);
    $("#txt_nombres").prop("disabled", true);
    $("#txt_ape_pat").prop("disabled", true);
    $("#txt_ape_mat").prop("disabled", true);
    
    $("#txt_prevision").prop("disabled",true);
    $("#txt_prevision_porcen").prop("disabled",true);
    $("#txt_afp").prop("disabled",true);
    $("#txt_afp_porcent").prop("disabled",true);

    $("#txt_sueldo_bruto").prop("disabled",true);
    $("#dt_contrato").prop("disabled",true);
    $("#txt_jornada").prop("disabled",true);
    $("#txt_vacaciones").prop("disabled",true);
    $("#txt_disponibles").prop("disabled",true);
}


function mostrar_datos_funcionarios(rut) {

                $.ajax({
                    url: base_url + "/RecursosH/Ctrl_liquidaciones/buscar_datos_funcionario/"+$("#txt_rut").val(),
                    type: "POST",
                    async: false,
                    dataType: "json",
                    success: function(data) {

                        if(data!=0){
                            console.warn(data.data[0].id_funcionario);
                            // var id_socio =data.id_socio;
                            // var abono =data.abono;
                            $("#txt_id_funcionario").val(data.data[0].id_funcionario);
                            $("#txt_rut").val(data.data[0].rut);
                            $("#txt_nombres").val(data.data[0].nombres);
                            $("#txt_ape_pat").val(data.data[0].ape_pat);
                            $("#txt_ape_mat").val(data.data[0].ape_mat);
                            $("#txt_prevision").val(data.data[0].prevision);
                            $("#txt_prevision_porcen").val(data.data[0].prev_porcentaje);
                            $("#txt_afp").val(data.data[0].afp);
                            $("#txt_afp_porcent").val(data.data[0].afp_porcentaje);
                            $("#txt_sueldo_bruto").val(data.data[0].sueldo_bruto);
                            $("#dt_contrato").val(data.data[0].fecha_contrato);
                            $("#txt_jornada").val(data.data[0].jornada);
                            $("#txt_vacaciones").val(data.data[0].vacaciones);
                            $("#txt_disponibles").val(data.data[0].vacaciones_disponibles);
                                                   
                        }
                    },
                    error: function(error) {
                        respuesta = JSON.parse(error["responseText"]);
                        alerta.error("alerta", respuesta.message);
                    }
                });
}

function guardar_vacaciones() {
    var id_funcionario =$("#txt_id_funcionario").val();
    var desde =$("#dt_desde").val();
    var hasta =$("#dt_hasta").val();
    var dias =$("#txt_dias").val();
    
    $.ajax({
        url: base_url + "/RecursosH/Ctrl_vacaciones/guardar_vacaciones",
        type: "POST",
        async: false,
        data: {
            id_funcionario: id_funcionario,
            desde: desde,
            hasta: hasta,
            dias: dias
            
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_vacaciones").dataTable().fnReloadAjax(base_url + "/RecursosH/Ctrl_liquidaciones/datatable_liquidaciones");
                $("#form_liquidaciones")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "vacaciones guardado con éxito");
                $("#datosVacaciones").collapse("hide");
                
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
    $("#txt_id_funcionario").prop("disabled", true);
    des_habilitar(true, false);
    
   function calcularDiasHabiles() {
      var fechaDesde = moment($("#dt_desde").val(), "DD-MM-YYYY");
      var fechaHasta = moment($("#dt_hasta").val(), "DD-MM-YYYY");
      
      if (!fechaDesde.isValid() || !fechaHasta.isValid() || fechaDesde.isAfter(fechaHasta)) {
        alert("Por favor, seleccione un rango de fechas válido.");
        return;
      }

      var diasHabiles = 0;
      var fechaActual = fechaDesde.clone();

      while (fechaActual.isSameOrBefore(fechaHasta)) {
        if (fechaActual.day() >= 1 && fechaActual.day() <= 6) {
          diasHabiles++;
        }
        fechaActual.add(1, 'days'); 
      }

      // if(diasHabiles <= $("#txt_vacaciones").val()){
        $("#txt_dias").val(diasHabiles);

      // }else{
      //       alerta.error("alerta", "Los dias solicitados no pueden superar al TOTAL de vacaciones disponibles");        
      // }

    }

    $("#dt_desde").datetimepicker({
      format: "DD-MM-YYYY",
      minDate: moment(),
      locale: moment.locale("es")
    }).on("dp.change", function (e) {
      $("#dt_hasta").data("DateTimePicker").minDate(e.date);
      $("#dt_desde").blur();
    });

    $("#dt_hasta").datetimepicker({
      format: "DD-MM-YYYY",
      useCurrent: false,
      locale: moment.locale("es")
    }).on("dp.change", function () {
      $("#dt_hasta").blur();
      calcularDiasHabiles();
    });





    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_liquidaciones")[0].reset();
        // buscaUf();

        $("#datosVacaciones").collapse("show");
    });
   
    $("#btn_aceptar").on("click", function() {
        var funcionario=$("#txt_id_funcionario").val();
        var disponible=$("#txt_disponibles").val();
        var solicitados=$("#txt_dias").val();

        if (funcionario!="") {
            if(solicitados<=disponible && solicitados!=""){
                guardar_vacaciones();
            }else{
              alerta.error("alerta","Lo solicitado no puede sobrepasar lo disponible");
            }
        }else{
            alerta.error("alerta","Debe indicar el funcionario");
        }
   });

    $("#btn_cancelar").on("click", function() {
        $("#form_liquidaciones")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosVacaciones").collapse("hide");
    });

    $("#btn_buscar").on("click", function() {
        var funcionario=$("#txt_rut").val();
        if(funcionario!=""){
            mostrar_datos_funcionarios();
        }else{
            alerta.error("alerta", "Debe ingresar un rut valido");
        }
    });

    $("#txt_nombres").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_ape_pat").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_ape_mat").on("blur", function() {
        $(this).val(convertirMayusculas($(this).val()));
    });

    $("#txt_prevision").on("blur",function(){
        $(this).val(convertirMayusculas($(this).val()));        
    });

    $("#txt_prevision_porcen").on("blur",function(){
        $(this).val(convertirMayusculas($(this).val()));        
    });

    $("#txt_afp").on("blur",function(){
        $(this).val(convertirMayusculas($(this).val()));        
    });

    $("#txt_afp_porcent").on("blur",function(){
        $(this).val(convertirMayusculas($(this).val()));        
    });


    $.validator.addMethod("charspecial", function(value, element) {
        return this.optional(element) || /^[^;\"'{}\[\]^<>=]+$/.test(value);
    });

    $.validator.addMethod("rut", function(value, element) {
        return this.optional(element) || /^[0-9-.Kk]*$/.test(value);
    });

    $.validator.addMethod("letras", function(value, element) {
        return this.optional(element) || /^[a-zA-ZñÑáÁéÉíÍóÓúÚ ]*$/.test(value);
    });

    $.validator.addMethod("decimal", function(value, element) {
        return this.optional(element) || /^-?\d+(\.\d{1,2})?$/.test(value);
    });

    $("#form_liquidaciones").validate({
        debug: true,
        errorClass: "my-error-class",
        highlight: function (element, required) {
            $(element).css('border', '2px solid #FDADAF');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).css('border', '1px solid #CCC');
        },
        rules:  {
            txt_rut: {
                rut: true,
                maxlength: 12
            },
            txt_nombres: {
                required: true,
                letras: true,
                maxlength: 45
            },
            txt_ape_pat: {
                required: true,
                letras: true,
                maxlength: 45
            },
            txt_ape_mat: {
                letras: true,
                maxlength: 45
            },
            txt_calle: {
                charspecial: true,
                maxlength: 60
            },
            txt_numero: {
                digits: true,
                maxlength: 6
            },
            txt_resto_direccion: {
                charspecial: true,
                maxlength: 200
            },
            txt_prevision:{
                letras: true,
                maxlength: 45
            },
            txt_prevision_porcen:{
                maxlength: 5,
                decimal:true
            },
            txt_afp:{
                letras: true,
                maxlength: 45
            },
            txt_afp_porcent:{
                maxlength: 5,
                decimal:true
            },
            txt_sueldo_bruto:{
                maxlength: 7,
                digits: true
            },
            txt_jornada:{
                maxlength: 2,
                digits: true
            },
           
        },
        messages: {
            txt_rut: {
                rut: "Solo números o k",
                maxlength: "Máximo 10 caracteres"
            },
            txt_nombres: {
                required: "El nombre de socio es obliatorio",
                letras: "Solo puede ingresar letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_ape_pat: {
                required: "El apellido paterno es obligatorio",
                letras: "Solo puede ingresar letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_ape_mat: {
                letras: "Solo puede ingresar letras",
                maxlength: "Máximo 45 caracteres"
            },
            txt_calle: {
                charspecial: "Ha ingresado caracteres no permitdos",
                maxlength: "Máximo 60 caracteres"
            },
            txt_numero: {
                digits: "Solo números",
                maxlength: "Máximo 6 dígitos"
            },
            txt_resto_direccion: {
                charspecial: "Hay caracteres extraños no permitdos",
                maxlength: "Máximo 200 caracteres"
            },
            txt_prevision:{
                letras: "Solo Puede ingresar Letras",
                maxlength: "Maximo 45 caracteres"
            },
            txt_prevision_porcen:{
                maxlength: "Maximo 4 caracteres",
                decimal:"Solo se permiten numero o decimales separados por punto (.) "
            },
            txt_afp:{
                letras: "Solo Puede ingresar Letras",
                maxlength: "Maximo 45 caracteres"
            },
            txt_afp_porcent:{
                maxlength: "Maximo 4 caracteres",
                decimal:"Solo se permiten numero o decimales separados por punto (.) "
            },
             txt_sueldo_bruto:{
                maxlength: "Maximo 7 caracteres", 
                digits: "Solo se permiten numeros"
            },
            txt_jornada:{
                maxlength: "Maximo 2 caracteres",
                digits: "Solo se permiten numeros"
            },
            dt_mes:{
                required: "Este campo es requerido",
            },
            txt_valor_uf:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
            txt_valor_uf:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
            txt_cotizacion_afp:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
            txt_obligatorio:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
            txt_cotizacion_pactada:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
            txt_diferencia_isapre:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
            txt_dias_trabajados:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
             txt_apagar:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
             txt_cargas:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            },
            txt_otros_descuentos:{
                required:"Valor Requerido",
                digits:"Solo se aceptan numeros"
            }
        }
    });

    var grid_vacaciones = $("#grid_vacaciones").DataTable({
        responsive: true,
        paging: true,
        destroy: true,
        select: {
            toggleable: false
        },
        ajax: base_url + "/RecursosH/Ctrl_liquidaciones/datatable_liquidaciones",
        orderClasses: true,
        columns: [
            {"data":"id"},
            {"data":"rut"},
            {"data":"funcionario"},
            {"data":"mes"},
            {"data":"valor_uf"},
            {"data":"dias_trabajados"},
            {"data":"sueldo_bruto"},
            {"data":"afp"},
            {"data":"obligatorio"},
            {"data":"pactada"},
            {"data":"diferencia_isapre"},
            {"data":"afc"},
            {"data":"otros"},
            {"data":"total_prevision"},
            {"data":"base_tributable"},
            {"data":"cargas"},
            {"data":"a_pagar"},
            {"data":"id_apr"},
            {"data":"fecha_genera"},
            {"data":"usuario_registra"},
            {"data": "id",
                "render":function(data,type,row){
                                 return "<button type='button' class='btn_imprimir btn btn-primary' title='Imprimir'><i class='fas fa-print'></i></button>"
                       }
              }
        ],
        "columnDefs": [
            { "targets": [0,4,7,8,9,10,11,12,13,14,15,17], "visible": false, "searchable": false }
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
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
    });

     $("#grid_vacaciones tbody").on("click", "button.btn_imprimir", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_vacaciones.row(tr).data();
        var id=data.id;        

        window.open(base_url + "/RecursosH/Ctrl_liquidaciones/imprime_liquidacion/"+id);

               
    });

    
});