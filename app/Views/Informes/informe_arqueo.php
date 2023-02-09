<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-file-invoice mr-1"></i> Informe de Arqueo</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card mb-4">
          <div class="card-header" data-toggle="collapse" data-target="#informeArqueo" aria-expanded="false" aria-controls="informeArqueo">
            <i class="fas fa-file-invoice mr-1"></i> Buscar
          </div>
          <div class="card shadow mb-12 collapse" id="informeArqueo">
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
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label class="small mb-1" for="dt_desde">Desde</label>
                        <input type='text' class="form-control" id='dt_desde' name="dt_desde"/>
                      </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label class="small mb-1" for="dt_hasta">Hasta</label>
                        <input type='text' class="form-control" id='dt_hasta' name="dt_hasta"/>
                      </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label class="small mb-1" for="cmb_forma_pago">Forma de Pago</label>
                        <select id="cmb_forma_pago" name="cmb_forma_pago" class="form-control" style="font-size: 150%;">
                          <option value="">Todos</option>
                          <option value="1">Efectivo</option>
                          <option value="2">Tarjeta</option>
                          <option value="3">Transferencia</option>
                          <option value="4">WebPay</option>
                          <option value="5">Cheque</option>
                          <option value="6">Depósito</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="chk_punto_blue" name="chk_punto_blue">
                        <label class="form-check-label" for="chk_punto_blue">Punto Blue</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                      <div class="card shadow mb-12 bg-dark">
                        <div class="card-body bg-light">
                          <center>
                            <button type="button" class="btn btn-success" id="btn_buscar"><i class="fas fa-search"></i> Buscar</button>
                            <button type="button" class="btn btn-primary" id="btn_limpiar"><i class="fas fa-broom"></i> Limpiar Formulario</button>
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
          <div class="card-header" data-toggle="collapse" data-target="#datosHistorialPagos" aria-expanded="false" aria-controls="datosHistorialPagos">
            <i class="fas fa-file-invoice mr-1"></i> Historial de Pagos (Doble click, Ver Detalle)
          </div>
          <div class="card shadow mb-12" id="datosHistorialPagos">
            <div class="card-body">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table id="grid_pagos" class="table table-bordered" width="100%">
                    <thead class="thead-dark">
                      <tr>
                        <th>Usuario</th>
                        <th>ROL Socio</th>
                        <th>Nombre Socio</th>
                        <th>Forma Pago</th>
                        <th>N° Transacción</th>
                        <th>F. Pago</th>
                        <th>Total</th>
                        <th>Subsidio</th>
                        <th>Total Pagado</th>
                        <th>Entregado $</th>
                        <th>Vuelto $</th>
                        <th>Folio Interno</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_arqueo.js"></script>