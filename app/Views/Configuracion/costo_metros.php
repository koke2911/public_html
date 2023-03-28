<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-dollar-sign mr-1"></i> Costo por Metros</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_nuevo" id="btn_nuevo" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo</button>
              <button type="button" name="btn_modificar" id="btn_modificar" class="btn btn-primary"><i class="fas fa-plus"></i> Modificar</button>
              <button type="button" name="btn_eliminar" id="btn_eliminar" class="btn btn-primary"><i class="fas fa-trash"></i> Eliminar</button>
              <button type="button" name="btn_aceptar" id="btn_aceptar" class="btn btn-success"><i class="fas fa-check"></i> Aceptar</button>
              <button type="button" name="btn_cancelar" id="btn_cancelar" class="btn btn-danger"><i class="fas fa-ban"></i> Cancelar</button>
            </center>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-xl-42 col-4g-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-dollar-sign mr-1"></i> Datos de Costo</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_costo_metros" name="form_costo_metros" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_costo_metros">Identificador</label>
                          <input type='text' class="form-control" id="txt_id_costo_metros" name="txt_id_costo_metros"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_apr">APR</label>
                          <select id="cmb_apr" name="cmb_apr" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tarifa">Tarifa</label>
                          <select id="cmb_tarifa" name="cmb_tarifa" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_diametro">Diámetro del Medidor</label>
                          <select id="cmb_diametro" name="cmb_diametro" class="form-control"></select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <input type='hidden' class="form-control" id="txt_id_cargo_fijo" name="txt_id_cargo_fijo"/>
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cantidad_cargo_fijo">Cantidad de Metros Cargo Fijo</label>
                          <input type='text' class="form-control" id="txt_cantidad_cargo_fijo" name="txt_cantidad_cargo_fijo"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cargo_fijo">Cargo Fijo $</label>
                          <input type='text' class="form-control" id="txt_cargo_fijo" name="txt_cargo_fijo"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_desde">Desde</label>
                          <input type='text' class="form-control" id="txt_desde" name="txt_desde"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_hasta">Hasta</label>
                          <input type='text' class="form-control" id='txt_hasta' name="txt_hasta"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_costo">Costo $</label>
                          <input type='text' class="form-control" id='txt_costo' name="txt_costo"/>
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
            <div class="card-header"><i class="fas fa-dollar-sign mr-1"></i> Listado de Costos por APR</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_costo_metros" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th width="0%">id_costo_metros</th>
                          <th width="0%">id_apr</th>
                          <th width="20%">APR</th>
                          <th width="0%">id_diametro</th>
                          <th width="10%">Diámetro</th>
                          <th width="10%">Desde</th>
                          <th width="10%">Hasta</th>
                          <th width="15%">Costo $</th>
                          <th width="10%">Usuarios Reg</th>
                          <th width="15%">Fecha</th>
                          <th width="10%">Traza</th>
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
      <div id="dlg_traza_costo_metros" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Costo</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaCostoMetros"></div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Configuracion/costo_metros.js"></script>