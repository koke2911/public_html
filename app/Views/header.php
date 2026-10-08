<?php
$sesión = session();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Software APR</title>

  <link rel="icon" href="<?php echo base_url(); ?>/softwareapr-logo-full.png" type="image/png" />

  <link href="<?php echo base_url(); ?>/css/styles.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/css/estilo_extra.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/css/buttons.dataTables.min.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/css/select.dataTables.min.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/css/buttons.bootstrap4.min.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>/css/jquerysctipttop.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>/Multiple-Select/dist/css/bootstrap-multiselect.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>/sweetalert2/sweetalert2.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>/context-menu/context-menu.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>/fileinput-bootstrap/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />

  <link rel="stylesheet"
    href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
    crossorigin="anonymous">

  <link href="<?php echo base_url(); ?>/fileinput-bootstrap/themes/explorer-fas/theme.css"
    media="all"
    rel="stylesheet"
    type="text/css" />

  <link href="<?php echo base_url(); ?>/loader-screen-bar/css/JQLoader.css"
    media="all"
    rel="stylesheet"
    type="text/css" />

  <script src="<?php echo base_url(); ?>/js/all.min.js"></script>

  <style>
    /* Estilos personalizados para los ítems de Rol con Check Verde */
    .role-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 8px 12px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s ease, color 0.2s ease;
      font-size: 13px;
      color: #495057;
      user-select: none;
    }

    .role-item:hover {
      background-color: rgba(0, 123, 255, 0.08);
      color: #007bff;
    }

    .role-item.active {
      background-color: rgba(40, 167, 69, 0.1);
      color: #1e7e34;
      font-weight: 600;
    }

    .role-item .role-check {
      color: #28a745;
      font-size: 14px;
      opacity: 0;
      transition: opacity 0.2s ease, transform 0.2s ease;
      transform: scale(0.8);
    }

    .role-item.active .role-check {
      opacity: 1;
      transform: scale(1);
    }
  </style>

</head>

<body class="sb-nav-fixed">

  <div class="div_sample">

    <input
      type="hidden"
      id="txt_base_url"
      name="txt_base_url"
      value="<?php echo base_url(); ?>">


    <!-- NAVBAR -->
    <nav class="sb-topnav navbar navbar-expand navbar-light apr-topnav">

      <a class="navbar-brand" href="<?php echo base_url(); ?>">
        <img
          src="<?php echo base_url(); ?>/softwareapr-logo-full.png"
          width="150"
          alt="Software APR">
      </a>


      <button
        class="btn btn-link btn-sm order-1 order-lg-0"
        id="sidebarToggle"
        href="#">
        <i class="fas fa-bars"></i>
      </button>


      <!-- Navbar usuario -->
      <ul class="navbar-nav ml-auto mr-0 mr-md-3 my-2 my-md-0">

        <li class="nav-item dropdown">


          <!-- ESCRITORIO -->
          <a
            class="nav-link dropdown-toggle d-none d-sm-none d-md-block"
            id="userDropdown"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            <?php
            echo $sesión->nombres_ses . " " . $sesión->ape_pat_ses;
            ?>

            <i class="fas fa-user fa-fw"></i>
          </a>


          <!-- MÓVIL -->
          <a
            class="nav-link dropdown-toggle d-block d-sm-block d-md-none"
            id="userDropdownMobile"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            <?php
            echo
            substr($sesión->nombres_ses, 0, 1) .
              substr($sesión->ape_pat_ses, 0, 1);
            ?>

            <i class="fas fa-user fa-fw"></i>
          </a>


          <!-- MENÚ DESPLEGABLE DE USUARIO (DROPDOWN) -->
          <div class="dropdown-menu dropdown-menu-right topbar-dropdown p-0 border-0 shadow" style="min-width: 240px;">

            <!-- Cabecera del Menú -->
            <div class="dropdown-head p-3 border-bottom">
              <div class="dropdown-user font-weight-bold text-dark">
                <?php echo htmlspecialchars(($sesión->nombres_ses ?? '') . ' ' . ($sesión->ape_pat_ses ?? '')); ?>
              </div>
              <div class="dropdown-apr text-muted small">
                <i class="fas fa-building mr-1"></i> <?php echo htmlspecialchars($sesión->apr_ses ?? ''); ?>
              </div>
            </div>

            <!-- SECCIÓN DE ROLES CON CHECK VERDE -->
            <div class="px-2 py-2 border-bottom bg-light">
              <div class="px-2 pb-1 small font-weight-bold text-muted text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                <i class="fas fa-user-tag mr-1"></i> Seleccionar Rol
              </div>

              <div class="role-item active" onclick="seleccionarRol(this, 'administrador')">
                <span><i class="fas fa-user-shield mr-2"></i>Administrador</span>
                <i class="fas fa-check-circle role-check"></i>
              </div>

              <div class="role-item" onclick="seleccionarRol(this, 'operador')">
                <span><i class="fas fa-user-cog mr-2"></i>Operador</span>
                <i class="fas fa-check-circle role-check"></i>
              </div>

              <div class="role-item" onclick="seleccionarRol(this, 'tesorero')">
                <span><i class="fas fa-coins mr-2"></i>Tesorero</span>
                <i class="fas fa-check-circle role-check"></i>
              </div>

              <div class="role-item" onclick="seleccionarRol(this, 'directiva')">
                <span><i class="fas fa-users mr-2"></i>Directiva</span>
                <i class="fas fa-check-circle role-check"></i>
              </div>
            </div>

            <!-- Opción Actualizar Clave -->
            <button class="dropdown-item d-flex align-items-center py-2" id="btn_actualizar_clave" type="button">
              <i class="fas fa-key mr-2" style="color: var(--aqua-bright, #00f2fe);"></i>
              <span>Actualizar Clave</span>
            </button>

            <div class="dropdown-divider my-0"></div>

            <!-- Opción Cerrar Sesión -->
            <a class="dropdown-item d-flex align-items-center py-2" href="<?php echo base_url(); ?>/Ctrl_login/logout" style="color: var(--red-alert, #ff4d4d);">
              <i class="fas fa-sign-out-alt mr-2"></i>
              <span>Cerrar Sesión</span>
            </a>

          </div>

        </li>

      </ul>

    </nav>


    <!-- LAYOUT -->
    <div id="layoutSidenav">


      <!-- SIDEBAR -->
      <div id="layoutSidenav_nav">

        <nav
          class="sb-sidenav accordion apr-sidenav"
          id="sidenavAccordion">

          <div class="sb-sidenav-menu">

            <div class="nav">


              <!-- BUSCADOR -->
              <div class="px-3 my-2">

                <input
                  type="text"
                  id="txt_buscar_menu"
                  class="form-control form-control-sm"
                  placeholder="Buscar en el menú...">

              </div>

              <div class="sb-sidenav-menu-heading categoria-menu">
                Dashboard
              </div>

              <!-- DASHBOARD FIJO -->
              <a class="nav-link dashboard-item" href="<?php echo base_url(); ?>">
                <div class="sb-nav-link-icon">
                  <i class="fas fa-th-large"></i>
                </div>

                <span class="nombre-grupo">
                  Dashboard
                </span>
              </a>

              <!-- MENÚ DINÁMICO POR ROL -->
              <div id="menu"></div>


            </div>

          </div>


          <!-- FOOTER SIDEBAR -->
          <div class="sb-sidenav-footer">
            <?php echo $sesión->apr_ses; ?>
          </div>


        </nav>

      </div>
      <script>
        function seleccionarRol(elemento, rol) {
          // Cambiar visualmente la clase activa en el Dropdown
          document.querySelectorAll('.role-item').forEach(item => {
            item.classList.remove('active');
          });

          elemento.classList.add('active');

          // Ejecutar el filtrado sobre la estructura de acordeón nativa
          if (typeof filtrarMenuPorRol === "function") {
            filtrarMenuPorRol(rol);
          }
        }
      </script>


      <!-- Operador : Formularios, Ingreso Lecturas ,Control de Agua, Pagos, Inventario -->
      <!-- Tesorero : Formularios,Pagos,Gestion Financiera ,Informes, R. Humanos -->
      <!-- Direcctiva : Formularios, control de agua, informes -->
      <!-- Adminsitrador: todos los que tenga -->