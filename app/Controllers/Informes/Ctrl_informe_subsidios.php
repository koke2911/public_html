<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_subsidios;

class Ctrl_informe_subsidios extends BaseController {

  protected $subsidios;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->subsidios = new Md_subsidios();
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_subsidios() {
    $this->validar_sesion();
    echo $this->subsidios->datatable_informe_subsidios($this->db, $this->sesión->id_apr_ses);
  }
}

?>