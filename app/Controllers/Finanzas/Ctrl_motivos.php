<?php

namespace App\Controllers\Finanzas;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_motivos;
use App\Models\Finanzas\Md_motivos_traza;

class Ctrl_motivos extends BaseController {

  protected $motivos;
  protected $motivos_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->motivos       = new Md_motivos();
    $this->motivos_traza = new Md_motivos_traza();
    $this->sesión        = session();
    $this->db            = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_motivos() {
    $this->validar_sesion();
    echo $this->motivos->datatable_motivos($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_motivo() {
    $this->validar_sesion();
    define("CREAR_MOTIVO", 1);
    define("MODIFICAR_MOTIVO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_motivo = $this->request->getPost("id_motivo");
    $motivo    = $this->request->getPost("motivo");

    $datosMotivo = [
     "motivo"     => $motivo,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "id_apr"     => $id_apr,
    ];

    if ($id_motivo != "") {
      $estado_traza      = MODIFICAR_MOTIVO;
      $datosMotivo["id"] = $id_motivo;
    } else {
      $estado_traza          = CREAR_MOTIVO;
      $datosMotivo["estado"] = $estado;
    }

    if ($this->motivos->save($datosMotivo)) {
      echo OK;

      if ($id_motivo == "") {
        $obtener_id = $this->motivos->select("max(id) as id_motivo")
                                    ->first();
        $id_motivo  = $obtener_id["id_motivo"];
      }

      $this->guardar_traza($id_motivo, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del sector";
    }
  }

  public function eliminar_motivo() {
    define("ELIMINAR_MOTIVO", 3);
    define("RECICLAR_MOTIVO", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_motivo   = $this->request->getPost("id_motivo");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosMotivo = [
     "id"         => $id_motivo,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->motivos->save($datosMotivo)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_MOTIVO;
      } else {
        $estado_traza = RECICLAR_MOTIVO;
      }

      $this->guardar_traza($id_motivo, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el sector";
    }
  }

  public function guardar_traza($id_motivo, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_motivo"   => $id_motivo,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->motivos_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_motivos_traza() {
    $this->validar_sesion();
    echo view("Finanzas/motivos_traza");
  }

  public function datatable_motivos_traza($id_motivo) {
    $this->validar_sesion();
    echo $this->motivos_traza->datatable_motivos_traza($this->db, $id_motivo);
  }

  public function v_motivos_reciclar() {
    $this->validar_sesion();
    echo view("Finanzas/motivos_reciclar");
  }

  public function datatable_motivos_reciclar() {
    $this->validar_sesion();
    echo $this->motivos->datatable_motivos_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>