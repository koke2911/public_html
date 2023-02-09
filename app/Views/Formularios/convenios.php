<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-handshake mr-1"></i> Convenios</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
              <button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-primary d-none"><i class="fas fa-edit"></i> Modificar</button>
              <button type="button" name="btn_eliminar" id="btn_eliminar" class="btn btn-primary"><i class="fas fa-trash"></i> Eliminar</button>
              <button type="button" name="btn_aceptar" id="btn_aceptar" class="btn btn-success"><i class="fas fa-check"></i> Aceptar</button>
              <button type="button" name="btn_cancelar" id="btn_cancelar" class="btn btn-danger"><i class="fas fa-ban"></i> Cancelar</button>
              <button type="button" name="btn_reciclar" id="btn_reciclar" class="btn btn-warning d-none"><i class="fas fa-recycle"></i> Reciclar</button>
              <button type="button" name="btn_repactar" id="btn_repactar" class="btn btn-secondary d-none"><i class="fas fa-divide"></i> Repactar Deuda</button>
            </center>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosConvenio" aria-expanded="false" aria-controls="datosConvenio">
              <i class="fas fa-handshake mr-1"></i> Datos del Convenio
            </div>
            <div class="card shadow mb-12 collapse" id="datosConvenio">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_convenio" name="form_convenio" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_convenio">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_convenio" id="txt_id_convenio"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_socio">Id. Socio</label>
                          <input type='text' class="form-control" id='txt_id_socio' name="txt_id_socio"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-2 col-md-6 col-sm-12">
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
                          <label class="small mb-1" for="txt_nombre_socio">Nombre Socio</label>
                          <input type='text' class="form-control" id='txt_nombre_socio' name="txt_nombre_socio"/>
                        </div>
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="btn_buscar_socio"></label>
                          <button type="button" name="btn_buscar_socio" id="btn_buscar_socio" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar Socio</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_servicios">Servicios</label>
                          <select id="cmb_servicios" name="cmb_servicios" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_detalles">Detalles del Servicio</label>
                          <textarea class="form-control" id="txt_detalles" name="txt_detalles"></textarea>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_servicio">Fecha Servicio</label>
                          <input type='text' class="form-control" id='dt_fecha_servicio' name="dt_fecha_servicio"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_n_cuotas">Número de Cuotas</label>
                          <input type='text' class="form-control" id='txt_n_cuotas' name="txt_n_cuotas"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_pago">Fecha Comienza a Pagar</label>
                          <input type='text' class="form-control" id='dt_fecha_pago' name="dt_fecha_pago"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_costo">Costo del Servicio</label>
                          <input type='text' class="form-control" id='txt_costo' name="txt_costo"/>
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
            <div class="card-header"><i class="fas fa-handshake mr-1"></i> Listado de Convenios (Doble click, para ver el detalle)</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_convenios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>id_socio</th>
                          <th>rut_socio</th>
                          <th>rol_socio</th>
                          <th>Nombre Socio</th>
                          <th>id_servicio</th>
                          <th>Servicio</th>
                          <th>detalle_servicio</th>
                          <th>Fecha Servicio</th>
                          <th>N° Cuotas</th>
                          <th>Comienza a Pagar</th>
                          <th>Costo Servicio</th>
                          <th>Usuarios Reg</th>
                          <th>Fecha</th>
                          <th>Traza</th>
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
      <div id="dlg_traza_convenio" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Convenio</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaConvenio"></div>
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
      <div id="dlg_reciclar_convenios" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reciclar Convenio (Doble click, para reciclar)</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorReciclarConvenios"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_detalle_convenio" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Detalle del Convenio</h4>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table id="grid_convenio_detalle" class="table table-bordered" width="100%">
                  <thead class="thead-dark">
                    <tr>
                      <th width="0%">id_cuota_socio</th>
                      <th width="40%">Socio</th>
                      <th width="20%">Fecha Pago</th>
                      <th width="20%">N° Cuota</th>
                      <th width="20%">Valor Cuota</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_repactar" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Repactar Convenio</h4>
            </div>
            <div class="modal-body">
              <div class="card shadow mb-12">
                <div class="card-body">
                  <div class="container-fluid">
                    <div class="alert alerta-fijo hidden" role="alert" id="alerta_repactacion"></div>
                    <form id="form_repactar" name="form_repactar" encType="multipart/form-data">
                      <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                          <div class="form-group">
                            <label class="small mb-1" for="txt_deuda_vigente">Deuda Vigente</label>
                            <input type='text' class="form-control" id='txt_deuda_vigente' name="txt_deuda_vigente"/>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                          <div class="form-group">
                            <label class="small mb-1" for="txt_n_cuotas_repact">N° de Cuotas</label>
                            <input type='number' class="form-control" id='txt_n_cuotas_repact' name="txt_n_cuotas_repact" min="0"/>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                          <div class="form-group">
                            <label class="small mb-1" for="dt_fecha_pago_repac">Mes de Inicio</label>
                            <input type='text' class="form-control" id='dt_fecha_pago_repac' name="dt_fecha_pago_repac"/>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                          <div class="form-group">
                            <label class="small mb-1" for="btn_aceptar_repactar"></label>
                            <button type="button" name="btn_aceptar_repactar" id="btn_aceptar_repactar" class="btn btn-success form-control"><i class="fas fa-save"></i> Aceptar</button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <br>
              <div class="table-responsive">
                <table id="grid_repactar" class="table table-bordered" width="100%">
                  <thead class="thead-dark">
                    <tr>
                      <th width="0%">id_cuota_socio</th>
                      <th width="40%">Socio</th>
                      <th width="20%">Fecha Pago</th>
                      <th width="20%">N° Cuota</th>
                      <th width="20%">Valor Cuota</th>
                      <th width="20%">Pagada</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Formularios/convenios.js"></script>