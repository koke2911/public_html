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
}

function buscaUf(){
    var apiUrl = "https://mindicador.cl/api/uf/16-11-2023";

   
    $.ajax({
        url: apiUrl,
        method: "GET",
        dataType: "json",
        success: function (data) {
            
            // console.log("Datos de la API:", data);
            var valorUF = data.serie[0].valor;
            // console.log("Valor de la UF:", valorUF);
            $("#txt_valor_uf").val(valorUF);

        },
        error: function (error) {
            console.error("Error al obtener datos de la API:", error);
        }
    });
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
                                                   
                        }
                    },
                    error: function(error) {
                        respuesta = JSON.parse(error["responseText"]);
                        alerta.error("alerta", respuesta.message);
                    }
                });
}

function guardar_liquidacion() {
    var rut =$("#txt_rut").val();
    var mes =$("#dt_mes").val();
    var valor_uf=$("#txt_valor_uf").val();
    var dias_trabajados=$("#txt_dias_trabajados").val();
    var sueldo_bruto=$("#txt_sueldo").val();
    var afp=$("#txt_cotizacion_afp").val();
    var obligatorio=$("#txt_obligatorio").val();
    var pactada=$("#txt_cotizacion_pactada").val();
    var diferencia_isapre=$("#txt_diferencia_isapre").val();
    var afc =$("#txt_afc").val();
    var otros =$("#txt_otros_descuentos").val();
    var total_prevision =$("#txt_total_prevision").val();
    var base_tributable=$("#txt_base_tributable").val();
    var cargas =$("#txt_cargas").val();
    var a_pagar=$("#txt_apagar").val();
    
    

    $.ajax({
        url: base_url + "/RecursosH/Ctrl_liquidaciones/guardar_liquidacion",
        type: "POST",
        async: false,
        data: {
            rut: rut,
            mes: mes,
            valor_uf: valor_uf,
            dias_trabajados: dias_trabajados,
            sueldo_bruto: sueldo_bruto,
            afp: afp,
            obligatorio: obligatorio,
            pactada: pactada,
            diferencia_isapre: diferencia_isapre,
            afc: afc,
            otros: otros,
            total_prevision: total_prevision,
            base_tributable: base_tributable,
            cargas: cargas,
            a_pagar: a_pagar
        },
        success: function(respuesta) {
            const OK = 1;
            if (respuesta == OK) {
                $("#grid_liquidaciones").dataTable().fnReloadAjax(base_url + "/RecursosH/Ctrl_liquidaciones/datatable_liquidaciones");
                $("#form_liquidaciones")[0].reset();
                des_habilitar(true, false);
                alerta.ok("alerta", "liquidacion guardado con éxito");
                $("#datosLiquidacion").collapse("hide");
                
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
    
   

    $("#dt_contrato").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function () {
        $("#dt_contrato").blur();
      });

      $("#dt_mes").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
      }).on("dp.change", function () {
        $("#dt_mes").blur();
      });


    $("#btn_nuevo").on("click", function() {
        des_habilitar(false, true);
        $("#form_liquidaciones")[0].reset();
        buscaUf();

        // $("#btn_modificar").prop("disabled", true);
        // $("#btn_eliminar").prop("disabled", true);
        $("#datosLiquidacion").collapse("show");
    });

    $("#btn_calcular").on("click", function() {
        
        var  obligatorio= $("#txt_obligatorio").val();
        var  pactada= 0;//$("#txt_cotizacion_pactada").val();
        var  isapre= $("#txt_diferencia_isapre").val();
        var  afc = $("#txt_afc").val();
        var  afp = $("#txt_cotizacion_afp").val();

        var total_prevision = parseFloat(obligatorio) + parseFloat(pactada) + parseFloat(isapre) + parseFloat(afc) + parseFloat(afp);
        $("#txt_total_prevision").val(total_prevision);

        var base_tributable = parseFloat($("#txt_sueldo").val()) - parseFloat($("#txt_total_prevision").val());
        $("#txt_base_tributable").val(base_tributable);

        var a_pagar= base_tributable + parseFloat($("#txt_cargas").val()) - parseFloat($("#txt_otros_descuentos").val());
        $("#txt_apagar").val(a_pagar);
    });

    // $("#btn_modificar").on("click", function() {
    //     des_habilitar(false, true);
    //     $("#btn_modificar").prop("disabled", true);
    //     $("#btn_eliminar").prop("disabled", true);
    //     datatable_enabled = false;
    //     $("#datosLiquidacion").collapse("show");
    // });

    // $("#btn_eliminar").on("click", function() {
    //     var nombres = $("#txt_nombres").val();
    //     var ape_pat = $("#txt_ape_pat").val();
    //     var ape_mat = $("#txt_ape_mat").val();
    //     var nombre_completo = nombres + " " + ape_pat + " " + ape_mat;
        
    //     Swal.fire({
    //         title: "¿Eliminar Funcionario?",
    //         text: "¿Está seguro de eliminar a " + nombre_completo + "?",
    //         input: 'text',
    //         icon: "question",
    //         showCancelButton: true,
    //         confirmButtonColor: "#3085d6",
    //         cancelButtonColor: "#d33",
    //         confirmButtonText: "Si",
    //         cancelButtonText: "No"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             var id_funcionario = $("#txt_id_funcionario").val();
    //             cambiar_estado_funcionario("eliminar", result.value, id_funcionario);
    //         }
    //     });
    // });

    $("#btn_aceptar").on("click", function() {
        if ($("#form_liquidaciones").valid()) {
            guardar_liquidacion();
        }
    });

    $("#btn_cancelar").on("click", function() {
        $("#form_liquidaciones")[0].reset();
        des_habilitar(true, false);
        datatable_enabled = true;
        $("#datosLiquidacion").collapse("hide");
    });

    $("#btn_buscar").on("click", function() {
        var funcionario=$("#txt_rut").val();
        if(funcionario!=""){
            mostrar_datos_funcionarios();
        }else{
            alerta.error("alerta", "Debe ingresar un rut valido");
        }
    });

    $("#txt_dias_trabajados").on("blur", function() {
        var bruto = parseFloat($("#txt_sueldo_bruto").val());
        var dias_total = parseFloat($("#txt_jornada").val());
        var dias_trabajados = parseFloat($("#txt_dias_trabajados").val());

        var valor_dia = bruto / dias_total;
        var sueldo = Math.round(valor_dia * dias_trabajados);

        $("#txt_sueldo").val((sueldo));

        var total_afp= Math.round(sueldo * parseFloat($("#txt_afp_porcent").val()) / 100);
        $("#txt_cotizacion_afp").val(total_afp);

        var obligatorio =  Math.round(sueldo * 7 / 100);
        $("#txt_obligatorio").val(obligatorio);

        if($("#txt_prevision").val()=="FONASA"){
            $("#txt_cotizacion_pactada").val(obligatorio);
            $("#txt_diferencia_isapre").val(0);
        }else{
            var valor_uf = $("#txt_valor_uf").val();
            var total_isapre =  Math.round(valor_uf * parseFloat($("#txt_prevision_porcen").val()));

            var diferencia_isapre = total_isapre - obligatorio;
            $("#txt_cotizacion_pactada").val(obligatorio);
            $("#txt_diferencia_isapre").val(diferencia_isapre);

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
            dt_mes:{
                required:true
            },
            txt_valor_uf:{
                required:true,
                digits:true
            },
            txt_valor_uf:{
                required:true,
                decimal:true
            },
            txt_cotizacion_afp:{
                required:true,
                digits:true
            },
            txt_obligatorio:{
                required:true,
                digits:true
            },
            txt_cotizacion_pactada:{
                required:true,
                digits:true
            },
            txt_diferencia_isapre:{
                required:true,
                decimal:true
            },
            txt_dias_trabajados:{
                required:true,
                digits:true
            },
             txt_apagar:{
                required:true,
                digits:true
            },
             txt_cargas:{
                required:true,
                digits:true
            },
            txt_otros_descuentos:{
                required:true,
                digits:true
            }
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

    var grid_liquidaciones = $("#grid_liquidaciones").DataTable({
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

     $("#grid_liquidaciones tbody").on("click", "button.btn_imprimir", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }

        var data = grid_liquidaciones.row(tr).data();
        var id=data.id;        

        window.open(base_url + "/RecursosH/Ctrl_liquidaciones/imprime_liquidacion/"+id);

               
    });

    // $("#grid_liquidaciones tbody").on("click", "tr", function () {
    //     if (datatable_enabled) {
    //         var tr = $(this).closest('tr');
    //         if ($(tr).hasClass('child') ) {
    //             tr = $(tr).prev();  
    //         }

    //         var data = grid_liquidaciones.row(tr).data();
    //         mostrar_datos_funcionarios(data);
    //         des_habilitar(true, false);
    //         $("#btn_modificar").prop("disabled", false);
    //         $("#btn_eliminar").prop("disabled", false);
    //         $("#datosLiquidacion").collapse("hide");
    //     }
    // });

    // $("#grid_liquidaciones tbody").on("click", "button.traza_funcionario", function () {
    //     if (datatable_enabled) {
    //         $("#divContenedorTrazaFuncionario").load(
    //             base_url + "/Finanzas/Ctrl_funcionarios/v_funcionario_traza"
    //         ); 

    //         $('#dlg_traza_funcionario').modal('show');
    //     }
    // });
});