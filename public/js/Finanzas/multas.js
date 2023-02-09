var base_url = $("#txt_base_url").val();
var datatable_enabled = true;

function des_habilitar (a, b) {
}


$(document).ready(function () {

  var grid_multas = $("#grid_multas").DataTable({
    responsive: true,
    paging: true,
    // scrollY: '50vh',
    // scrollCollapse: true,
    destroy: true,
    select: {
      toggleable: false
    },
    ajax: base_url + "/Finanzas/Ctrl_multas/datatable_multas",
    orderClasses: true,
    columns: [
      {"data": "id_socio"},
      {"data": "nombres"},
      {"data": "ape_pat"},
      {"data": "ape_mat"},
      {"data": "glosa"},
      {"data": "monto"},
      {"data": "tipo"},
    ],
    "columnDefs": [
      {"targets": [0, 3, 4, 5, 9, 10, 11], "visible": false, "searchable": true}
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

  $("#grid_multas tbody").on("click", "tr", function () {
    if (datatable_enabled) {
      var tr = $(this).closest('tr');
      if ($(tr).hasClass('child')) {
        tr = $(tr).prev();
      }

      var data = grid_multas.row(tr).data();

      des_habilitar(true, false);
      $("#btn_modificar").prop("disabled", false);
      $("#btn_eliminar").prop("disabled", false);
    }
  });

  $("#btn_nuevo").on('click', function () {
    var grid_socios = $("#grid_socios").DataTable({
      responsive: true,
      paging: true,
      // scrollY: '50vh',
      // scrollCollapse: true,
      destroy: true,
      select: {
        toggleable: false
      },
      ajax: base_url + "/Formularios/Ctrl_socios/datatable_socios",
      orderClasses: true,
      columns: [
        {"data": "id_socio"},
        {"data": "rut"},
        {"data": "rol"},
        {"data": "nombres"},
        {"data": "ape_pat"},
        {"data": "ape_mat"},
        {"data": "nombre_completo"},
        {"data": "fecha_entrada"},
        {"data": "fecha_nacimiento"},
        {"data": "id_sexo"},
        {"data": "id_region"},
        {"data": "id_provincia"},
        {"data": "id_comuna"},
        {"data": "comuna"},
        {"data": "calle"},
        {"data": "numero"},
        {"data": "resto_direccion"},
        {"data": "usuario"},
        {"data": "fecha"},
        {
          "data": "id_socio",
          "render": function (data, type, row) {
            return "<button type='button' class='traza_socio btn btn-warning' title='Traza socio'><i class='fas fa-shoe-prints'></i></button>";
          }
        },
        {"data": "ruta"}
      ],
      "columnDefs": [
        {"targets": [0, 3, 4, 5, 9, 10, 11, 12, 14, 15, 16, 20], "visible": false, "searchable": false}
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
    $("#dlg_nuevo").modal('show');
  });
});