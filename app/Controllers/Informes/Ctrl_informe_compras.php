<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_compras;

class Ctrl_informe_compras extends BaseController {

  protected $compras;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->compras = new Md_compras();
    $this->sesión  = session();
    $this->db      = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_compras_basico($datosBusqueda) {
    $this->validar_sesion();
    echo $this->compras->datatable_informe_compras_basico($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }

  public function datatable_informe_compras_detallado($datosBusqueda) {
    $this->validar_sesion();
    echo $this->compras->datatable_informe_compras_detallado($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }
}

?>