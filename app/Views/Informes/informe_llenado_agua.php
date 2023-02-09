<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-water"></i> Informe de Llenado de Agua</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#informeLlenadoAgua" aria-expanded="false" aria-controls="informeLlenadoAgua">
              <i class="fas fa-search"></i> Buscar
            </div>
            <div class="card shadow mb-12 collapse" id="informeLlenadoAgua">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_inf_llenado" name="form_inf_llenado" encType="multipart/form-data">
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
                          <label class="small mb-1" for="cmb_conversion">Conversión del agua</label>
                          <select id="cmb_conversion" name="cmb_conversion" class="form-control">
                            <option value="1">Metros Cúbicos</option>
                            <option value="2">Litros</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="card shadow mb-12">
                      <div class="card-body bg-light">
                        <div class="container-fluid">
                          <h5 class="card-title"><i class="fas fa-industry"></i> Buscar Operador</h5>
                          <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_operador">Id. Operador</label>
                                <input type="text" class="form-control" name="txt_id_operador" id="txt_id_operador"/>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_rut_operador">RUT Operador</label>
                                <input type="text" class="form-control" name="txt_rut_operador" id="txt_rut_operador"/>
                              </div>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_nombre_operador">Nombre Operador</label>
                                <div class="input-group">
                                  <input type="text" class="form-control" name="txt_nombre_operador" id="txt_nombre_operador"/>
                                  <div class="input-group-append">
                                    <button class="btn btn-outline-dark" type="button" id="btn_buscar_operador" name="btn_buscar_operador"><i class="fas fa-search"></i> Buscar Operador</button>
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
            <div class="card-header"><i class="fas fa-water"></i> Resultados de Búsqueda</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_inf_llenado_agua" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Folio</th>
                          <th>Fecha - Hora</th>
                          <th>RUT Operador</th>
                          <th>Operador</th>
                          <th>Cantidad Agua</th>
                          <th>U.M.</th>
                          <th>Cantidad Cloro</th>
                          <th>U.M.</th>
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
  <div id="dlg_buscador" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="tlt_buscador"></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="txt_origen" id="txt_origen" value="informe_llenado_agua">
          <div id="divContenedorBuscador"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_llenado_agua.js"></script>
