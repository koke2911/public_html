<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_arranques;

class Ctrl_informe_arranques extends BaseController {

  protected $arranques;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->arranques = new Md_arranques();
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_arranques() {
    $this->validar_sesion();
    echo $this->arranques->datatable_informe_arranques($this->db, $this->sesión->id_apr_ses);
  }
}

?>