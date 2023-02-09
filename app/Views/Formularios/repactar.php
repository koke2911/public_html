<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-divide"></i> Repactar Deuda</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
              <button type="button" name="btn_eliminar" id="btn_eliminar" class="btn btn-primary"><i class="fas fa-trash"></i> Eliminar</button>
              <button type="button" name="btn_aceptar" id="btn_aceptar" class="btn btn-success"><i class="fas fa-check"></i> Aceptar</button>
              <button type="button" name="btn_cancelar" id="btn_cancelar" class="btn btn-danger"><i class="fas fa-ban"></i> Cancelar</button>
            </center>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosRepactar" aria-expanded="false" aria-controls="datosRepactar">
              <i class="fas fa-divide"></i> Repactar Deuda
            </div>
            <div class="card shadow mb-12 collapse" id="datosRepactar">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_repactar" name="form_repactar" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_repactacion">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_repactacion" id="txt_id_repactacion"/>
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
                  </form>
                  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title"><i class="fas fa-mouse-pointer"></i> Seleccione Deuda a Repactar</h5>
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
                      </div>
                    </div>
                  </div>
                  <form id="form_repactar2" name="form_repactar2" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_deuda_vigente">Deuda Vigente</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="deuda_vigente">$</span>
                            </div>
                            <input type='text' class="form-control" id='txt_deuda_vigente' name="txt_deuda_vigente" aria-describedby="deuda_vigente" value="0"/>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_n_cuotas">Número de Cuotas</label>
                          <input type='number' class="form-control" id='txt_n_cuotas' name="txt_n_cuotas" min="0" value="0"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_valor_cuota">Valor Cuota</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="valor_cuota">$</span>
                            </div>
                            <input type='text' class="form-control" id='txt_valor_cuota' name="txt_valor_cuota" aria-describedby="valor_cuota" value="0"/>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_pago">Fecha Comienza a Pagar</label>
                          <input type='text' class="form-control" id='dt_fecha_pago' name="dt_fecha_pago" placeholder="Seleccione un mes"/>
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
            <div class="card-header"><i class="fas fa-divide"></i> Listado de Repactaciones (Doble click, para ver el detalle)</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_repactaciones" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>id_socio</th>
                          <th>rut_socio</th>
                          <th>rol_socio</th>
                          <th>Nombre Socio</th>
                          <th>Deuda Completa</th>
                          <th>N° Cuotas</th>
                          <th>Comienza a Pagar</th>
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
      <div id="dlg_traza" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad de la Repactación</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTraza"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
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
              <input type="hidden" name="txt_origen" id="txt_origen" value="repactacion">
              <div id="divContenedorBuscador"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_detalle" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Detalle de la Repactación</h4>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table id="grid_repactacion_detalle" class="table table-bordered" width="100%">
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
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Formularios/repactar.js"></script>