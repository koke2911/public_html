<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-building"></i> Informe Municipal de Subsidios</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card mb-4">
          <div class="card-header" data-toggle="collapse" data-target="#informeMunicipal" aria-expanded="false" aria-controls="informeMunicipal">
            <i class="fas fa-building"></i> Buscar
          </div>
          <div class="card shadow mb-12 collapse" id="informeMunicipal">
            <div class="card-body">
              <div class="container-fluid">
                <form id="form_histPagos" name="form_histPagos" encType="multipart/form-data">
                  <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                      <div class="form-group">
                        <label class="small mb-1" for="dt_mes_consumo">Mes Consumo</label>
                        <div class="input-group">
                          <input type='text' class="form-control" id='dt_mes_consumo' name="dt_mes_consumo"/>
                          <div class="input-group-append">
                            <button type="button" id="btn_emitir_factura" name="btn_emitir_factura" class="btn btn-primary"><i class="fas fa-receipt"></i> Emitir Factura</button>
                          </div>
                        </div>
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
          <div class="card-header" data-toggle="collapse" data-target="#listaMunicipal" aria-expanded="false" aria-controls="listaMunicipal">
            <i class="fas fa-building"></i> Subsidios
          </div>
          <div class="card shadow mb-12" id="listaMunicipal">
            <div class="card-body">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table id="grid_subsidios" class="table table-bordered" width="100%">
                    <thead class="thead-dark">
                      <tr>
                        <th>Folio Mt.</th>
                        <th>RUT Socio</th>
                        <th>Nombre Socio</th>
                        <th>Mes Cubierto</th>
                        <th>50%</th>
                        <th>100%</th>
                        <th>Subsidio</th>
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
    <div id="dlg_buscador" class="modal fade" role="dialog">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="tlt_buscador"></h4>
          </div>
          <div class="modal-body">
            <input type="hidden" name="txt_origen" id="txt_origen" value="informe_municipalidad_subsidios">
            <div id="divContenedorDlg"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_municipalidad_subsidios.js"></script>