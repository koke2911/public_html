<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_ingresos;

class Ctrl_informe_ingresos extends BaseController {

  protected $ingresos;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->ingresos = new Md_ingresos();
    $this->sesión   = session();
    $this->db       = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_ingresos($datosBusqueda) {
    $this->validar_sesion();
    echo $this->ingresos->datatable_informe_ingresos($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }
}

?>