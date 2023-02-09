<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Inventario_agua\Md_llenado_agua;

class Ctrl_informe_llenado_agua extends BaseController {

  protected $llenado_agua;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->llenado_agua = new Md_llenado_agua();
    $this->sesión       = session();
    $this->db           = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_llenado_agua($datosBusqueda) {
    $this->validar_sesion();
    echo $this->llenado_agua->datatable_informe_llenado_agua($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }
}

?>