<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-shopping-cart"></i> Informe de Compras</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="basico-tab" data-toggle="tab" href="#basico" role="tab" aria-controls="basico" aria-selected="true">Informe Básico</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="detallado-tab" data-toggle="tab" href="#detallado" role="tab" aria-controls="detallado" aria-selected="false">Informe Detallado</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="basico" role="tabpanel" aria-labelledby="basico-tab">
        <br>
        <div class="container-fluid">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
              <div class="card mb-4">
                <div class="card-header" data-toggle="collapse" data-target="#informeComprasBasico" aria-expanded="false" aria-controls="informeComprasBasico">
                  <i class="fas fa-search"></i> Buscar
                </div>
                <div class="card shadow mb-12 collapse" id="informeComprasBasico">
                  <div class="card-body">
                    <div class="container-fluid">
                      <form id="form_inf_compras_bas" name="form_inf_compras_bas" encType="multipart/form-data">
                        <div class="row">
                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="dt_fecha_desde">Fecha Desde</label>
                              <input type='text' class="form-control" id='dt_fecha_desde' name="dt_fecha_desde" placeholder="Seleccionar fecha desde"/>
                            </div>
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="dt_fecha_hasta">Fecha Hasta</label>
                              <input type='text' class="form-control" id='dt_fecha_hasta' name="dt_fecha_hasta" placeholder="Selecciones fecha hasta"/>
                            </div>
                          </div>
                          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="cmb_tipo_documento">Tipo de documento</label>
                              <select id="cmb_tipo_documento" name="cmb_tipo_documento" class="form-control"></select>
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
                      </form>
                      <br>
                      <div class="card shadow mb-12">
                        <div class="card-body">
                          <div class="container-fluid">
                            <center>
                              <button type="button" name="btn_buscar" id="btn_buscar" class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
                              <button type="button" name="btn_limpiar" id="btn_limpiar" class="btn btn-info"><i class="fas fa-broom"></i> Limpiar</button>
                            </center>
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
                <div class="card-header"><i class="fas fa-shopping-cart"></i> Listado de Compras Básico</div>
                <div class="card shadow mb-12">
                  <div class="card-body">
                    <div class="container-fluid">
                      <div class="table-responsive">
                        <div class="card mb-4">
                          <div class="card-header" data-toggle="collapse" data-target="#buttons" aria-expanded="false" aria-controls="buttons">
                            <i class="fas fa-eye"></i> Ocultar o Mostrar Columnas
                          </div>
                          <div class="card shadow mb-12 collapse" id="buttons">
                            <div class="card-body">
                              <div class="container-fluid">
                                <button class="toggle-vis btn btn-success text-white" data-column="0"><i class="fas fa-eye"></i> N° de Egreso</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="1"><i class="fas fa-eye"></i> Tipo de Documento</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="2"><i class="fas fa-eye"></i> Fecha Documento</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="3"><i class="fas fa-eye"></i> NETO</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="4"><i class="fas fa-eye"></i> IVA</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="5"><i class="fas fa-eye"></i> Monto</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="6"><i class="fas fa-eye"></i> RUT Proveedor</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="7"><i class="fas fa-eye"></i> Nombre Proveedor</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="8"><i class="fas fa-eye"></i> Fecha Reg</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <table id="grid_compras_basico" class="table table-bordered" width="100%">
                          <thead class="thead-dark">
                            <tr>
                              <th>N°. Eg.</th>
                              <th>Tipo de Documento</th>
                              <th>Fecha Documento</th>
                              <th>NETO $</th>
                              <th>IVA $</th>
                              <th>Monto $</th>
                              <th>RUT Proveedor</th>
                              <th>Razón Social</th>
                              <th>Fecha Reg</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </tfoot>
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
      <div class="tab-pane fade" id="detallado" role="tabpanel" aria-labelledby="detallado-tab">
        <br>
        <div class="container-fluid">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
              <div class="card mb-4">
                <div class="card-header" data-toggle="collapse" data-target="#informeComprasDet" aria-expanded="false" aria-controls="informeComprasDet">
                  <i class="fas fa-search"></i> Buscar
                </div>
                <div class="card shadow mb-12 collapse" id="informeComprasDet">
                  <div class="card-body">
                    <div class="container-fluid">
                      <form id="form_inf_compras_det" name="form_inf_compras_det" encType="multipart/form-data">
                        <div class="row">
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="dt_fecha_desde_det">Fecha Desde</label>
                              <input type='text' class="form-control" id='dt_fecha_desde_det' name="dt_fecha_desde_det" placeholder="Seleccionar fecha desde"/>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="dt_fecha_hasta_det">Fecha Hasta</label>
                              <input type='text' class="form-control" id='dt_fecha_hasta_det' name="dt_fecha_hasta_det" placeholder="Selecciones fecha hasta"/>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="cmb_tipo_documento_det">Tipo de documento</label>
                              <select id="cmb_tipo_documento_det" name="cmb_tipo_documento_det" class="form-control"></select>
                            </div>
                          </div>
                          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                              <label class="small mb-1" for="cmb_productos">Producto</label>
                              <select id="cmb_productos" name="cmb_productos" class="form-control"></select>
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
                                    <label class="small mb-1" for="txt_id_proveedor_det">Id. Proveedor</label>
                                    <input type="text" class="form-control" name="txt_id_proveedor_det" id="txt_id_proveedor_det"/>
                                  </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                  <div class="form-group">
                                    <label class="small mb-1" for="txt_rut_proveedor_det">RUT Proveedor</label>
                                    <input type="text" class="form-control" name="txt_rut_proveedor_det" id="txt_rut_proveedor_det"/>
                                  </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                                  <div class="form-group">
                                    <label class="small mb-1" for="txt_razon_social_det">Razón Social</label>
                                    <div class="input-group">
                                      <input type="text" class="form-control" name="txt_razon_social_det" id="txt_razon_social_det"/>
                                      <div class="input-group-append">
                                        <button class="btn btn-outline-dark" type="button" id="btn_buscar_proveedor_det" name="btn_buscar_proveedor_det"><i class="fas fa-search"></i> Buscar Proveedor</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                      <br>
                      <div class="card shadow mb-12">
                        <div class="card-body">
                          <div class="container-fluid">
                            <center>
                              <button type="button" name="btn_buscar_det" id="btn_buscar_det" class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
                              <button type="button" name="btn_limpiar_det" id="btn_limpiar_det" class="btn btn-info"><i class="fas fa-broom"></i> Limpiar</button>
                            </center>
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
                <div class="card-header"><i class="fas fa-shopping-cart"></i> Listado de Compras Detallado</div>
                <div class="card shadow mb-12">
                  <div class="card-body">
                    <div class="container-fluid">
                      <div class="table-responsive">
                        <div class="card mb-4">
                          <div class="card-header" data-toggle="collapse" data-target="#buttons" aria-expanded="false" aria-controls="buttons">
                            <i class="fas fa-eye"></i> Ocultar o Mostrar Columnas
                          </div>
                          <div class="card shadow mb-12 collapse" id="buttons">
                            <div class="card-body">
                              <div class="container-fluid">
                                <button class="toggle-vis btn btn-success text-white" data-column="0"><i class="fas fa-eye"></i> N° de Egreso</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="1"><i class="fas fa-eye"></i> Tipo de Documento</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="2"><i class="fas fa-eye"></i> Fecha Documento</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="3"><i class="fas fa-eye"></i> NETO</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="4"><i class="fas fa-eye"></i> IVA</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="5"><i class="fas fa-eye"></i> Monto</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="6"><i class="fas fa-eye"></i> RUT Proveedor</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="7"><i class="fas fa-eye"></i> Nombre Proveedor</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="8"><i class="fas fa-eye"></i> Producto</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="9"><i class="fas fa-eye"></i> Cantidad</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="10"><i class="fas fa-eye"></i> Precio</button>
                                <button class="toggle-vis btn btn-success text-white" data-column="11"><i class="fas fa-eye"></i> Fecha Reg</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <table id="grid_compras_detallado" class="table table-bordered" width="100%">
                          <thead class="thead-dark">
                            <tr>
                              <th>N°. Eg.</th>
                              <th>Tipo de Documento</th>
                              <th>Fecha Documento</th>
                              <th>NETO $</th>
                              <th>IVA $</th>
                              <th>Monto $</th>
                              <th>RUT Proveedor</th>
                              <th>Razón Social</th>
                              <th>Producto</th>
                              <th>Cantidad</th>
                              <th>Precio</th>
                              <th>Fecha Reg</th>
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </tfoot>
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
  </div>
  <div id="dlg_buscador" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="tlt_buscador"></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="txt_origen" id="txt_origen">
          <div id="divContenedorBuscador"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_compras_basico.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_compras_detallado.js"></script>