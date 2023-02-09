<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-cut"></i> Informe Afecto a Corte</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card mb-4">
          <div class="card-header" data-toggle="collapse" data-target="#informeAfectoCorte" aria-expanded="false" aria-controls="informeAfectoCorte">
            <i class="fas fa-cut"></i> Buscar (ENTER para buscar)
          </div>
          <div class="card shadow mb-12 collapse" id="informeAfectoCorte">
            <div class="card-body">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label class="small mb-1" for="txt_n_meses">N° de Meses Deudores</label>
                      <input type='text' class="form-control" id='txt_n_meses' name="txt_n_meses"/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card mb-4">
          <div class="card-header" data-toggle="collapse" data-target="#listaAfectoCorte" aria-expanded="false" aria-controls="listaAfectoCorte">
            <i class="fas fa-cut"></i> Afecto a Corte
          </div>
          <div class="card shadow mb-12" id="listaAfectoCorte">
            <div class="card-body">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table id="grid_afecto_corte" class="table table-bordered" width="100%">
                    <thead class="thead-dark">
                      <tr>
                        <th>ROL</th>
                        <th>RUT</th>
                        <th>Nombre</th>
                        <th>Meses Pend.</th>
                        <th>Total Deuda $</th>
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
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_afecto_corte.js"></script>