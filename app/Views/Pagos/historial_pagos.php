<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-file-invoice mr-1"></i> Historial de Pagos</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card mb-4">
          <div class="card-header" data-toggle="collapse" data-target="#datosBusquedaHistorialPagos" aria-expanded="false" aria-controls="datosBusquedaHistorialPagos">
            <i class="fas fa-file-invoice mr-1"></i> Buscar
          </div>
          <div class="card shadow mb-12 collapse" id="datosBusquedaHistorialPagos">
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
                        <label class="small mb-1" for="dt_desde">Desde</label>
                        <input type='text' class="form-control" id='dt_desde' name="dt_desde"/>
                      </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label class="small mb-1" for="dt_hasta">Hasta</label>
                        <input type='text' class="form-control" id='dt_hasta' name="dt_hasta"/>
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
                        <th>id_caja</th>
                        <th>Pagado $</th>
                        <th>Entregado $</th>
                        <th>Vuelto $</th>
                        <th>Forma Pago</th>
                        <th>N° Transacción</th>
                        <th>ROL Socio</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Imp.</th>
                        <th>Anular</th>
                        <th>Traza</th>
                        <th>Nombre Socio</th>
                        <th>Descuento</th>
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
  <div id="dlg_caja_detalle" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detalle del Pago</h4>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="table-responsive">
              <table id="grid_deuda" class="table table-bordered" width="100%">
                <thead class="thead-dark">
                  <tr>
                    <th>id_metros</th>
                    <th>Deuda $</th>
                    <th>Fecha Vencimiento</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <div id="dlg_traza_pagos" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Trazabilidad de Pagos</h4>
        </div>
        <div class="modal-body">
          <div id="divContenedorTrazaPagos"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Pagos/historial_pagos.js"></script>