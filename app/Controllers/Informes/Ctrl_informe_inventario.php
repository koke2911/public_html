<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Inventario\Md_productos;

class Ctrl_informe_inventario extends BaseController {

  protected $productos;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->productos = new Md_productos();
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_inventario($datosBusqueda) {
    $this->validar_sesion();
    echo $this->productos->datatable_informe_inventario($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }
}

?>