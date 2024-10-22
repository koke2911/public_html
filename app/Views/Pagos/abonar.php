<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-wallet"></i> Abonos</h3>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosAbono" aria-expanded="false" aria-controls="datosAbono">
              <i class="fas fa-wallet"></i> Datos del Abono
            </div>
            <div class="card shadow mb-12 collapse" id="datosAbono">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_abono" name="form_abono" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_abono">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_abono" id="txt_id_abono"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_abono">Abono</label>
                          <input type="text" class="form-control" name="txt_abono" id="txt_abono"/>
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
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-wallet"></i> Listado de Abonos (Doble click, para ver el detalle)</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_abonos" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>id_socio</th>
                          <th>rut_socio</th>
                          <th>rol_socio</th>
                          <th>Nombre Socio</th>
                          <th>Abono</th>
                          <th>Usuario</th>
                          <th>Fecha</th>
                          <th>Traza</th>
                          <th>Boucher</th>
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
  <div id="dlg_traza" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Trazabilidad del Abono</h4>
        </div>
        <div class="modal-body">
          <div id="divContenedorTrazaAbono"></div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Pagos/abonos.js"></script>