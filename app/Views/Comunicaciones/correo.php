<main>
  <div class="container-fluid">
        <h3 class="mt-4" align="center"><i class="fas fa-envelope mr-1"></i> Correo</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
      <br>
        <div class="card shadow mb-12">
            <div class="card-body">
                <div class="container-fluid">
                    <center>
                        <button type="button" name="btn_enviar" id="btn_enviar" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
                    </center>
                 </div>
            </div>
        </div>
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#enviados" aria-expanded="false" aria-controls="enviados">
              <i class="fas fa-male mr-1"></i> Enviados
            </div>
            <div class="card shadow mb-12 collapse" id="enviados">
                <div class="card-body">
                    <div class="container-fluid">                 
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table id="grid_enviados" class="table table-bordered" width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                        <th>ID</th>
                                        <th>Fecha Envio</th>
                                        <th>Asunto</th>
                                        <th>Cuerpo</th>
                                        <th>Usuario</th>
                                        <th>Enviado a </th>                                                                                
                                        </tr>
                                    </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          </div>  
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#datosSocio" aria-expanded="false" aria-controls="datosSocio">
              <i class="fas fa-male mr-1"></i> Enviar Nuevo Correo
            </div>
            <div class="card shadow mb-12" id="datosSocio">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_socios" name="form_socios" encType="multipart/form-data">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="small mb-1" for="txt_asunto">Asunto</label>
                                <input type="text" class="form-control" name="txt_asunto" id="txt_asunto"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label class="small mb-1" for="txt_cuerpo">Cuerpo</label>
                                <textarea style="height: 200px;" class="form-control" name="txt_cuerpo" id="txt_cuerpo"></textarea>
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
      <br>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-male mr-1"></i> Listado de Socios con Email</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_socios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>id_socio</th>
                          <th>RUT</th>
                          <th>ROL</th>
                          <th>nombres</th>
                          <th>ape_pat</th>
                          <th>ape_mat</th>
                          <th>Nombre Completo</th>
                          <th>Fecha Entrada</th>
                          <th>Fecha Nacimiento</th>
                          <th>Email</th>
                          <th>id_region</th>
                          <th>id_provincia</th>
                          <th>id_comuna</th>
                          <th>Comuna</th>
                          <th>Calle</th>
                          <th>Número</th>
                          <th>resto_direccion</th>
                          <th>Usuarios Reg</th>
                          <th>Fecha</th>
                          <th>Traza</th>
                          <th>ruta</th>
                          <th>Cert.</th>
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
    </div>
  </div>
  <div id="dlg_traza_socio" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Detalle de enviados</h4>
            </div>
            <div class="modal-body">
              <table id="grid_detalle" class="table table-bordered" width="100%">
                <thead class="thead-dark">
                    <tr>
                    <th>id Socio</th>
                    <th>Socio</th>                                                                                                 
                    </tr>
                  </thead>
                </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Comunicaciones/correo.js"></script>