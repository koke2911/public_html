<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-square"></i> Informe de Cuadratura del Agua</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#informeCuadraturaAgua" aria-expanded="false" aria-controls="informeCuadraturaAgua">
              <i class="fas fa-search"></i> Buscar
            </div>
            <div class="card shadow mb-12 collapse" id="informeCuadraturaAgua">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_inf_cuadratura" name="form_inf_cuadratura" encType="multipart/form-data">
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
          <button type="button" id="btn_imprimir_grafico" name="btn_imprimir_grafico" class="btn btn-info"><i class="fas fa-print"></i> Imprimir</button>
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-chart-bar mr-1"></i>
              Cuadratura del Agua
            </div>
            <div class="card-body" id="divCuadratura">
              <h3 align="center">Cuadratura del Agua</h3>
              <canvas id="graf_consumo" width="100%" height="40"></canvas>
              <div class="container-fluid">
                <div class="card mb-4">
                  <div class="card-body bg-light">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_llenado">Llenado</label>
                          <input type='text' style="font-size: 150%;" class="form-control bg-info text-white" id='txt_llenado' name="txt_llenado"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_consumo">Consumo</label>
                          <input type='text' style="font-size: 150%;" class="form-control bg-info text-white" id='txt_consumo' name="txt_consumo"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_diferencia">Diferecia</label>
                          <input type='text' style="font-size: 150%;" class="form-control bg-success text-white" id='txt_diferencia' name="txt_diferencia"/>
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
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_cuadratura_agua.js"></script>
