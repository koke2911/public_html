<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_socios;

class Ctrl_informe_afecto_corte extends BaseController {

  protected $socios;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->socios = new Md_socios();
    $this->sesión = session();
    $this->db     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_afecto_corte($n_meses) {
    $this->validar_sesion();

    $data = $this->socios
     ->select("rol as rol_socio")
     ->select("concat(rut, '-', dv) as rut")
     ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre_socio")
     ->select("afecto_corte(id, id_apr) as meses_pendientes")
     ->select("total_deuda(id, id_apr) as total_deuda")
     ->where("id_apr", $this->sesión->id_apr_ses)
     ->where("afecto_corte(id, id_apr) >=", $n_meses)
     ->findAll();

    $salida = ['data' => $data];

    return json_encode($salida);
  }
}

?>