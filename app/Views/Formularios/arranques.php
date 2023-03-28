<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-swimming-pool mr-1"></i> Arranques</h3>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosArranque" aria-expanded="false" aria-controls="datosArranque">
              <i class="fas fa-swimming-pool mr-1"></i> Datos del Arranque
            </div>
            <div class="card shadow mb-12 collapse" id="datosArranque">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_arranque" name="form_arranque" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_arranque">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_arranque" id="txt_id_arranque"/>
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
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_medidor">Número de Medidor</label>
                          <select id="cmb_medidor" name="cmb_medidor" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_sector">Sector</label>
                          <select id="cmb_sector" name="cmb_sector" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tarifa">Tarifa</label>
                          <select id="cmb_tarifa" name="cmb_tarifa" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="col-xs-12">
                          <div class="form-group">
                            <label class="small mb-1" for="rd_alcantarillado">Alcantarillado</label>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="rd_alcantarillado">
                              <label class="btn btn-light" id="lbl_rd_si_a">
                                <input type="radio" name="alcantarillado" id="rd_si_a"> SI
                              </label>
                              <label class="btn btn-light active" id="lbl_rd_no_a">
                                <input type="radio" name="alcantarillado" id="rd_no_a" checked> NO
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="small mb-1" for="txt_alcantarillado">Monto Alcantarillado</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold" id="basic-addon1">$</span>
                              </div>
                              <input type='text' class="form-control" id='txt_alcantarillado' name="txt_alcantarillado"/>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="col-xs-12">
                          <div class="form-group">
                            <label class="small mb-1" for="rd_cuota_socio">Cuota Socio</label>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="rd_cuota_socio">
                              <label class="btn btn-light" id="lbl_rd_si_sc">
                                <input type="radio" name="cuota_socio" id="rd_si_cs"> SI
                              </label>
                              <label class="btn btn-light active" id="lbl_rd_no_sc">
                                <input type="radio" name="cuota_socio" id="rd_no_cs" checked> NO
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="small mb-1" for="txt_cuota_socio">Monto Cuota Socio</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold" id="basic-addon1">$</span>
                              </div>
                              <input type='text' class="form-control" id='txt_cuota_socio' name="txt_cuota_socio"/>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="col-xs-12">
                          <div class="form-group">
                            <label class="small mb-1" for="rd_otros">Otros</label>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="rd_otros">
                              <label class="btn btn-light" id="lbl_rd_si_otros">
                                <input type="radio" name="otros" id="rd_si_otros"> SI
                              </label>
                              <label class="btn btn-light active" id="lbl_rd_no_otros">
                                <input type="radio" name="otros" id="rd_no_otros" checked> NO
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="small mb-1" for="txt_otros">Monto Otros</label>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold" id="basic-addon1">$</span>
                              </div>
                              <input type='text' class="form-control" id='txt_otros' name="txt_otros"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_region">Región</label>
                          <select id="cmb_region" name="cmb_region" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_provincia">Provincia</label>
                          <select id="cmb_provincia" name="cmb_provincia" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_comuna">Comuna</label>
                          <select id="cmb_comuna" name="cmb_comuna" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_calle">Calle</label>
                          <input type='text' class="form-control" id='txt_calle' name="txt_calle"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_numero">Número</label>
                          <input type='text' class="form-control" id='txt_numero' name="txt_numero"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_resto_direccion">Resto Dirección</label>
                          <textarea class="form-control" id="txt_resto_direccion" name="txt_resto_direccion"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_documento">Tipo Documento</label>
                          <select id="cmb_tipo_documento" name="cmb_tipo_documento" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_descuento">Descuento</label>
                          <input type='text' class="form-control" id='txt_descuento' name="txt_descuento"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_razon_social">Razón Social</label>
                          <input type='text' class="form-control" id='txt_razon_social' name="txt_razon_social"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_giro">Giro</label>
                          <input type='text' class="form-control" id='txt_giro' name="txt_giro"/>
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
            <div class="card-header"><i class="fas fa-swimming-pool mr-1"></i> Listado de Arranques</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_arranques" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>id_socio</th>
                          <th>rut_socio</th>
                          <th>Rol</th>
                          <th>Nombre Socio</th>
                          <th>id_medidor</th>
                          <th>N° Medidor</th>
                          <th>id_diametro</th>
                          <th>Diámetro</th>
                          <th>id_sector</th>
                          <th>Sector</th>
                          <th>Alcantarillado</th>
                          <th>Cuota Socio</th>
                          <th>id_region</th>
                          <th>id_provincia</th>
                          <th>id_comuna</th>
                          <th>calle</th>
                          <th>numero</th>
                          <th>resto_direccion</th>
                          <th>id_tipo_documento</th>
                          <th>Descuento</th>
                          <th>Usuarios Reg</th>
                          <th>Fecha</th>
                          <th>Traza</th>
                          <th>Razón Social</th>
                          <th>Giro</th>
                          <th>Otros</th>
                          <th>Monto Alcantarillado</th>
                          <th>Monto Cuota Socio</th>
                          <th>Monto Otros</th>
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
      <div id="dlg_traza_arranque" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Arranque</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaArranque"></div>
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
      <div id="dlg_reciclar_arranque" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reciclar Arranque (Doble click, para reciclar)</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorReciclarArranque"></div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Formularios/arranques.js"></script>