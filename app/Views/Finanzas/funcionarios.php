<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-briefcase"></i> Directivos - Funcionarios</h3>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosFuncionario" aria-expanded="false" aria-controls="datosFuncionario">
              <i class="fas fa-briefcase"></i> Datos del Directivo - Funcionario
            </div>
            <div class="card shadow mb-12 collapse" id="datosFuncionario">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_funcionario" name="form_funcionario" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_funcionario">Identificador</label>
                          <input type="text" class="form-control" name="txt_id_funcionario" id="txt_id_funcionario"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rut">RUT</label>
                          <input type='text' class="form-control" id='txt_rut' name="txt_rut"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_sexo">Sexo</label>
                          <select id="cmb_sexo" name="cmb_sexo" class="form-control">
                            <option value="">Seleccionar sexo</option>
                            <option value="1">Masculino</option>
                            <option value="2">Femenino</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombres">Nombre</label>
                          <input type='text' class="form-control" id='txt_nombres' name="txt_nombres"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_ape_pat">Apellido Paterno</label>
                          <input type='text' class="form-control" id='txt_ape_pat' name="txt_ape_pat"/>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_ape_mat">Apellido Materno</label>
                          <input type='text' class="form-control" id='txt_ape_mat' name="txt_ape_mat"/>
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
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_prevision">Prevision (FONASA o ISAPRE)</label>
                          <input class="form-control" id="txt_prevision" name="txt_prevision"></input>
                        </div>
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_prevision_porcen">% o UF</label>
                          <input class="form-control" id="txt_prevision_porcen" name="txt_prevision_porcen"></input>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_afp">AFP</label>
                          <input class="form-control" id="txt_afp" name="txt_afp"></input>
                        </div>
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_afp_porcent">% AFP</label>
                          <input class="form-control" id="txt_afp_porcent" name="txt_afp_porcent"></input>
                        </div>
                      </div>
                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_sueldo_bruto">Sueldo Bruto</label>
                          <input class="form-control" id="txt_sueldo_bruto" name="txt_sueldo_bruto"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_contrato">Fecha Contrato</label>
                          <input class="form-control" id="dt_contrato" name="dt_contrato"></input>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_jornada">Jornada (Dias)</label>
                          <input class="form-control" id="txt_jornada" name="txt_jornada"></input>
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
            <div class="card-header"><i class="fas fa-briefcase"></i> Listado de Directivos - Funcionarios</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_funcionarios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id</th>
                          <th>RUT</th>
                          <th>Nombres</th>
                          <th>Ap. Paterno</th>
                          <th>Ap. Materno</th>
                          <th>Nombre Completo</th>
                          <th>Id Sexo</th>
                          <th>Id Region</th>
                          <th>Id Provincia</th>
                          <th>Id Comuna</th>
                          <th>Comuna</th>
                          <th>Calle</th>
                          <th>Número</th>
                          <th>Resto Dirección</th>
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
      <div id="dlg_traza_funcionario" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Trazabilidad del Funcionario</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorTrazaFuncionario"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div id="dlg_reciclar_funcionario" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reciclar Motivos</h4>
            </div>
            <div class="modal-body">
              <div id="divContenedorReciclarFuncionario"></div>
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/funcionarios.js"></script>