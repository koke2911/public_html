<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-male"></i> Informe de Socios</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosListadoSocios" aria-expanded="false" aria-controls="datosListadoSocios">
              <i class="fas fa-male"></i> Listado de socios
            </div>
            <div class="card shadow mb-12" id="datosListadoSocios">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_socios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>RUT</th>
                          <th>ROL</th>
                          <th>Nombre</th>
                          <th>Fecha Entrada</th>
                          <th>Fecha Nacimiento</th>
                          <th>Sexo</th>
                          <th>Calle</th>
                          <th>N° Casa</th>
                          <th>Resto Dirección</th>
                          <th>Comuna</th>
                          <th>Ruta</th>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_socios.js"></script>