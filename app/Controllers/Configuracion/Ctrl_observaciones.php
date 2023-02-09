<?php

namespace App\Controllers\Configuracion;

use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr_traza;
use App\Models\Configuracion\Md_observaciones_dte;

class Ctrl_observaciones extends BaseController {

  protected $observaciones_dte;
  protected $apr_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->observaciones_dte = new Md_observaciones_dte();
    $this->apr_traza         = new Md_apr_traza();
    $this->sesión            = session();
    $this->db                = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_observaciones() {
    $this->validar_sesion();
    define("ACTIVO", 1);

    $datosObservaciones = $this->observaciones_dte
     ->select("id as id_observacion")
     ->select("titulo")
     ->select("observacion")
     ->where("id_apr", $this->sesión->id_apr_ses)
     ->where("estado", ACTIVO)
     ->findAll();

    $respuesta = ['data' => $datosObservaciones];

    return json_encode($respuesta);
  }

  public function guardar() {
    $this->validar_sesion();
    define("CREAR_OBSERVACION", 4);
    define("MODIFICAR_OBSERVACION", 5);

    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_observacion = $this->request->getPost("id_observacion");
    $titulo         = $this->request->getPost("titulo");
    $observacion    = $this->request->getPost("observacion");

    $datosObservacion = [
     "titulo"      => $titulo,
     "observacion" => $observacion,
     "id_apr"      => $id_apr
    ];

    if ($id_observacion != "") {
      $estado_traza           = MODIFICAR_OBSERVACION;
      $datosObservacion["id"] = $id_observacion;
    } else {
      $estado_traza = CREAR_OBSERVACION;
    }

    $this->db->transStart();
    $this->observaciones_dte->save($datosObservacion);

    if ($id_observacion == "") {
      $obtener_id     = $this->observaciones_dte->select("max(id) as id_observacion")
                                                ->first();
      $id_observacion = $obtener_id["id_observacion"];
    }

    $datosTraza = [
     "id_apr"      => $id_apr,
     "estado"      => $estado_traza,
     "observacion" => "Ingreso de observación con folio " . $id_observacion . ".",
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    $this->apr_traza->save($datosTraza);

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      $respuesta = [
       "estado"  => OK,
       "mensaje" => "Observación ingresada correctamente"
      ];

      return json_encode($respuesta);
    } else {
      $respuesta = [
       "estado"  => "Error",
       "mensaje" => "Error al ingresar la observación"
      ];

      return json_encode($respuesta);
    }
  }

  public function eliminar() {
    $this->validar_sesion();
    define("ELIMINAR_OBSERVACION", 6);

    define("OK", 1);
    define("ELIMINADO", 0);

    $fecha          = date("Y-m-d H:i:s");
    $id_usuario     = $this->sesión->id_usuario_ses;
    $id_apr         = $this->sesión->id_apr_ses;
    $id_observacion = $this->request->getPost("id_observacion");

    $datosObservacion = [
     "estado" => ELIMINADO,
     "id"     => $id_observacion
    ];

    $this->db->transStart();
    $this->observaciones_dte->save($datosObservacion);

    $datosTraza = [
     "id_apr"     => $id_apr,
     "estado"     => ELIMINAR_OBSERVACION,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->apr_traza->save($datosTraza);

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      $respuesta = [
       "estado"  => OK,
       "mensaje" => "Observación eliminada correctamente"
      ];

      return json_encode($respuesta);
    } else {
      $respuesta = [
       "estado"  => "Error",
       "mensaje" => "Error al eliminar la observación"
      ];

      return json_encode($respuesta);
    }
  }
}

?>