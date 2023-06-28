var base_url = $("#txt_base_url").val();


function imrpimirSol(){

}

$(document).ready(function() {

   

    var grid_socios = $("#grid_socios").DataTable({
		responsive: true,
        paging: true,
        destroy: true,
        pageLength:30,
        select: {
            toggleable: false
        },
        ajax: base_url + "/Formularios/Ctrl_solicitud_subsidio/datatable_socios",
        orderClasses: true,
        columns: [
            { "data": "id" },
            { "data": "rol" },
            { "data": "nombre_socio" },
            { 
                "data": "subsidio",
                "render": function(data, type, row) {
                        if(data!='Sin Subsidio'){
                             return "<i class='text-success'>Adjudicado</i>"
                        }else{
                            return data;
                        } 
                }
            } ,
            { "data": "solicitud",
               "render":function(data,type,row){

                    if(data!='Sin Solicitud'){
                         // return "<i class='text-success'>Solicitado</i>"
                         return "<button type='button' class='btn_solicitado btn btn-primary' title='Imprimir'><i class='fas fa-print'></i> Imprmir</button>"

                    }else{
                        return data;
                    } 
               }
             },
            { 
                "data": "id_so",
                "render": function(data, type, row) {
                        
                        return "<button type='button' class='btn_solicita btn btn-success' title='Solicitar'><i class=''></i> Solicitar</button>"
                    // }
                }
            }           
        ],
        "columnDefs": [
            // { "targets": [1,5,6,7,8,9,10,11,12,13,14,15,18,19,20,21], "visible": false, "searchable": false }
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

    function genera_solicitud(id_socio){
        // alert(id_socio);
        $.ajax({
        url: base_url + "/Formularios/Ctrl_solicitud_subsidio/generar_solicitud",
        type: "POST",
        async: false,
        data: { 
            id_socio: id_socio,
            
        },
        success: function(respuesta) {
            const OK = 1;

            if (respuesta == OK) {
                alerta.ok("alerta", 'Se ha Generado la Solicitud');
                $("#grid_socios").dataTable().fnReloadAjax(base_url + "/Formularios/Ctrl_solicitud_subsidio/datatable_socios");
                
                           
            } else {
                alerta.error("alerta", respuesta);
            }
        }
    });

    }

    $("#grid_socios tbody").on("click", "button.btn_solicita", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }
        
        var data = grid_socios.row(tr).data();
        var subsidio=data.subsidio;
        var estado_sol=data.solicitud;
        var nombre=data.nombre_socio;

        if( subsidio!="Sin Subsidio"){
            alerta.error("alerta", "El Socio ya posee un subsidio Adjudicado");
        }else if(estado_sol!="Sin Solicitud"){
            alerta.error("alerta", "El Socio ya posee una Solicitud");
        }else{
            Swal.fire({
            title: "Solicitar Subsidio",
            text: "¿Está seguro que desea solicitar subsidio a "+nombre+"?",
            // input: 'text',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                // var id_desinfeccion = $("#txt_id_desinfeccion").val();
                // eliminar_desinfecciones(id_desinfeccion);
                genera_solicitud(data.id);

            }
        });

        }
    });

    $("#grid_socios tbody").on("click", "button.btn_solicitado", function () {
        var tr = $(this).closest('tr');
        if ($(tr).hasClass('child') ) {
            tr = $(tr).prev();  
        }
        
        var data = grid_socios.row(tr).data();
        var subsidio=data.subsidio;
        var estado_sol=data.solicitud;
        var nombre=data.nombre_socio;
        var solicitud=data.solicitud;

        window.open(base_url + "/Formularios/Ctrl_solicitud_subsidio/imprime_solicitud/"+solicitud);

       

        
    });

    // $("#grid_socios tbody").on("click", "tr", function () {
    //     var tr = $(this).closest('tr');
    //     if ($(tr).hasClass('child') ) {
    //         tr = $(tr).prev();  
    //     }

    //     var data = grid_socios.row(tr).data();
    //     mostrar_datos(data);
    //     des_habilitar(true, false);
    //     $("#btn_modificar").prop("disabled", false);
    //     $("#btn_eliminar").prop("disabled", false);
    //     $("#datosDesinfeccion").collapse("hide");
    // });

    
});