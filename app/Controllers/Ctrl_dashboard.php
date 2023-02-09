<?php

namespace App\Controllers;

use App\Models\Formularios\Md_socios;

class Ctrl_dashboard extends BaseController {

  protected $sesión;
  protected $socios;
  protected $db;

  public function __construct() {
    $this->sesión = session();
    $this->socios = new Md_socios();
    $this->db     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function llenar_grafico_socios() {
    $this->validar_sesion();
    echo $this->socios->llenar_grafico_socios($this->db, $this->sesión->id_apr_ses);
  }
}

?>