<?php
$sesión = session();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Software APR</title>
    <link rel="icon" href="<?php echo base_url(); ?>/icon3_html.png" type="image/png"/>
    <link href="<?php echo base_url(); ?>/css/styles.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/estilo_extra.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/select.dataTables.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/buttons.bootstrap4.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>/css/jquerysctipttop.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/Multiple-Select/dist/css/bootstrap-multiselect.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/context-menu/context-menu.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>/fileinput-bootstrap/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    <link href="<?php echo base_url(); ?>/fileinput-bootstrap/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>/loader-screen-bar/css/JQLoader.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>/js/all.min.js"></script>
  </head>

  <body class="sb-nav-fixed">
    <div class="div_sample">
      <input type="hidden" id="txt_base_url" name="txt_base_url" value="<?php echo base_url(); ?>">
      <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/icon3_html.png" width="30"> Software APR</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>

        <!-- Navbar-->
        <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-none d-sm-none d-md-block" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $sesión->nombres_ses . " " . $sesión->ape_pat_ses; ?><i class="fas fa-user fa-fw"></i></a>
            <a class="nav-link dropdown-toggle d-block d-sm-block d-md-none" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo substr($sesión->nombres_ses, 0, 1) . substr($sesión->ape_pat_ses, 0, 1); ?><i class="fas fa-user fa-fw"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="#"><?php echo $sesión->apr_ses; ?></a>
              <a class="dropdown-item" href="#" id="btn_actualizar_clave">Actualizar Clave</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="<?php echo base_url(); ?>/Ctrl_login/logout">Cerrar Sesión</a>
            </div>
          </li>
        </ul>
      </nav>
      <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
          <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
              <div class="nav">
                <div id="menu"></div>
              </div>
            </div>
            <div class="sb-sidenav-footer">
              <?php echo $sesión->apr_ses; ?>
            </div>
          </nav>
        </div>