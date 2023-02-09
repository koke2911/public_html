<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-print"></i> Imprimir Facturas Municipalidad</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <div class="container-fluid">
            <div class="table-responsive">
              <table id="grid_facturas_muni" class="table table-bordered" width="100%">
                <thead class="thead-dark">
                  <tr>
                    <th>Folio SII</th>
                    <th>Mes Facturado</th>
                    <th>Usuario</th>
                    <th>Fecha Emisión</th>
                    <th><i class="fas fa-print"></i></th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Pagos/imprimir_facturas_muni.js"></script>
