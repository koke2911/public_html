<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-book-reader"></i> Informe Lecturas por Sector</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <br>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#informeInventario" aria-expanded="false" aria-controls="informeInventario">
              <i class="fas fa-search"></i> Buscar
            </div>
            <div class="card shadow mb-12 collapse" id="informeInventario">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_inf_inventario" name="form_inf_inventario" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_sectores">Sectores</label>
                          <select id="cmb_sectores" name="cmb_sectores" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-book-reader"></i> Listado de Socios</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_lecturas_sector" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Ruta</th>
                          <th>ROL</th>
                          <th>Socio</th>
                          <th>N° Medidor</th>
                          <th>Lectura Anterior</th>
                          <th>Lectura Actual</th>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_lecturas_sector.js"></script>