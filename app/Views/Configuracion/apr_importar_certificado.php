<main>
  <div class="container-fluid">
    <div class="row" id="old_certificate" style="display: none;">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card mb-4">
          <div class="card-header"><i class="fas fa-house-user mr-1"></i> Certificado antiguo</div>
          <div class="card shadow mb-12">
            <div class="card-body">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table id="grid_certificate" class="table table-bordered" width="100%">
                    <thead class="thead-dark">
                      <tr>
                        <th>RUT</th>
                        <th>Nombre</th>
                        <th>Emisor</th>
                        <th>Fecha inicio</th>
                        <th>Fecha caducidad</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

   
    <form enctype="multipart/form-data" id="form">
       <center><h2>Importar Certificado</h2></center>
      <div class="form-group">
        <div class="form-group">
          <label for="certificate">Certificado</label>
          <input id="certificate" name="certificate" type="file" class="form-control" accept=".pfx,.p12">
        </div>
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" class="form-control">
      </div>
      <input type="button" value="Subir Certificado" class="btn btn-success" id="btn_importar_certificado">
    </form>

    <hr style="border-color: black; border-width: 3px;">    
      
    <form enctype="multipart/form-data" id="formFolios">
    <center><h2>Importar Folios</h2></center>
      <div class="form-group">
        <div class="form-group">
          <label for="folios">Folios</label>
          <input id="folios" name="folios" type="file" class="form-control" accept=".xml,.txt">
        </div>
      </div>     
      <input type="button" value="Subir Folios" class="btn btn-success" id="btn_importar_folios">

      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-house-user mr-1"></i> Listado de Folios</div>
            <div class="card shadow mb-12">
              <div class="card-body">
                <div class="container-fluid">
                  <div class="table-responsive">
                    <table id="grid_folios" class="table table-bordered" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>id</th>
                          <th>Archivo</th>
                          <th>Desde</th>
                          <th>Hasta</th>
                          <th>Total</th>
                          <th>Fecha Timbraje</th>
                          <th>Disponibles</th>
                          <th>estado</th>
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


    </form>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Configuracion/apr_importar_certificado.js"></script>