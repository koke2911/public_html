<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-hand-holding-usd"></i> Informe de Subsidios</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#listadoSubsidios" aria-expanded="false" aria-controls="listadoSubsidios">
              <i class="fas fa-hand-holding-usd"></i> Listado de socios
            </div>
            <div class="card shadow mb-12" id="listadoSubsidios">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_subsidios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>ROL</th>
                          <th>Nombre</th>
                          <th>F. Decreto</th>
                          <th>F. Caducidad</th>
                          <th>%</th>
                          <th>F. Encuesta</th>
                          <th>Puntaje</th>
                          <th>N° Único</th>
                          <th>Díg. Único</th>
                          <th>Estado</th>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_subsidios.js"></script>