<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center">Multas</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-user-plus"></i> Nueva</button>
              <button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-primary"><i class="fas fa-user-edit"></i> Modificar</button>
              <button type="button" name="btn_bloquear" id="btn_bloquear" class="btn btn-primary"><i class="fas fa-user-lock"></i> Eliminar</button>
            </center>
          </div>
        </div>
      </div>
      <br>

      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user mr-1"></i> Listado de Multas</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_multas" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>id_socio</th>
                          <th>Nombres</th>
                          <th>Apellito paterno</th>
                          <th>Apellido materno</th>
                          <th>Glosa multa</th>
                          <th>Monto</th>
                          <th>Tipo</th>
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

      <div id="dlg_nuevo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nueva multa</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-male mr-1"></i> Listado de Socios</div>
                    <div class="card shadow mb-12">
                      <div class="card-body">
                        <div class="container-fluid">
                          <div class="table-responsive">
                            <table id="grid_socios" class="table table-bordered" width="100%">
                              <thead class="thead-dark">
                                <tr>
                                  <th>id_socio</th>
                                  <th>RUT</th>
                                  <th>ROL</th>
                                  <th>nombres</th>
                                  <th>ape_pat</th>
                                  <th>ape_mat</th>
                                  <th>Nombre Completo</th>
                                  <th>Fecha Entrada</th>
                                  <th>Fecha Nacimiento</th>
                                  <th>id_sexo</th>
                                  <th>id_region</th>
                                  <th>id_provincia</th>
                                  <th>id_comuna</th>
                                  <th>Comuna</th>
                                  <th>Calle</th>
                                  <th>Número</th>
                                  <th>resto_direccion</th>
                                  <th>Usuarios Reg</th>
                                  <th>Fecha</th>
                                  <th>Traza</th>
                                  <th>ruta</th>
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
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/multas.js"></script>