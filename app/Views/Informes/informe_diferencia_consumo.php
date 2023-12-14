<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-cash-register mr-1"></i> Informe Diferencia de consumo</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    
  <!-- <label class="small mb-1" for="dt_mes_año">Mes Consumo</label>
  <input type='text' class="form-control" id='dt_mes_año' name="dt_mes_año"/> -->
  
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#listadoPagosDiarios" aria-expanded="false" aria-controls="listadoPagosDiarios">
              <i class="fas fa-cash-register mr-1"></i> Información de diferencia de consumo
            </div><BR>
              
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="form-group d-flex">
                  <label class="small mb-1" for="dt_fecha_mes">Mes a Consultar</label>
                  <input type='text' class="form-control" id='dt_fecha_mes' name="dt_fecha_mes"/>
                  <button type="button" name="btn_exportar_mes" id="btn_exportar_mes" class="btn btn-info" style="width: 20%;height: 10%"><i class="fas fa-file-excel"></i></button>
                </div>

              </div>

            
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_diferencia_consumo.js"></script>