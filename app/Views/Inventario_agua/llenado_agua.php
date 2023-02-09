<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-water"></i> Llenado de Copa</h3>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosLlenadoAgua" aria-expanded="false" aria-controls="datosLlenadoAgua">
              <i class="fas fa-water"></i> Datos Motivo
            </div>
            <div class="card shadow mb-12 collapse" id="datosLlenadoAgua">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_llenado" name="form_llenado" encType="multipart/form-data">

                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_llenado">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_llenado" id="txt_id_llenado"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_hora">Fecha - Hora</label>
                          <input type='text' class="form-control" id='dt_fecha_hora' name="dt_fecha_hora"/>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_operador">Id. Operador</label>
                          <input type='text' class="form-control" id='txt_id_operador' name="txt_id_operador"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rut_operador">RUT Operador</label>
                          <input type='text' class="form-control" id='txt_rut_operador' name="txt_rut_operador"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombre_operador">Nombre Operador</label>
                          <input type='text' class="form-control" id='txt_nombre_operador' name="txt_nombre_operador"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="btn_buscar_operador"></label>
                          <button type="button" name="btn_buscar_operador" id="btn_buscar_operador" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cantidad_agua">Cantidad de Agua</label>
                          <input type='text' class="form-control" id='txt_cantidad_agua' name="txt_cantidad_agua"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_um_agua">Unidad de Medida</label>
                          <select id="cmb_um_agua" name="cmb_um_agua" class="form-control">
                            <option value="1">Metros Cúbicos</option>
                            <option value="2">Litros</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cantidad_cloro">Cantidad de Cloro</label>
                          <input type='text' class="form-control" id='txt_cantidad_cloro' name="txt_cantidad_cloro"/>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_um_cloro">Unidad de Medida</label>
                          <select id="cmb_um_cloro" name="cmb_um_cloro" class="form-control">
                            <option value="1">Gramos</option>
                            <option value="2">Kilos</option>
                            <option value="3">Litros</option>
                          </select>
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
            <div class="card-header"><i class="fas fa-water"></i> Listado</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_llenado" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Fecha - Hora</th>
                          <th>Id. Operador</th>
                          <th>RUT Operador</th>
                          <th>Operador</th>
                          <th>Cantidad Agua</th>
                          <th>Id. U. M.</th>
                          <th>U. M.</th>
                          <th>Cantidad Cloro</th>
                          <th>Id. U. M.</th>
                          <th>U. M.</th>
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
      <div id="dlg_traza_llenado" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Llenado de Agua</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaLlenado"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
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
              <input type="hidden" name="txt_origen" id="txt_origen" value="llenado_agua">
              <div id="divContenedorBuscador"></div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Inventario_agua/llenado_agua.js"></script>