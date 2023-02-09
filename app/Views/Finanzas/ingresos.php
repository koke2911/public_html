<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-sign-in-alt"></i> Ingresos</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
              <button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-primary"><i class="fas fa-edit"></i> Modificar</button>
              <button type="button" name="btn_anular" id="btn_anular" class="btn btn-warning"><i class="fas fa-trash"></i> Anular</button>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosIngreso" aria-expanded="false" aria-controls="datosIngreso">
              <i class="fas fa-sign-in-alt"></i> Datos Ingreso
            </div>
            <div class="card shadow mb-12 collapse" id="datosIngreso">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_ingresos" name="form_ingreso" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_ingreso">N° de Ingreso</label>
                          <input type="text" class="form-control" name="txt_id_ingreso" id="txt_id_ingreso"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_ingreso">Tipo de Ingreso</label>
                          <select id="cmb_tipo_ingreso" name="cmb_tipo_ingreso" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_ingreso">Fecha</label>
                          <input type="text" class="form-control" name="dt_fecha_ingreso" id="dt_fecha_ingreso"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_monto">Monto $</label>
                          <input type="text" class="form-control" name="txt_monto" id="txt_monto"/>
                        </div>
                      </div>
                    </div>
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
            <div class="card-header"><i class="fas fa-sign-in-alt"></i> Listado de Ingresos</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_ingresos" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Monto $</th>
                          <th>Fecha Ingreso</th>
                          <th>Id. Tipo de Ingreso</th>
                          <th>Tipo de Ingreso</th>
                          <th>Tipo Entidad</th>
                          <th>Id. Entidad</th>
                          <th>RUT Entidad</th>
                          <th>Nombre Entidad</th>
                          <th>Id Motivo</th>
                          <th>Motivo</th>
                          <th>Observaciones</th>
                          <th>Usuario</th>
                          <th>Fecha</th>
                          <th width="1%">Traza</th>
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
      <div id="dlg_traza_ingresos" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Ingreso</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaIngreso"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
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
              <input type="hidden" name="txt_origen" id="txt_origen" value="ingresos">
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/ingresos.js"></script>