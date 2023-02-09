<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-exchange-alt"></i> Cambios de Medidor</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
              <button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-primary"><i class="fas fa-edit"></i> Modificar</button>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosCambioMedidor" aria-expanded="false" aria-controls="datosCambioMedidor">
              <i class="fas fa-exchange-alt"></i> Datos del Cambio de Medidor
            </div>
            <div class="card shadow mb-12 collapse" id="datosCambioMedidor">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_cambio_medidor" name="form_cambio_medidor" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_cambio">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_cambio" id="txt_id_cambio"/>
                        </div>
                      </div>
                    </div>
                    <div class="card shadow mb-12">
                      <div class="card-body bg-light">
                        <div class="container-fluid">
                          <h5 class="card-title">Buscar Socio</h5>
                          <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_socio">Id.</label>
                                <input type="text" class="form-control" name="txt_id_socio" id="txt_id_socio"/>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_rut_socio">RUT</label>
                                <input type="text" class="form-control" name="txt_rut_socio" id="txt_rut_socio"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_nombre_socio">Nombre</label>
                                <input type="text" class="form-control" name="txt_nombre_socio" id="txt_nombre_socio"/>
                              </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="btn_buscar_socio"></label>
                                <button type="button" name="btn_buscar_socio" id="btn_buscar_socio" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar</button>
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
                          <h5 class="card-title">Buscar Funcionario</h5>
                          <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_funcionario">Id.</label>
                                <input type="text" class="form-control" name="txt_id_funcionario" id="txt_id_funcionario"/>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_rut_funcionario">RUT</label>
                                <input type="text" class="form-control" name="txt_rut_funcionario" id="txt_rut_funcionario"/>
                              </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_nombre_funcionario">Nombre</label>
                                <input type="text" class="form-control" name="txt_nombre_funcionario" id="txt_nombre_funcionario"/>
                              </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="btn_buscar_funcionario"></label>
                                <button type="button" name="btn_buscar_funcionario" id="btn_buscar_funcionario" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_motivo_cambio">Motivo del Cambio</label>
                          <textarea class="form-control" id="txt_motivo_cambio" name="txt_motivo_cambio"></textarea>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_cambio">Fecha del Cambio</label>
                          <input type='text' class="form-control" id='dt_fecha_cambio' name="dt_fecha_cambio"/>
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
            <div class="card-header"><i class="fas fa-exchange-alt"></i> Listado de Cambios de Medidor</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_cambio_medidor" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Id. Socio</th>
                          <th>RUT Socio</th>
                          <th>ROL Socio</th>
                          <th>Nombre Socio</th>
                          <th>Id. Funcionario</th>
                          <th>RUT Funcionario</th>
                          <th>Nombre Funcionario</th>
                          <th>Motivo del Cambio</th>
                          <th>Fecha Cambio</th>
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
      <div id="dlg_traza_cambio" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Cambio</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaCambio"></div>
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
              <input type="hidden" name="txt_origen" id="txt_origen" value="cambio_medidor">
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Formularios/cambio_medidor.js"></script>