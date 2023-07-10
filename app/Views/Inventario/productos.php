<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-box-open"></i> Productos</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
              <button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-primary"><i class="fas fa-edit"></i> Modificar</button>
              <button type="button" name="btn_desactivar" id="btn_desactivar" class="btn btn-primary"><i class="fas fa-trash"></i> Desactivar</button>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosProducto" aria-expanded="false" aria-controls="datosProducto">
              <i class="fas fa-box-open"></i> Datos del Producto
            </div>
            <div class="card shadow mb-12 collapse" id="datosProducto">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_producto" name="form_producto" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_producto">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_producto" id="txt_id_producto"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombre">Nombre</label>
                          <input type='text' class="form-control" id='txt_nombre' name="txt_nombre"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_marca">Marca</label>
                          <input type='text' class="form-control" id='txt_marca' name="txt_marca"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_modelo">Modelo</label>
                          <input type='text' class="form-control" id='txt_modelo' name="txt_modelo"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cantidad">Cantidad</label>
                          <input type='text' class="form-control" id='txt_cantidad' name="txt_cantidad"/>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="card shadow mb-12">
                    <div class="card-body bg-light">
                      <div class="container-fluid">
                        <h5 class="card-title"><i class="fas fa-box-open"></i> Unidades</h5>
                        <div class="row">
                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="txt_id_detalle">N° Serie o Cód Barra</label>
                              <input type="text" class="form-control" name="txt_id_detalle" id="txt_id_detalle"/>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="cmb_estado">Estado</label>
                              <select id="cmb_estado" name="cmb_estado" class="form-control"></select>
                            </div>
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="cmb_ubicacion">Ubicación</label>
                              <select id="cmb_ubicacion" name="cmb_ubicacion" class="form-control"></select>
                            </div>
                          </div>
                          <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="btn_modificar_detalle"></label>
                              <button type="button" id="btn_modificar_detalle" name="btn_modificar_detalle" class="form-control btn btn-success"><i class="fas fa-pencil-alt"></i></button>
                            </div>
                          </div>
                        </div>
                        <div class="table-responsive">
                          <table id="grid_productos_detalles" class="table table-bordered" width="100%">
                            <thead class="thead-dark">
                              <tr>
                                <th>Id. Detalle</th>
                                <th>Código de Barras</th>
                                <th>Id Estado</th>
                                <th>Estado</th>
                                <th>Id Ubicacion</th>
                                <th>Ubicación</th>
                                <th width="1%"><i class="fas fa-trash"></i></th>
                                <th width="1%"><i class="fas fa-barcode"></i></th>
                                <th width="1%"><i class="fas fa-shoe-prints"></i></th>
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
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-box-open"></i> Rebaja de productos</div>
            <div class="card shadow mb-12">
              <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_cantidad_r">Cantidad</label>
                    <input type="text" class="form-control" name="txt_cantidad_r" id="txt_cantidad_r" placeholder="Ingrese la Cantidad"/>
                  </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="cmb_productos">Producto</label>
                    <div class="input-group">
                      <select id="cmb_productos" name="cmb_productos" class="custom-select"></select>
                      <div class="input-group-append">
                        <button class="btn btn-outline-danger" type="button" id="btn_agregar_producto" name="btn_agregar_producto" title="Agregar Producto"><i class="fas fa-minus"></i></button>
                      </div>
                    </div>
                  </div>
                </div>

            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-box-open"></i> Listado de Productos</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_productos" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Nombre</th>
                          <th>Marca</th>
                          <th>Modelo</th>
                          <th>Cantidad</th>
                          <th>Estado</th>
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
      <div id="dlg_traza_productos" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Producto</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaProductos"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_codigo_barra" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Código de Barras</h4>
            </div>
            <div class="modal-body">
              <div class="card shadow mb-12">
                <div class="card-body bg-light">
                  <button type="button" name="btn_codigo_barra" id="btn_codigo_barra" class="btn btn-info"><i class="fas fa-print"></i> Imprimir Código Barra</button>
                </div>
              </div>
              <br>
              <div class="card shadow mb-12">
                <div class="card-body bg-light">
                  <div class="container-fluid">
                    <h5 class="card-title"><i class="fas fa-barcode"></i> Código de Barras</h5>
                    <canvas id="divCodigoBarras" align="center"></canvas>
                  </div>
                </div>
              </div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Inventario/productos.js"></script>