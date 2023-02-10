<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-receipt"></i> Boleta Electronica</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_emitir" id="btn_emitir" class="btn btn-success"><i class="fas fa-receipt"></i> Emitir DTE</button>
              <button type="button" name="btn_imprimir" id="btn_imprimir" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir DTE</button>
              <button type="button" name="btn_aviso_cobranza" id="btn_aviso_cobranza" class="btn btn-info"><i class="fas fa-print"></i> Imprimir Aviso de Cobranza</button>
            </center>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosBuscarSocios" aria-expanded="false" aria-controls="datosBuscarSocios">
              <i class="fas fa-receipt"></i> Buscar
            </div>
            <div class="card shadow mb-12 collapse" id="datosBuscarSocios">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_boleta_electronica" name="form_boleta_electronica" encType="multipart/form-data">
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
                    </div>
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombre_socio">Nombre Socio</label>
                          <input type='text' class="form-control" id='txt_nombre_socio' name="txt_nombre_socio"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_mes_año">Mes Consumo</label>
                          <input type='text' class="form-control" id='dt_mes_año' name="dt_mes_año"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_sector">Sector</label>
                          <select id="cmb_sector" name="cmb_sector" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="card shadow mb-12">
                          <div class="card-body">
                            <center>
                              <button type="button" class="btn btn-dark" id="btn_buscar"><i class="fas fa-search"></i> Buscar</button>
                              <button type="button" class="btn btn-light" id="btn_limpiar"><i class="fas fa-broom"></i> Limpiar Formulario</button>
                            </center>
                          </div>
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
            <div class="card-header" data-toggle="collapse" data-target="#seleccionar_boletas" aria-expanded="false" aria-controls="seleccionar_boletas">
              <i class="fas fa-receipt"></i> Seleccionar Boletas
            </div>
            <div class="card shadow mb-12" id="seleccionar_boletas">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_boletas" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Folio SII</th>
                          <th>Boleta</th>
                          <th>Ruta</th>
                          <th>id_socio</th>
                          <th>rut_socio</th>
                          <th>ROL Socio</th>
                          <th>Nombre Socio</th>
                          <th>id_Arranque</th>
                          <th>subsidio</th>
                          <th>Subsidio $</th>
                          <th>Sector</th>
                          <th>diametro</th>
                          <th>diametro</th>
                          <th>Fecha Ingreso</th>
                          <th>Fecha Vencimiento</th>
                          <th>Consumo Antetior</th>
                          <th>Consumo Actual</th>
                          <th>Metros</th>
                          <th>Subtotal $</th>
                          <th>Multa $</th>
                          <th>T. Servicios $</th>
                          <th>T. Mes $</th>

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
  <div id="dlg_emitir_boletas" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="emitir_boletas">
          <div class="modal-header">
            <h4 class="modal-title">Emitir DTEs</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="comments">Comentarios</label>
              <textarea name="comments" id="comments" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Crear" class="btn btn-success">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
          </div>
        </form>
      </div>
    </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Pagos/boleta_electronica.js"></script>