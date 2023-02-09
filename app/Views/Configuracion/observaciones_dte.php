<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-comments"></i> Observaciones para Boleta Electrónica</h3>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosObservacion" aria-expanded="false" aria-controls="datosObservacion">
              <i class="fas fa-comments"></i> Datos del Arranque
            </div>
            <div class="card shadow mb-12 collapse" id="datosObservacion">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_observacion" name="form_observacion" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_observacion">Identificador</label>
                          <input type='text' class="form-control" id='txt_id_observacion' name="txt_id_observacion"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_titulo">Título</label>
                          <input type='text' class="form-control" id='txt_titulo' name="txt_titulo"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_observacion">Observación</label>
                          <textarea class="form-control" id="txt_observacion" name="txt_observacion"></textarea>
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
            <div class="card-header"><i class="fas fa-comments"></i> Listado de Arranques</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_observaciones" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Título</th>
                          <th>Observación</th>
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
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Configuracion/observaciones_dte.js"></script>