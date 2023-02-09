var base_url = $("#txt_base_url").val();

var peso = {
    validaEntero: function  ( value ) {
        var RegExPattern = /[0-9]+$/;
        return RegExPattern.test(value);
    },
    formateaNumero: function (value) {
        if (peso.validaEntero(value))  {  
            var retorno = '';
            value = value.toString().split('').reverse().join('');
            var i = value.length;
            while(i>0) retorno += ((i%3===0&&i!=value.length)?'.':'')+value.substring(i--,i);
            return retorno;
        }
        return 0;
    },
    quitar_formato : function(numero){
        numero = numero.split('.').join('');
        return numero;
    }
}

function buscar_subsidios() {
    var mes_consumo = $("#dt_mes_consumo").val();
	$("#grid_subsidios").dataTable().fnReloadAjax(base_url + "/Informes/Ctrl_informe_municipal/datatable_informe_municipal/" + mes_consumo);
    $("#grid_subsidios").DataTable().ajax.reload(function(json) {
        $("#btn_emitir_factura").prop("disabled", false);
    });
}

function emitir_factura(id_proveedor, rut_proveedor, razon_social) {
    Swal.fire({
        title: "¿Emitir Factura?",
        text: "RUT Municipalidad: " + rut_proveedor + ", Razón Social: " + razon_social + ".",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            var sub50 = $('#grid_subsidios').DataTable().column(4).data().sum();
            var sub100 = $('#grid_subsidios').DataTable().column(5).data().sum();
            var mes_facturado = $("#dt_mes_consumo").val();

            setTimeout(function() {
                $(".div_sample").JQLoader({
                    theme: "standard",
                    mask: true,
                    background: "#fff",
                    color: "#fff"
                });
            }, 500);

            setTimeout(function() {
                $.ajax({
                    url: base_url + "/Informes/Ctrl_informe_municipal/emitir_factura",
                    type: "POST",
                    async: false,
                    dataType: "json",
                    data: {
                        id_proveedor: id_proveedor,
                        sub50: sub50,
                        sub100: sub100,
                        mes_facturado: mes_facturado
                    },
                    success: function(respuesta) {
                        const OK = 1;
                        if (respuesta.estado == OK) {
                            alerta.ok("alerta", respuesta.mensaje);
                            var url = base_url + "/Informes/Ctrl_informe_municipal/imprimir_factura/" + respuesta.folio;
                            window.open(url, "Factura Electrónica", "width=1200,height=800,location=0,scrollbars=yes");
                        } else {
                            alerta.error("alerta", respuesta.mensaje);
                        }

                        $(".div_sample").JQLoader({
                            theme: "standard",
                            mask: true,
                            background: "#fff",
                            color: "#fff",
                            action: "close"
                        });
                    },
                    error: function(error) {
                        respuesta = JSON.parse(error["responseText"]);
                        alerta.error("alerta", respuesta.message);

                        $(".div_sample").JQLoader({
                            theme: "standard",
                            mask: true,
                            background: "#fff",
                            color: "#fff",
                            action: "close"
                        });
                    }
                });
            }, 500);
        }
    });
}

$(document).ready(function() {
    $("#btn_emitir_factura").prop("disabled", true);

	$("#dt_mes_consumo").datetimepicker({
        format: "MM-YYYY",
        useCurrent: false,
        locale: moment.locale("es")
    }).on("dp.change", function() {
        buscar_subsidios();
    });

    $("#btn_emitir_factura").on("click", function() {
        $("#tlt_buscador").text("Buscar Municipalidad");
        $("#divContenedorDlg").load(
            base_url + "/Finanzas/Ctrl_ingresos/v_buscar_proveedor"
        ); 

        $('#dlg_buscador').modal('show');
    });

	var grid_subsidios = $("#grid_subsidios").DataTable({
		responsive: true,
        paging: false,
        destroy: true,
        select: {
            toggleable: false
        },
        orderClasses: true,
        dom: 'Bfrtilp',
        columns: [
            { "data": "id_metros" },
            { "data": "rut_socio" },
            { "data": "nombre_socio" },
            { "data": "mes_cubierto" },
            { 
                "data": "subsidio_50",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "subsidio_100",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            },
            { 
                "data": "subsidio",
                "render": function(data, type, row) {
                    return peso.formateaNumero(data);
                }
            }
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            $( api.column(4).footer()).html(
                peso.formateaNumero(api.column(4, {page:'current'} ).data().sum())
            );
            $( api.column(5).footer()).html(
                peso.formateaNumero(api.column(5, {page:'current'} ).data().sum())
            );
            $( api.column(6).footer()).html(
                peso.formateaNumero(api.column(6, {page:'current'} ).data().sum())
            );
        },
        buttons: [ 
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: "Informe Municipal",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    format: {
                        body: function ( data, row, column, node ) {
                            const SUBSIDIOS = 4; 

                            if (column == 4 || column == 5 || column == 6) {
                                return peso.quitar_formato(data);
                            } else {
                                return data;
                            }
                        },
                        footer: function ( data, row, column, node ) {
                            return peso.quitar_formato(data);
                        }
                    }
                }
            },
            {
                extend:    'pdfHtml5',
                text:      '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                title: "Informe Municipal",
                pageSize: 'LETTER',
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i> Imprimir',
                titleAttr: 'Imprimir',
                className: 'btn btn-info',
                title: "Informe Municipal",
                footer: true,
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
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
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Sig.",
                "previous": "Ant."
            }
        }
	});
});