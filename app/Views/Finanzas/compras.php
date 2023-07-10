<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-shopping-cart"></i> Compras</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-shopping-cart"></i> Datos de la Compra
            </div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_compras" name="form_compras" encType="multipart/form-data">
                    <?php if (isset($id_egreso)) { ?>
                      <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                          <div class="form-group">
                            <label class="small mb-1" for="txt_id_egreso_com">Id. Egreso</label>
                            <input type="text" class="form-control" name="txt_id_egreso_com" id="txt_id_egreso_com" value="<?php echo $id_egreso; ?>"/>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_documento">Tipo de Documento</label>
                          <select id="cmb_tipo_documento" name="cmb_tipo_documento" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_gasto">Tipo de Gasto</label>
                          <select id="cmb_tipo_gasto" name="cmb_tipo_gasto" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_n_documento">N° de Documento</label>
                          <input type="text" class="form-control" name="txt_n_documento" id="txt_n_documento" placeholder="Ingrese el N° de Documento"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_documento">Fecha de Documento</label>
                          <input type="text" class="form-control" name="dt_fecha_documento" id="dt_fecha_documento" placeholder="Ingrese la Fecha del Documento"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_neto">NETO</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1">$</span>
                            </div>
                            <input type="text" class="form-control" name="txt_neto" id="txt_neto" placeholder="Cálculo automático"/>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_iva">IVA</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1">$</span>
                            </div>
                            <input type="text" class="form-control" name="txt_iva" id="txt_iva" placeholder="Cálculo automático"/>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_total">Total</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1">$</span>
                            </div>
                            <input type="text" class="form-control" name="txt_total" id="txt_total" placeholder="Cálculo automático"/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card shadow mb-12">
                      <div class="card-body bg-light">
                        <div class="container-fluid">
                          <h5 class="card-title"><i class="fas fa-industry"></i> Buscar Proveedor</h5>
                          <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_proveedor">Id. Proveedor</label>
                                <input type="text" class="form-control" name="txt_id_proveedor" id="txt_id_proveedor"/>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_rut_proveedor">RUT Proveedor</label>
                                <input type="text" class="form-control" name="txt_rut_proveedor" id="txt_rut_proveedor"/>
                              </div>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_razon_social">Razón Social</label>
                                <div class="input-group">
                                  <input type="text" class="form-control" name="txt_razon_social" id="txt_razon_social"/>
                                  <div class="input-group-append">
                                    <button class="btn btn-outline-dark" type="button" id="btn_buscar_proveedor" name="btn_buscar_proveedor"><i class="fas fa-search"></i> Buscar Proveedor</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                  </form>
                  <div class="card shadow mb-12">
                    <div class="card-body bg-light">
                      <div class="container-fluid">
                        <h5 class="card-title"><i class="fas fa-box-open"></i> Ingresar Productos</h5>
                        <form id="form_producto" name="form_producto" encType="multipart/form-data">
                          <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="cmb_productos">Producto</label>
                                <div class="input-group">
                                  <select id="cmb_productos" name="cmb_productos" class="custom-select"></select>
                                  <div class="input-group-append">
                                    <button class="btn btn-outline-success" type="button" id="btn_agregar_producto" name="btn_agregar_producto" title="Agregar Producto"><i class="fas fa-plus"></i></button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_cantidad">Cantidad</label>
                                <input type="text" class="form-control" name="txt_cantidad" id="txt_cantidad" placeholder="Ingrese la Cantidad"/>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_precio">Precio</label>
                                <input type="text" class="form-control" name="txt_precio" id="txt_precio" placeholder="Ingrese el Precio"/>
                              </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_precio"></label>
                                <button class="btn btn-success form-control" type="button" id="btn_agregar" name="btn_agregar" title="Agregar"><i class="fas fa-plus"></i></button>
                              </div>
                            </div>
                          </div>
                        </form>
                        <div class="table-responsive">
                          <table id="grid_productos_fac" class="table table-bordered" width="100%">
                            <thead class="thead-dark">
                              <tr>
                                <th>Id. Producto</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total Producto</th>
                                <th><i class="fas fa-trash"></i></th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br>
                  <?php if (!isset($id_egreso)) { ?>
                    <div align="right">
                      <button id="btn_guardar" name="btn_guardar" type="button" class="btn btn-success" style="font-size: 120%;"><i class="fas fa-save"></i> Guardar</button>
                      <button id="btn_limpiar" name="btn_limpiar" type="button" class="btn btn-primary" style="font-size: 120%;"><i class="fas fa-broom"></i> Limpiar</button>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="dlg_compras" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="tlt_compras_dlg"></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="txt_origen" id="txt_origen" value="compras">
          <div id="divContenedorDlg"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div id="dlg_productos" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Ingresa un producto nuevo</h4>
        </div>
        <div class="modal-body">
          <div class="container">
            <form id="form_producto_add" name="form_producto_add" encType="multipart/form-data">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_nombre">Nombre</label>
                    <input type='text' class="form-control" id='txt_nombre' name="txt_nombre"/>
                  </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_marca">Marca</label>
                    <input type='text' class="form-control" id='txt_marca' name="txt_marca"/>
                  </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="form-group">
                    <label class="small mb-1" for="txt_modelo">Modelo</label>
                    <input type='text' class="form-control" id='txt_modelo' name="txt_modelo"/>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_guardar_producto" name="btn_guardar_producto">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/compras.js"></script>