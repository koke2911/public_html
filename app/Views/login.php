<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Gestion APR</title>
    <link rel="icon" href="<?php echo base_url(); ?>/icon_html.png" type="image/png"/>
    <link href="<?php echo base_url(); ?>/css/styles.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/estilo_extra.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/login.css" rel="stylesheet"/>
    <script src="<?php echo base_url(); ?>/js/all.min.js" crossorigin="anonymous"></script>
  </head>
  <body class="bg-primary">
    <input type="hidden" name="txt_base_url" id="txt_base_url" value="<?php echo base_url(); ?>">
    <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
        <main>
          <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                  <div class="card-header"><h3 class="text-center font-weight-light my-4">Iniciar Sesión</h3></div>
                  <div class="card-body">
                    <form id="form_login">
                      <div class="form-group">
                        <label class="small mb-1" for="txt_usuario">Usuario</label>
                        <input class="form-control py-4" id="txt_usuario" name="txt_usuario" placeholder="Ingresar usuario"/>
                      </div>
                      <div class="form-group" id="divClave">
                        <label class="small mb-1" for="txt_clave">Clave</label>
                        <input class="form-control py-4" id="txt_clave" name="txt_clave" type="password" placeholder="Ingresar clave"/>
                      </div>
                      <div class="form-group hidden" id="divClaveActivar">
                        <label class="small mb-1" for="txt_clave_activar">Ingrese su clave</label>
                        <input class="form-control py-4" id="txt_clave_activar" name="txt_clave_activar" type="password" placeholder="Ingresar clave para activar"/>
                      </div>
                      <div class="form-group hidden" id="divClaveRepetir">
                        <label class="small mb-1" for="txt_clave_repetir">Repita su clave</label>
                        <input class="form-control py-4" id="txt_clave_repetir" name="txt_clave_repetir" type="password" placeholder="Ingresar clave"/>
                      </div>
                      <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small" href=""></a>
                        <button class="btn btn-primary" type="button" id="btn_login"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
      <div id="layoutAuthentication_footer">
        <footer class="py-4 bg-light mt-auto">
          <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between small">
              <div class="text-muted">Copyright &copy; Info Integral y Corretaje. LTDA 2021</div>
              <div>
                <a href="https://medidorinteligente.cl" target="_blank">Medidor inteligente</a> &middot; <a href="https://puntoblue.cl/" target="_blank">Punto blue</a>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <script src="<?php echo base_url(); ?>/js/jquery-3.5.1.slim.min.js"></script>
    <script src="<?php echo base_url(); ?>/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>/jquery-validation-1.19.2/dist/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>/js/alerta.js"></script>
    <script src="<?php echo base_url(); ?>/js/login.js" type="text/javascript"></script>
  </body>
</html>
