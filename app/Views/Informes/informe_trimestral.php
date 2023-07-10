<main>
      
     <div class="row">
      <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-file-excel"></i> Generar Reporte Mensual
            </div>
            <div class="card shadow mb-12 " id="datorReporte">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_trimestral" name="form_trimestral" encType="multipart/form-data">
                     <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_ano_consulta">Año</label>
                          <input type='text' class="form-control" id='dt_ano_consulta' name="dt_ano_consulta"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_trimestre">Trimestre</label>
                          <input type='text' class="form-control" id='txt_trimestre' name="txt_trimestre"/>
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
      
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_trimestral.js"></script>