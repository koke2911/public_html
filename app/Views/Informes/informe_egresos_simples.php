<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-sign-out-alt"></i> Informe de Egresos Simples</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <br>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#informeEgresosSimples" aria-expanded="false" aria-controls="informeEgresosSimples">
              <i class="fas fa-search"></i> Buscar
            </div>
            <div class="card shadow mb-12 collapse" id="informeEgresosSimples">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_inf_egresos" name="form_inf_egresos" encType="multipart/form-data">
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
                          <label class="small mb-1" for="txt_id_cuenta">Cuenta</label>
                          <div class="input-group">
                            <input type='text' class="form-control" id='txt_id_cuenta' name="txt_id_cuenta"/>
                            <div class="input-group-append">
                              <button class="btn btn-outline-dark" type="button" id="btn_buscar_cuenta" name="btn_buscar_cuenta"><i class="fas fa-search"></i> Buscar Cuenta</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_egreso">Tipo de Egreso</label>
                          <select id="cmb_tipo_egreso" name="cmb_tipo_egreso" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_tipo_entidad">Tipo Entidad</label>
                          <select id="cmb_tipo_entidad" name="cmb_tipo_entidad" class="form-control">
                            <option value="">Seleccione tipo de entidad</option>
                            <option value="Proveedor">Proveedor</option>
                            <option value="Funcionario">Directivo - Funcionario</option>
                            <option value="Socio">Socio</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_entidad">Entidad</label>
                          <div class="input-group">
                            <input type='text' class="form-control" id='txt_id_entidad' name="txt_id_entidad"/>
                            <div class="input-group-append">
                              <button class="btn btn-outline-dark" type="button" id="btn_buscar_entidad" name="btn_buscar_entidad"><i class="fas fa-search"></i> Buscar Entidad</button>
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
            <div class="card-header"><i class="fas fa-sign-out-alt"></i> Listado de Egresos Simples</div>
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
                            <button class="toggle-vis btn btn-success text-white" data-column="1"><i class="fas fa-eye"></i> Monto</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="2"><i class="fas fa-eye"></i> Fecha Documento</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="3"><i class="fas fa-eye"></i> Tipo de
                              Egreso
                            </button>
                            <button class="toggle-vis btn btn-success text-white" data-column="4"><i class="fas fa-eye"></i> Banco</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="5"><i class="fas fa-eye"></i> Tipo Cuenta</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="6"><i class="fas fa-eye"></i> N° Cuenta</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="7"><i class="fas fa-eye"></i> RUT Cuenta</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="8"><i class="fas fa-eye"></i> Nombre Cuenta</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="9"><i class="fas fa-eye"></i> N° Cheque Trans.</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="10"><i class="fas fa-eye"></i> Tipo de Entidad</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="11"><i class="fas fa-eye"></i> RUT Entidad</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="12"><i class="fas fa-eye"></i> Nombre Entidad</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="13"><i class="fas fa-eye"></i> Motivo</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="14"><i class="fas fa-eye"></i> Observaciones</button>
                            <button class="toggle-vis btn btn-success text-white" data-column="15"><i class="fas fa-eye"></i> Fecha Registro</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <table id="grid_egresos" class="table table-bordered" width="100%">
                    <thead class="thead-dark">
                      <tr>
                        <th>N°. Eg.</th>
                        <th>Monto $</th>
                        <th>Fecha Documento</th>
                        <th>Tipo de Egreso</th>
                        <th>Banco</th>
                        <th>Tipo Cuenta</th>
                        <th>N° Cuenta</th>
                        <th>RUT Cuenta</th>
                        <th>Nombre Cuenta</th>
                        <th>N° Cheque Trans</th>
                        <th>Tipo Entidad</th>
                        <th>RUT Entidad</th>
                        <th>Nombre Entidad</th>
                        <th>Motivo</th>
                        <th>Observaciones</th>
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
          <input type="hidden" name="txt_origen" id="txt_origen" value="informe_egresos">
          <div id="divContenedorBuscador"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_egresos.js"></script>