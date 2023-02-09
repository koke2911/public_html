<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-swimming-pool mr-1"></i> Informe de Arranques</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#listadoArranques" aria-expanded="false" aria-controls="listadoArranques">
              <i class="fas fa-swimming-pool mr-1"></i> Listado de Arranques
            </div>
            <div class="card shadow mb-12" id="listadoArranques">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_arranques" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>ROL Socio</th>
                          <th>Nombre Socio</th>
                          <th>Núm. Medidor</th>
                          <th>Calle</th>
                          <th>N°</th>
                          <th>Resto Direc.</th>
                          <th>Alcantarillado</th>
                          <th>Cuota Socio</th>
                          <th>Tipo Documento</th>
                          <th>Descuento</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_arranques.js"></script>