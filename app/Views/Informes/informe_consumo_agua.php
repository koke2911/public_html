<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-water"></i> Informe de Consumo del Agua</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#informeConsumoAgua" aria-expanded="false" aria-controls="informeConsumoAgua">
              <i class="fas fa-search"></i> Buscar
            </div>
            <div class="card shadow mb-12 collapse" id="informeConsumoAgua">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_inf_consumo" name="form_inf_consumo" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_desde">Fecha Desde</label>
                          <input type='text' class="form-control" id='dt_fecha_desde' name="dt_fecha_desde" placeholder="Seleccionar fecha desde"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_hasta">Fecha Hasta</label>
                          <input type='text' class="form-control" id='dt_fecha_hasta' name="dt_fecha_hasta" placeholder="Selecciones fecha hasta"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_sectores">Sectores</label>
                          <select id="cmb_sectores" name="cmb_sectores" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
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
                          <h5 class="card-title"><i class="fas fa-industry"></i> Buscar Socio</h5>
                          <div class="row">
                            <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_id_socio">Id. Socio</label>
                                <input type="text" class="form-control" name="txt_id_socio" id="txt_id_socio"/>
                              </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_rut_socio">RUT Socio</label>
                                <input type="text" class="form-control" name="txt_rut_socio" id="txt_rut_socio"/>
                              </div>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label class="small mb-1" for="txt_nombre_socio">Nombre Socio</label>
                                <div class="input-group">
                                  <input type="text" class="form-control" name="txt_nombre_socio" id="txt_nombre_socio"/>
                                  <div class="input-group-append">
                                    <button class="btn btn-outline-dark" type="button" id="btn_buscar_socio" name="btn_buscar_socio"><i class="fas fa-search"></i> Buscar Socio</button>
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
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="grid-tab" data-toggle="tab" href="#grid" role="tab" aria-controls="grid" aria-selected="true">Listado</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="grafico-tab" data-toggle="tab" href="#grafico" role="tab" aria-controls="grafico" aria-selected="false">Gráfico</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="grid" role="tabpanel" aria-labelledby="grid-tab">
          <br>
          <div class="container-fluid">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card mb-4">
                  <div class="card-header"><i class="fas fa-water"></i> Resultados de Búsqueda</div>
                  <div class="card shadow mb-12">
                    <div class="card-body">
                      <div class="container-fluid">
                        <div class="table-responsive">
                          <table id="grid_inf_consumo_agua" class="table table-bordered" width="100%">
                            <thead class="thead-dark">
                              <tr>
                                <th>Folio</th>
                                <th>RUT Socio</th>
                                <th>ROL Socio</th>
                                <th>Nombre Socio</th>
                                <th>Consumo de Agua</th>
                                <th>U.M.</th>
                                <th>Sector</th>
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
        <div class="tab-pane fade show" id="grafico" role="tabpanel" aria-labelledby="grafico-tab">
          <br>
          <div class="container-fluid">
            <div class="row">
              <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12"></div>
              <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <button type="button" id="btn_imprimir_grafico" name="btn_imprimir_grafico" class="btn btn-info"><i class="fas fa-print"></i> Imprimir</button>
                <div class="card mb-4">
                  <div class="card-header">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Consumo de Agua por Sector
                  </div>
                  <div class="card-body">
                    <canvas id="graf_consumo" width="100%" height="40"></canvas>
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
          <input type="hidden" name="txt_origen" id="txt_origen" value="informe_consumo_agua">
          <div id="divContenedorBuscador"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_consumo_agua.js"></script>
