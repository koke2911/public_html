<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-sign-out-alt"></i> Informe de Inventario</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <br>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header" data-toggle="collapse" data-target="#informeInventario" aria-expanded="false" aria-controls="informeInventario">
              <i class="fas fa-search"></i> Buscar
            </div>
            <div class="card shadow mb-12 collapse" id="informeInventario">
              <div class="card-body">
                <div class="container-fluid">
                  <form id="form_inf_inventario" name="form_inf_inventario" encType="multipart/form-data">
                    <div class="row">
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_productos">Productos</label>
                          <select id="cmb_productos" name="cmb_productos" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_estado">Estado</label>
                          <select id="cmb_estado" name="cmb_estado" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="cmb_ubicacion">Ubicación</label>
                          <select id="cmb_ubicacion" name="cmb_ubicacion" class="form-control"></select>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="small mb-1" for="txt_codigo_barras">Código de Barras</label>
                          <input type='text' class="form-control" id='txt_codigo_barras' name="txt_codigo_barras"/>
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
            <div class="card-header"><i class="fas fa-sign-out-alt"></i> Listado de Productos</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_inventario" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>Id. Producto</th>
                          <th>Producto</th>
                          <th>Marca</th>
                          <th>Modelo</th>
                          <th>Código Barras</th>
                          <th>Estado</th>
                          <th>Ubicación</th>
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
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Informes/informe_inventario.js"></script>