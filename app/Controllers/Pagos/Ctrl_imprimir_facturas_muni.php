<?php

namespace App\Controllers\Pagos;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_facturas_municipalidad;

class Ctrl_imprimir_facturas_muni extends BaseController {

  protected $facturas_municipalidad;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->facturas_municipalidad = new Md_facturas_municipalidad();
    $this->sesión                 = session();
    $this->db                     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_facturas_muni() {
    $this->validar_sesion();
    define("ACTIVO", 1);

    $data = $this->facturas_municipalidad
     ->select("facturas_municipalidad.folio_factura")
     ->select("date_format(facturas_municipalidad.mes_facturado, '%m-%Y') as mes_facturado")
     ->select("u.usuario")
     ->select("date_format(facturas_municipalidad.fecha, '%d-%m-%Y %H:%m:%s') as fecha")
     ->join("usuarios u", "facturas_municipalidad.id_usuario = u.id")
     ->where("facturas_municipalidad.id_apr", $this->sesión->id_apr_ses)
     ->where("facturas_municipalidad.estado", ACTIVO)
     ->findAll();

    $salida = ['data' => $data];
    echo json_encode($salida);
  }
}

?>