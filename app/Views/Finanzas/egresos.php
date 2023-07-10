<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-sign-out-alt"></i> Egresos Simples</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-sign-out-alt"></i> Datos Egreso Simple
            </div>
            <div class="card shadow mb-12" id="datosEgreso">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_egresos" name="form_egreso" encType="multipart/form-data">
                    <?php if (isset($id_egreso)) { ?>
                      <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                          <div class="form-group">
                            <label class="small mb-1" for="txt_id_egreso_egre">Id. Egreso</label>
                            <input type="text" class="form-control" name="txt_id_egreso_egre" id="txt_id_egreso_egre" value="<?php echo $id_egreso; ?>"/>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_egreso">Tipo de Egreso</label>
                          <select id="cmb_tipo_egreso" name="cmb_tipo_egreso" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_gasto">Tipo de Gasto</label>
                          <select id="cmb_tipo_gasto" name="cmb_tipo_gasto" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_egreso">Fecha</label>
                          <input type="text" class="form-control" name="dt_fecha_egreso" id="dt_fecha_egreso"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_monto">Monto $</label>
                          <input type="text" class="form-control" name="txt_monto" id="txt_monto"/>
                        </div>
                      </div>
                    </div>
                    <div class="card shadow mb-12">
                      <div class="card-body bg-light">
                        <div class="container-fluid">
                          <h5 class="card-title"><i class="fas fa-sort-numeric-up-alt"></i> Buscar Cuenta</h5>
                          <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_cuenta">Id. Cuenta</label>
                                <input type="text" class="form-control" name="txt_id_cuenta" id="txt_id_cuenta"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_banco">Banco</label>
                                <input type="text" class="form-control" name="txt_banco" id="txt_banco"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_tipo_cuenta">Tipo de Cuenta</label>
                                <input type="text" class="form-control" name="txt_tipo_cuenta" id="txt_tipo_cuenta"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_n_cuenta">N° de la Cuenta</label>
                                <input type="text" class="form-control" name="txt_n_cuenta" id="txt_n_cuenta"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_rut">RUT</label>
                                <input type="text" class="form-control" name="txt_rut" id="txt_rut"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_nombre">Nombre</label>
                                <input type="text" class="form-control" name="txt_nombre" id="txt_nombre"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_email">Correo Electrónico</label>
                                <input type="text" class="form-control" name="txt_email" id="txt_email"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_n_transaccion">N° de Cheque o Transacción</label>
                                <input type="text" class="form-control" name="txt_n_transaccion" id="txt_n_transaccion"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="btn_buscar_cuenta"></label>
                                <button id="btn_buscar_cuenta" name="btn_buscar_cuenta" class="form-control btn btn-dark"><i class="fas fa-search"></i> Buscar Cuenta</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="card shadow mb-12">
                      <div class="card-body bg-light">
                        <div class="container-fluid">
                          <h5 class="card-title">Buscar</h5>
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="card shadow mb-12">
                              <div class="card-body bg-info text-white">
                                <div class="container-fluid">
                                  <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                      <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="rd_proveedor" checked>
                                        <label class="form-check-label" for="rd_proveedor"><i class="fas fa-industry"></i> Proveedor</label>
                                      </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                      <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="rd_funcionario">
                                        <label class="form-check-label" for="rd_funcionario"><i class="fas fa-briefcase"></i> Directivo - Funcionario</label>
                                      </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                      <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="rd_socio">
                                        <label class="form-check-label" for="rd_socio"><i class="fas fa-male"></i> Socio</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_proveedor">Id.</label>
                                <input type="text" class="form-control" name="txt_id_proveedor" id="txt_id_proveedor"/>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_rut_proveedor">RUT</label>
                                <input type="text" class="form-control" name="txt_rut_proveedor" id="txt_rut_proveedor"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_razon_social">Nombre</label>
                                <input type="text" class="form-control" name="txt_razon_social" id="txt_razon_social"/>
                              </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="btn_buscar_proveedor"></label>
                                <button type="button" name="btn_buscar_proveedor" id="btn_buscar_proveedor" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="card shadow mb-12">
                      <div class="card-body bg-light">
                        <div class="container-fluid">
                          <h5 class="card-title"><i class="fas fa-comments"></i> Buscar Motivo</h5>
                          <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_motivo">Id. Motivo</label>
                                <input type="text" class="form-control" name="txt_id_motivo" id="txt_id_motivo"/>
                              </div>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_motivo">Motivo</label>
                                <input type="text" class="form-control" name="txt_motivo" id="txt_motivo"/>
                              </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="btn_buscar_motivo"></label>
                                <button type="button" name="btn_buscar_motivo" id="btn_buscar_motivo" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar Motivo</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_observaciones">Observaciones</label>
                          <textarea class="form-control" id="txt_observaciones" name="txt_observaciones"></textarea>
                        </div>
                      </div>
                    </div>
                    <?php if (!isset($id_egreso)) { ?>
                      <div align="right">
                        <button id="btn_guardar" name="btn_guardar" type="button" class="btn btn-success" style="font-size: 120%;"><i class="fas fa-save"></i> Guardar</button>
                        <button id="btn_limpiar" name="btn_limpiar" type="button" class="btn btn-primary" style="font-size: 120%;"><i class="fas fa-broom"></i> Limpiar</button>
                      </div>
                    <?php } ?>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_buscador" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="tlt_buscador"></h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="txt_origen" id="txt_origen" value="egresos">
              <div id="divContenedorBuscador"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/egresos.js"></script>