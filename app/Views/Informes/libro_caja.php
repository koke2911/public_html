<main>
      
     <div class="row">
      <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-file-excel"></i> Generar Libro de caja Mensual
            </div>
            <div class="card shadow mb-12 " id="datorReporte">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_libro" name="form_libro" encType="multipart/form-data">
                     <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_mes_consulta">Mes</label>
                          <input type='text' class="form-control" id='dt_mes_consulta' name="dt_mes_consulta"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                              <label class="small mb-1" for="btn_export">Exportar</label><BR>
                              <button type="button" name="btn_export" id="btn_export" class="btn btn-success"><i class="fas fa-file-excel">Excel</i></button>
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
      <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-file-excel"></i> Generar Resumen Libro de caja Anual
            </div>
            <div class="card shadow mb-12 " id="datorReporte">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_libro" name="form_libro" encType="multipart/form-data">
                     <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_ano">año</label>
                          <input type='text' class="form-control" id='dt_ano' name="dt_ano"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                              <label class="small mb-1" for="btn_export_anual">Exportar</label><BR>
                              <button type="button" name="btn_export_anual" id="btn_export_anual" class="btn btn-success"><i class="fas fa-file-excel">Excel</i></button>
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
      
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/libro_caja.js"></script>