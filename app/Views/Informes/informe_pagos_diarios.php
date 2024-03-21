<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-cash-register mr-1"></i> Informe Pagos Diarios</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    
  <!-- <label class="small mb-1" for="dt_mes_año">Mes Consumo</label>
  <input type='text' class="form-control" id='dt_mes_año' name="dt_mes_año"/> -->
  
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#listadoPagosDiarios" aria-expanded="false" aria-controls="listadoPagosDiarios">
              <i class="fas fa-cash-register mr-1"></i> Listado Pagos Diarios
            </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="form-group d-flex">
                  <label class="small mb-1" for="dt_fecha_dia">Dia a Consultar</label>
                  <input type='text' class="form-control" id='dt_fecha_dia' name="dt_fecha_dia"/>
                  <button type="button" name="btn_exportar" id="btn_exportar" class="btn btn-info" style="width: 20%;height: 10%"><i class="fas fa-file-excel"></i></button>
                </div>

              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="form-group d-flex">
                  <label class="small mb-1" for="dt_fecha_mes">Mes a Consultar</label>
                  <input type='text' class="form-control" id='dt_fecha_mes' name="dt_fecha_mes"/>
                  <button type="button" name="btn_exportar_mes" id="btn_exportar_mes" class="btn btn-info" style="width: 20%;height: 10%"><i class="fas fa-file-excel"></i></button>
                </div>

              </div>

            <div class="card shadow mb-12" id="listadoPagosDiarios">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_pagos_diarios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>ROL Socio</th>
                          <th>Nombre Socio</th>
                          <th>Total Pago</th>
                          <th>Entregado</th>
                          <th>Vuelto</th>
                          <th>Consumo</th>
                          <th>Usuario Reg.</th>
                          <th>Forma Pago</th>
                          <th>F. Transferencia</th>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_pagos_diarios.js"></script>