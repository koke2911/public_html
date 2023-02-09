<?php

namespace App\Controllers\Informes;

use App\Models\Pagos\Md_caja;
use App\Controllers\BaseController;

class Ctrl_informe_mensual extends BaseController {

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

  public function datatable_informe_mensual($mes_consumo) {
    $this->validar_sesion();
    if ($mes_consumo == 0) {
      $mes_consumo = date("m-Y");
    }
    echo $this->caja->datatable_informe_mensual($this->db, $this->sesión->id_apr_ses, $mes_consumo);
  }
}

?>