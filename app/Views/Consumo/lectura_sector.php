<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-book-reader"></i> Lecturas por Sector</h3>
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
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_sectores">Sectores</label>
                          <select id="cmb_sectores" name="cmb_sectores" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_mes_consumo">Mes de Consumo</label>
                          <input type='text' class="form-control" id='dt_mes_consumo' name="dt_mes_consumo"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_vencimiento">Fecha Vencimiento del Pago</label>
                          <input type='text' class="form-control" id='dt_fecha_vencimiento' name="dt_fecha_vencimiento"/>
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
                          <th>Id. Socio</th>
                          <th>Id. Metros</th>
                          <th width="10%">Ruta</th>
                          <th width="15%">ROL</th>
                          <th width="25%">Socio</th>
                          <th width="10%">N° Medidor</th>
                          <th width="15%">L. Anterior</th>
                          <th width="15%">L. Actual</th>
                          <th width="10%">Promedio</th>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Consumo/lecturas_sector.js"></script>