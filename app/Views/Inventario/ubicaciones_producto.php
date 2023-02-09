<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-location-arrow"></i> Ubicaciones del Producto</h3>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosUbicacionProducto" aria-expanded="false" aria-controls="datosUbicacionProducto">
              <i class="fas fa-location-arrow"></i> Datos Ubicaciones del Producto
            </div>
            <div class="card shadow mb-12 collapse" id="datosUbicacionProducto">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_ubicacion" name="form_ubicacion" encType="multipart/form-data">

                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_ubicacion">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_ubicacion" id="txt_id_ubicacion"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_ubicacion">Ubicación</label>
                          <input type='text' class="form-control" id='txt_ubicacion' name="txt_ubicacion"/>
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
            <div class="card-header"><i class="fas fa-location-arrow"></i> Listado de Ubicaciones del Producto</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_ubicaciones" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Ubicación</th>
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
      <div id="dlg_traza_ubicacion" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad de la Ubicación del Producto</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaUbicacion"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_reciclar_ubicacion" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reciclar Ubicaciones del Producto</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorReciclarUbicacion"></div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Inventario/ubicaciones_producto.js"></script>