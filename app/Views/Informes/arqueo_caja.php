<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-briefcase"></i> Arqueo de Caja</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>                           
              <button type="button" name="btn_aceptar" id="btn_aceptar" class="btn btn-success"><i class="fas fa-check"></i> Aceptar</button>
              <button type="button" name="btn_cancelar" id="btn_cancelar" class="btn btn-danger"><i class="fas fa-ban"></i> Cancelar</button>
              
            </center>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosArqueo" aria-expanded="false" aria-controls="datosArqueo">
              <i class="fas fa-briefcase"></i> Arqueo de caja
            </div>
            <div class="card shadow mb-12 collapse" id="datosArqueo">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_arqueo" name="form_arqueo" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_mes">Mes</label>
                          <input type="text" class="form-control" name="dt_mes"  id="dt_mes"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_ingresos">Ingresos</label>
                          <input type='text' class="form-control" value="0" id='txt_ingresos' name="txt_ingresos"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_egresos">Egresos</label>
                          <input type='text' class="form-control" value="0" id='txt_egresos' name="txt_egresos"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_saldo_periodo">Saldo Periodo</label>
                          <input type='text' class="form-control" value="0" id='txt_saldo_periodo' name="txt_saldo_periodo"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_saldo_anterior">Saldo Anterior</label>
                          <input type='text' class="form-control" value="0" id='txt_saldo_anterior' name="txt_saldo_anterior"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_saldo_siguiente">Saldo Mes Siguiente</label>
                          <input type='text' class="form-control" value="0" id='txt_saldo_siguiente' name="txt_saldo_siguiente"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_saldo_banco">Saldo Banco Cta Cte,Ahorro.</label>
                          <input type='text' value="0" id="txt_saldo_banco" name="txt_saldo_banco" class="form-control"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_saldo_deposito">Saldo deposito a plazo, Otros</label>
                          <input type='text' value="0" id="txt_saldo_deposito" name="txt_saldo_deposito" class="form-control"></input>
                        </div>
                      </div>
                      
                    </div> 
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_total_fondos">Total Fondos Disponibles</label>
                          <input  type='text' value="0" id="txt_total_fondos" name="txt_total_fondos" class="form-control"></input>
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
            <div class="card-header"><i class="fas fa-briefcase"></i> Historial de arqueos</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_arqueo" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>id</th>
                          <th>mes</th>
                          <th>Ingresos</th>
                          <th>Egresos</th>
                          <th>Saldo periodo</th>
                          <th>Saldo anterior</th>
                          <th>Saldo siguiente</th>
                          <th>Saldo banco</th>
                          <th>Saldo deposito</th>
                          <th>Total fondos</th>
                          <th>Fecha reg</th> 
                          <!-- <th>Imprimir</th>  -->
                          <th>Anular</th>                          
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/arqueo_caja.js"></script>