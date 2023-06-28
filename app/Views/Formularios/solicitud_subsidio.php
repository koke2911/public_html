<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="far fa-address-card"></i> Solicitud de Subsidios</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>

    <div class="container-fluid">
     
      
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="far fa-address-card"></i> Solicitar Subsidios</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_socios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>id</th>
                          <th>rol</th>
                          <th>nombre_socio</th>
                          <th>Subsidio</th>
                          <th>Estado</th>
                          <th>Solicitar</th>                      
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
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Formularios/solicitud_subsidio.js"></script>