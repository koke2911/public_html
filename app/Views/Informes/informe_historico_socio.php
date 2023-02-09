<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-history"></i> Informe Histórico por Socio</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="card">
      <div class="card-body">
        <center>
          <button type="button" name="btn_imprimir" id="btn_imprimir" class="btn btn-primary"><i class="fas fa-print"></i>
            Imprimir Histórico
          </button>
        </center>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="card-header">
        <i class="fas fa-search"></i> Buscar
      </div>
      <div class="card shadow">
        <div class="card-body">
          <div class="container-fluid">
            <form id="form_histPagos" name="form_histPagos" encType="multipart/form-data">
              <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_id_socio">Id. Socio</label>
                    <input type='text' class="form-control" id='txt_id_socio' name="txt_id_socio"/>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_rut_socio">RUT Socio</label>
                    <input type='text' class="form-control" id='txt_rut_socio' name="txt_rut_socio"/>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_rol">ROL Socio</label>
                    <input type='text' class="form-control" id='txt_rol' name="txt_rol"/>
                  </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="btn_buscar_socio"></label>
                    <button type="button" name="btn_buscar_socio" id="btn_buscar_socio" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar Socio</button>
                  </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_nombre_socio">Nombre Socio</label>
                    <input type='text' class="form-control" id='txt_nombre_socio' name="txt_nombre_socio"/>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="dlg_buscar_socio" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buscar Socio (Doble click, para seleccionar)</h4>
        </div>
        <div class="modal-body">
          <div id="divContenedorBuscarSocio"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_historico_socio.js"></script>