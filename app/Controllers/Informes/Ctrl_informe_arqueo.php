<?php

namespace App\Controllers\Informes;

use App\Models\Pagos\Md_caja;
use App\Controllers\BaseController;

class Ctrl_informe_arqueo extends BaseController {

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

  public function datatable_informe_arqueo($datosBusqueda) {
    $this->validar_sesion();
    define("ACTIVO", 1);
    $datosBusqueda = json_decode($datosBusqueda, TRUE);

    $id_socio      = $datosBusqueda["id_socio"];
    $desde         = date_format(date_create($datosBusqueda["desde"] . "00:00"), 'Y-m-d H:i');
    $hasta         = date_format(date_create($datosBusqueda["hasta"] . "23:59"), 'Y-m-d H:i');
    $id_forma_pago = $datosBusqueda["id_forma_pago"];
    $punto_blue    = $datosBusqueda["punto_blue"];

    $this->caja
     ->select("caja.id as id_caja")
     ->select("s.rol as rol_socio")
     ->select("concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio")
     ->select("fp.glosa as forma_pago")
     ->select("ifnull(caja.numero_transaccion, 'No Registrado') as n_transaccion")
     ->select("caja.fecha_pago as f_transaccion")
     ->select("date_format(caja.fecha, '%d-%m-%Y %H:%m:%s') as fecha_pago")
     ->select("m.monto_facturable as total")
     ->select("m.monto_subsidio")
     ->select("m.total_mes as pagado")
     ->select("caja.entregado")
     ->select("caja.vuelto")
     ->select("u.usuario")

     ->join("caja_detalle cd", "cd.id_caja = caja.id")
     ->join("metros m", "cd.id_metros = m.id")
     ->join("socios s", "caja.id_socio = s.id")
     ->join("forma_pago fp", "caja.id_forma_pago = fp.id")
     ->join("usuarios u", "caja.id_usuario = u.id")
     ->where("caja.id_apr", $this->sesión->id_apr_ses)
     ->where("caja.estado", ACTIVO);

    if ($id_socio != "") {
      $this->caja->where("caja.id_socio", $id_socio);
    }

    if ($desde != "" && $hasta != "") {
      $this->caja->where("caja.fecha between '$desde' and '$hasta'");
    }

    if ($id_forma_pago != "") {
      $this->caja->where("caja.id_forma_pago", $id_forma_pago);
    }

    if ($punto_blue != "") {
      $this->caja->where("u.punto_blue", $punto_blue);
    }

    $data = $this->caja->findAll();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>