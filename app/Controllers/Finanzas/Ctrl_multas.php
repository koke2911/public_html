<?php

namespace App\Controllers\Finanzas;

use Config\Database;
use App\Models\Finanzas\Md_multas;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;

class Ctrl_multas extends BaseController {

  protected $apr;
  protected $multas;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->apr    = new Md_apr();
    $this->multas = new Md_multas();
    $this->sesión = session();
    $this->db     = Database::connect();
  }

  public function datatable_multas($db) {
    $consulta = "SELECT f.id, f.id_socio, f.monto, f.tipo, f.glosa, s.nombres, s.ape_pat, s.ape_mat
						from 
							files f
							inner join socios s on s.id = f.id_socio
							WHERE f.status = 1";

    $query = $db->query($consulta);
    $apr   = $query->getResultArray();

    foreach ($apr as $key) {
      $row = [
       "id"       => $key["id"],
       "id_socio" => $key["id_socio"],
       "monto"    => $key["monto"],
       "tipo"     => $key["tipo"],
       "glosa"    => $key["glosa"],
       "nombres"  => $key["nombres"],
       "ape_pat"  => $key["ape_pat"],
       "ape_mat"  => $key["ape_mat"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      return "{ \"data\": [] }";
    }
  }
}