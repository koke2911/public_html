<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-swimming-pool mr-1"></i> Desinfeccion Diaria</h3>
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
            <div class="card-header" data-toggle="collapse" data-target="#datosDesinfeccion" aria-expanded="false" aria-controls="datosDesinfeccion">
              <i class="fas fa-swimming-pool mr-1"></i> Datos de desinfeccion
            </div>
            <div class="card shadow mb-12 collapse" id="datosDesinfeccion">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_desinfeccion" name="form_desinfeccion" encType="multipart/form-data">
                     <div class="row">
                      <div class="col-xl-1 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_id_desinfeccion">ID</label>
                          <input type='text' class="form-control" id='txt_id_desinfeccion' name="txt_id_desinfeccion"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_fecha_dia">Dia</label>
                              <input type='text' class="form-control" id='dt_fecha_dia' name="dt_fecha_dia"/>
                        </div>
                      </div>
                    </div>
                    <h><strong>PLANTA AGUA POTABLE</h></strong><BR>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_hora_ap">Hora</label>
                          <input type='text' class="form-control" id='txt_hora_ap' name="txt_hora_ap"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_cloro_ap">Cloro Residual MG/L</label>
                              <input type='text' class="form-control" id='txt_cloro_ap' name="txt_cloro_ap"/>
                        </div>
                      </div>
                    </div>
                    <strong>RED DISTRIBUCION MUESTRA N°1 </strong><BR>
                    <div class="row">

                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">

                          <label class="small mb-1" for="txt_id_socio1">Id. Socio</label>
                          <input type='text' class="form-control" id='txt_id_socio1' name="txt_id_socio1"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rut_socio1">RUT Socio</label>
                          <input type='text' class="form-control" id='txt_rut_socio1' name="txt_rut_socio1"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rol1">ROL Socio</label>
                          <input type='text' class="form-control" id='txt_rol1' name="txt_rol"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombre_socio1">Nombre Socio</label>
                          <input type='text' class="form-control" id='txt_nombre_socio1' name="txt_nombre_socio1"/>
                        </div>
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="btn_buscar_socio1"></label>
                          <button type="button" name="btn_buscar_socio1" id="btn_buscar_socio1" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar Socio</button>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-1 col-md-6 col-sm-12">
                       <label class="small mb-1" for="dt_hora_socio1">Hora</label>
                          <input type='text' class="form-control" id='dt_hora_socio1' name="dt_hora_socio1"/>

                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                       <label class="small mb-1" for="txt_cloro_socio1">Cloro Residual MG/L</label>
                          <input type='text' class="form-control" id='txt_cloro_socio1' name="txt_cloro_socio1"/>
                          
                      </div>
                    </div>
                    <BR><strong>RED DISTRIBUCION MUESTRA N°2 </strong><BR>
                    <div class="row">

                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">

                          <label class="small mb-1" for="txt_id_socio2">Id. Socio</label>
                          <input type='text' class="form-control" id='txt_id_socio2' name="txt_id_socio2"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rut_socio2">RUT Socio</label>
                          <input type='text' class="form-control" id='txt_rut_socio2' name="txt_rut_socio2"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_rol2">ROL Socio</label>
                          <input type='text' class="form-control" id='txt_rol2' name="txt_rol2"/>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_nombre_socio2">Nombre Socio</label>
                          <input type='text' class="form-control" id='txt_nombre_socio2' name="txt_nombre_socio2"/>
                        </div>
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="btn_buscar_socio2"></label>
                          <button type="button" name="btn_buscar_socio2" id="btn_buscar_socio2" class="btn btn-dark form-control"><i class="fas fa-search"></i> Buscar Socio</button>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-1 col-md-6 col-sm-12">
                       <label class="small mb-1" for="dt_hora_socio2">Hora</label>
                          <input type='text' class="form-control" id='dt_hora_socio2' name="dt_hora_socio2"/>

                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                       <label class="small mb-1" for="txt_cloro_socio2">Cloro Residual MG/L</label>
                          <input type='text' class="form-control" id='txt_cloro_socio2' name="txt_cloro_socio2"/>
                          
                      </div>
                    </div>
                    <BR><strong>DOSIFICADOR CLORADOR</strong><BR>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_frecuencia">FREC</label>
                          <input type='text' class="form-control" id='txt_frecuencia' name="txt_frecuencia"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_desp">DESP</label>
                          <input type='text' class="form-control" id='txt_desp' name="txt_desp"/>
                        </div>
                      </div>                      
                    </div>
                    <div class="row">
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_medidor_caudal">Medidor Caudal. M3</label>
                          <input type='text' class="form-control" id='txt_medidor_caudal' name="txt_medidor_caudal"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_electricidad">Medidor Electrici. KW</label>
                          <input type='text' class="form-control" id='txt_electricidad' name="txt_electricidad"/>
                        </div>
                      </div> 
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_horometro">Horometro.</label>
                          <input type='text' class="form-control" id='txt_horometro' name="txt_horometro"/>
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
            <div class="card-header">
              <i class="fas fa-file-excel"></i> Generar Reporte Mensual
            </div>
            <div class="card shadow mb-12 " id="datorReporte">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_desinfeccion" name="form_desinfeccion" encType="multipart/form-data">
                     <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="dt_mes_export">Mes</label>
                          <input type='text' class="form-control" id='dt_mes_export' name="dt_mes_export"/>
                        </div>
                      </div>
                      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
                        <div class="form-group">
                              <label class="small mb-1" for="btn_export">Exportar</label>
                              <button type="button" name="btn_export" id="btn_export" class="btn btn-success"><i class="fas fa-file-excel">Excel</i></button>
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
            <div class="card-header"><i class="fas fa-swimming-pool mr-1"></i> Listado de Desinfecciones</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_desinfecciones" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id.</th>
                          <th>Dia</th>
                          <th>Planta_hora</th>
                          <th>Planta_cloro</th>
                          <th>Socio M1</th>
                          <th>Hora M1</th>
                          <th>C. Residual M1</th>
                          <th>Socio Muestra 2</th>
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
                          <th>calle</th>
                          <th>calle</th>
                          <th>calle</th>
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
      <!-- <div id="dlg_reciclar_arranque" class="modal fade" role="dialog">
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
      </div> -->
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Formularios/desinfeccion.js"></script>