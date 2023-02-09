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
      <input type="submit" value="Subir" class="btn btn-success">
    </form>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Configuracion/apr_importar_certificado.js"></script>