<?php

namespace App\Controllers\Finanzas;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_tipos_egreso;
use App\Models\Finanzas\Md_tipos_egreso_traza;

class Ctrl_tipos_egreso extends BaseController {

  protected $tipos_egreso;
  protected $tipos_egreso_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->tipos_egreso       = new Md_tipos_egreso();
    $this->tipos_egreso_traza = new Md_tipos_egreso_traza();
    $this->sesión             = session();
    $this->db                 = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_tipos_egreso() {
    $this->validar_sesion();
    echo $this->tipos_egreso->datatable_tipos_egreso($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_tipo_egreso() {
    $this->validar_sesion();
    define("CREAR_TIPO_EGRESO", 1);
    define("MODIFICAR_TIPO_EGRESO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_tipo_egreso = $this->request->getPost("id_tipo_egreso");
    $tipo_egreso    = $this->request->getPost("tipo_egreso");

    $datosTipoEgreso = [
     "tipo_egreso" => $tipo_egreso,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha,
     "id_apr"      => $id_apr,
    ];

    if ($id_tipo_egreso != "") {
      $estado_traza          = MODIFICAR_TIPO_EGRESO;
      $datosTipoEgreso["id"] = $id_tipo_egreso;
    } else {
      $estado_traza = CREAR_TIPO_EGRESO;
    }

    if ($this->tipos_egreso->save($datosTipoEgreso)) {
      echo OK;

      if ($id_tipo_egreso == "") {
        $obtener_id     = $this->tipos_egreso->select("max(id) as id_tipo_egreso")
                                             ->first();
        $id_tipo_egreso = $obtener_id["id_tipo_egreso"];
      }

      $this->guardar_traza($id_tipo_egreso, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del sector";
    }
  }

  public function cambiar_estado_tipo_egreso() {
    define("ELIMINAR_TIPO_EGRESO", 3);
    define("RECICLAR_TIPO_EGRESO", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_tipo_egreso = $this->request->getPost("id_tipo_egreso");
    $estado         = $this->request->getPost("estado");
    $observacion    = $this->request->getPost("observacion");

    $datosTipoEgreso = [
     "id"         => $id_tipo_egreso,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->tipos_egreso->save($datosTipoEgreso)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_TIPO_EGRESO;
      } else {
        $estado_traza = RECICLAR_TIPO_EGRESO;
      }

      $this->guardar_traza($id_tipo_egreso, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el sector";
    }
  }

  public function guardar_traza($id_tipo_egreso, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_tipo_egreso" => $id_tipo_egreso,
     "estado"         => $estado,
     "observacion"    => $observacion,
     "id_usuario"     => $id_usuario,
     "fecha"          => $fecha
    ];

    if (!$this->tipos_egreso_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_tipos_egreso_traza() {
    $this->validar_sesion();
    echo view("Finanzas/tipos_egreso_traza");
  }

  public function datatable_tipos_egreso_traza($id_tipo_egreso) {
    $this->validar_sesion();
    echo $this->tipos_egreso_traza->datatable_tipos_egreso_traza($this->db, $id_tipo_egreso);
  }

  public function v_tipos_egreso_reciclar() {
    $this->validar_sesion();
    echo view("Finanzas/tipos_egreso_reciclar");
  }

  public function datatable_tipos_egreso_reciclar() {
    $this->validar_sesion();
    echo $this->tipos_egreso->datatable_tipos_egreso_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>