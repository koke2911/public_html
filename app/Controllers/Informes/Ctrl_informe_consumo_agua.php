<?php

namespace App\Controllers\Informes;

use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;

class Ctrl_informe_consumo_agua extends BaseController {

  protected $metros;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->metros = new Md_metros();
    $this->sesión = session();
    $this->db     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_consumo_agua($datosBusqueda) {
    $this->validar_sesion();
    echo $this->metros->datatable_informe_consumo_agua($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }

  public function llenar_grafico_consumo_agua($datosBusqueda) {
    $this->validar_sesion();
    echo $this->metros->llenar_grafico_consumo_agua($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }
}

?>