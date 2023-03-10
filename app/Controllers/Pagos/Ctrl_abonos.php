<?php
namespace App\Controllers\Pagos;

use App\Models\Pagos\Md_abonos;
use App\Controllers\BaseController;
use App\Models\Pagos\Md_abonos_traza;
use App\Models\Formularios\Md_socios;
use App\Models\Formularios\Md_socios_traza;

class Ctrl_abonos extends BaseController {

  protected $abonos;
  protected $abonos_traza;
  protected $socios;
  protected $socios_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->abonos       = new Md_abonos();
    $this->abonos_traza = new Md_abonos_traza();
    $this->socios       = new Md_socios();
    $this->socios_traza = new Md_socios_traza();
    $this->sesión       = session();
    $this->db           = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_abonos() {
    $this->validar_sesion();
    echo $this->abonos->datatable_abonos($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_abono() {
    $this->validar_sesion();
    define("CREAR_ABONO", 1);
    define("INGRESO_ABONO", 5);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_abono = $this->request->getPost("id_abono");
    $id_socio = $this->request->getPost("id_socio");
    $abono    = $this->request->getPost("abono");

    $this->db->transStart();

    $datosAbono = [
     "id_socio"   => $id_socio,
     "abono"      => $abono,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "id_apr"     => $id_apr
    ];

    $this->abonos->save($datosAbono);

    $obtener_id = $this->abonos->select("max(id) as id_abono")
                               ->first();
    $id_abono   = $obtener_id["id_abono"];

    $datosAbonoTraza = [
     "id_abono"   => $id_abono,
     "estado"     => CREAR_ABONO,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->abonos_traza->save($datosAbonoTraza);

    $datosSocios = $this->socios->select("abono")
                                ->where("id", $id_socio)
                                ->first();

    $datosSociosSave = [
     "id"         => $id_socio,
     "abono"      => intval($datosSocios["abono"]) + intval($abono),
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->socios->save($datosSociosSave);

    $datosSociosTraza = [
     "id_socio"   => $id_socio,
     "estado"     => INGRESO_ABONO,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->socios_traza->save($datosSociosTraza);

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      echo OK;
    } else {
      echo "Error al guardar el abono";
    }
  }

  public function eliminar_abono() {
    $this->validar_sesion();
    define("ELIMINAR_ABONO", 2);
    define("REDUCIR_ABONO", 6);

    define("OK", 1);
    define("ELIMINADO", 0);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_abono    = $this->request->getPost("id_abono");
    $abono       = $this->request->getPost("abono");
    $id_socio    = $this->request->getPost("id_socio");
    $observacion = $this->request->getPost("observacion");

    $this->db->transStart();

    $datosAbono = [
     "id"         => $id_abono,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => ELIMINADO
    ];

    $this->abonos->save($datosAbono);

    $datosAbonoTraza = [
     "id_abono"   => $id_abono,
     "estado"     => ELIMINAR_ABONO,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->abonos_traza->save($datosAbonoTraza);

    $datosSocios = $this->socios->select("abono")
                                ->where("id", $id_socio)
                                ->first();

    if (intval($datosSocios["abono"]) <= intval($abono)) {
      $abono = 0;
    } else {
      $abono = intval($datosSocios["abono"]) - intval($abono);
    }

    $datosSociosSave = [
     "id"         => $id_socio,
     "abono"      => $abono,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->socios->save($datosSociosSave);

    $datosSociosTraza = [
     "id_socio"   => $id_socio,
     "estado"     => REDUCIR_ABONO,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->socios_traza->save($datosSociosTraza);

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      echo OK;
    } else {
      echo "Error al guardar el abono";
    }
  }

  public function v_abono_traza() {
    $this->validar_sesion();
    echo view("Pagos/abono_traza");
  }

  public function datatable_abono_traza($id_abono) {
    $this->validar_sesion();
    echo $this->abonos_traza->datatable_abono_traza($this->db, $id_abono);
  }
}

?>
