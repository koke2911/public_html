<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_egresos_simples;

class Ctrl_informe_egresos extends BaseController {

  protected $egresos_simples;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->egresos_simples = new Md_egresos_simples();
    $this->sesión          = session();
    $this->db              = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_egresos($datosBusqueda) {
    $this->validar_sesion();
    echo $this->egresos_simples->datatable_informe_egresos($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }
}

?>