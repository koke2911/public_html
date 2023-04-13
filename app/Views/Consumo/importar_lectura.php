<main>
  <!-- <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>"> -->
  <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
  <div class="container-fluid">
   <form enctype="multipart/form-data" id="form">
      <div class="form-group">
        <div class="row">
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
        <div class="form-group">
          <label for="lecturas">Importar Archivo de Lecturas</label>
          <input id="lecturas" name="lecturas" type="file" class="form-control">
        </div>
      </div>
      
      <input type="submit" value="Subir" class="btn btn-success" >
    </form>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Consumo/importar_lectura.js"></script>