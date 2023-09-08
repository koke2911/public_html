
<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-cash-register mr-1"></i> Caja Express</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <div class="row">
              
              <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                <div class="form-group">
                  <label class="small mb-1" for="txt_rut_socio">RUT Socio(Solo los primeros digitos)</label>
                  <input type='text' class="form-control" id='txt_rut_socio' name="txt_rut_socio"/>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div class="form-group">
                  <label class="small mb-1" for="btn_buscar_deuda"></label>
                  <button type="button" name="btn_buscar_deuda" id="btn_buscar_deuda" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar deuda</button>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div class="form-group">
                  <input type='hidden' class="form-control" id='txt_id_socio' name="txt_id_socio"/>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <div class="form-group">
                  <input type='hidden' class="form-control" id='txt_rol' name="txt_rol"/>
                </div>
              </div>
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                
              </div>
            </div>
            <div class="row">
              <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <div class="form-group">
                  <input type='hidden' class="form-control" id='txt_nombre_socio' name="txt_nombre_socio"/>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="form-group">
                  <input type='hidden' class="form-control" id='txt_abono' name="txt_abono"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_deuda" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>id_metros</th>
                          <th>Rol</th>
                          <th>Deuda $</th>
                          <th>Fecha Vencimiento</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                      <div class="form-group">
                        <label class="small mb-1" for="txt_descuento" style="font-size: 150%;">Descuento</label>
                        <div class="input-group mb-1">
                          <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold" id="basic-addon1">$</span>
                          </div>
                          <input type='text' class="form-control bg-info text-white" id='txt_descuento' name="txt_descuento" style="font-size: 150%;"/>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                      <div class="form-group">
                        <label class="small mb-1" for="txt_total_pagar" style="font-size: 150%;">Total a Pagar</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold" id="basic-addon1">$</span>
                          </div>
                          <input type='text' class="form-control bg-warning text-dark" id='txt_total_pagar' name="txt_total_pagar" style="font-size: 150%;"/>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <div class="row">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <label class="small mb-1" for="cmb_forma_pago" style="font-size: 150%;">Forma de Pago</label>
                      <select id="cmb_forma_pago" name="cmb_forma_pago" class="form-control" style="font-size: 150%;">
                        <option value="1">Efectivo</option>
                        <option value="2">Tarjeta</option>
                        <option value="3">Transferencia</option>
                        <option value="5">Cheque</option>
                        <option value="6">Depósito</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <!-- <label class="small mb-1" for="txt_n_transaccion" style="font-size: 150%;">N° de Transacción</label> -->
                      <input type='hidden' class="form-control bg-light text-dark" id='txt_n_transaccion' name="txt_n_transaccion" style="font-size: 150%;"/>
                    </div>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <label class="small mb-1" for="txt_entregado" style="font-size: 150%;">Entregado</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text font-weight-bold" id="basic-addon1">$</span>
                        </div>
                        <input type='text' class="form-control bg-secondary text-white" id='txt_entregado' name="txt_entregado" style="font-size: 150%;"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <label class="small mb-1" for="txt_vuelto" style="font-size: 150%;">Vuelto</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text font-weight-bold" id="basic-addon1">$</span>
                        </div>
                        <input type='text' class="form-control bg-info text-white" id='txt_vuelto' name="txt_vuelto" style="font-size: 150%;"/>
                      </div>
                    </div>
                  </div>
                </div>
                <br><br>
                <div class="row">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                      <button type="button" name="btn_pagar" id="btn_pagar" class="btn btn-dark form-control" style="font-size: 150%;"><i class="fas fa-money-bill-wave"></i> Pagar</button>
                    </div>
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
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Pagos/caja_expres.js"></script>