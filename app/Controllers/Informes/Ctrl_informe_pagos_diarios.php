<?php

namespace App\Controllers\Informes;

use App\Models\Pagos\Md_caja;
use App\Controllers\BaseController;

class Ctrl_informe_pagos_diarios extends BaseController {

  protected $caja;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->caja   = new Md_caja();
    $this->sesión = session();
    $this->db     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_pagos_diarios($fecha) {
    $this->validar_sesion();
    echo $this->caja->datatable_informe_pagos_diarios($this->db, $this->sesión->id_apr_ses,$fecha);
  }
}

?>