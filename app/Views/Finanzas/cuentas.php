<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-sort-numeric-up-alt"></i> Cuentas</h3>
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
              <button type="button" name="btn_reciclar" id="btn_reciclar" class="btn btn-warning"><i class="fas fa-recycle"></i> Reciclar</button>
            </center>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosCuenta" aria-expanded="false" aria-controls="datosCuenta">
              <i class="fas fa-sort-numeric-up-alt"></i> Datos de la Cuenta
            </div>
            <div class="card shadow mb-12 collapse" id="datosCuenta">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_cuenta" name="form_cuenta" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_cuenta">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_cuenta" id="txt_id_cuenta"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_banco">Banco</label>
                          <select id="cmb_banco" name="cmb_banco" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_cuenta">Tipo de Cuenta</label>
                          <select id="cmb_tipo_cuenta" name="cmb_tipo_cuenta" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_n_cuenta">N° de Cuenta</label>
                          <input type="text" class="form-control" name="txt_n_cuenta" id="txt_n_cuenta"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rut_cuenta">RUT</label>
                          <input type='text' class="form-control" id='txt_rut_cuenta' name="txt_rut_cuenta"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombre_cuenta">Nombre</label>
                          <input type='text' class="form-control" id='txt_nombre_cuenta' name="txt_nombre_cuenta"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_email_cuenta">Correo Electrónico</label>
                          <input type='text' class="form-control" id='txt_email_cuenta' name="txt_email_cuenta"/>
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
            <div class="card-header"><i class="fas fa-sort-numeric-up-alt"></i> Listado de Cuentas</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_cuentas" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id. Cuenta</th>
                          <th>Id. Banco</th>
                          <th>Banco</th>
                          <th>Id. Tipo de Cuenta</th>
                          <th>Tipo de Cuenta</th>
                          <th>N° de Cuenta</th>
                          <th>RUT</th>
                          <th>Nombre</th>
                          <th>C. Electrónico</th>
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
      <div id="dlg_traza_cuenta" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad de la Cuenta</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaCuenta"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_reciclar_cuenta" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reciclar Cuenta (Doble click, para reciclar)</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorReciclarCuenta"></div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/cuentas.js"></script>