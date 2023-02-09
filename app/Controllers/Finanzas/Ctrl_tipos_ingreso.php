<?php

namespace App\Controllers\Finanzas;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_tipos_ingreso;
use App\Models\Finanzas\Md_tipos_ingreso_traza;

class Ctrl_tipos_ingreso extends BaseController {

  protected $tipos_ingreso;
  protected $tipos_ingreso_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->tipos_ingreso       = new Md_tipos_ingreso();
    $this->tipos_ingreso_traza = new Md_tipos_ingreso_traza();
    $this->sesión              = session();
    $this->db                  = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_tipos_ingreso() {
    $this->validar_sesion();
    echo $this->tipos_ingreso->datatable_tipos_ingreso($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_tipo_ingreso() {
    $this->validar_sesion();
    define("CREAR_TIPO_INGRESO", 1);
    define("MODIFICAR_TIPO_INGRESO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_tipo_ingreso = $this->request->getPost("id_tipo_ingreso");
    $tipo_ingreso    = $this->request->getPost("tipo_ingreso");

    $datosTipoIngreso = [
     "tipo_ingreso" => $tipo_ingreso,
     "id_usuario"   => $id_usuario,
     "fecha"        => $fecha,
     "id_apr"       => $id_apr,
    ];

    if ($id_tipo_ingreso != "") {
      $estado_traza           = MODIFICAR_TIPO_INGRESO;
      $datosTipoIngreso["id"] = $id_tipo_ingreso;
    } else {
      $estado_traza = CREAR_TIPO_INGRESO;
    }

    if ($this->tipos_ingreso->save($datosTipoIngreso)) {
      echo OK;

      if ($id_tipo_ingreso == "") {
        $obtener_id      = $this->tipos_ingreso->select("max(id) as id_tipo_ingreso")
                                               ->first();
        $id_tipo_ingreso = $obtener_id["id_tipo_ingreso"];
      }

      $this->guardar_traza($id_tipo_ingreso, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del sector";
    }
  }

  public function cambiar_estado_tipo_ingreso() {
    define("ELIMINAR_TIPO_INGRESO", 3);
    define("RECICLAR_TIPO_INGRESO", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_tipo_ingreso = $this->request->getPost("id_tipo_ingreso");
    $estado          = $this->request->getPost("estado");
    $observacion     = $this->request->getPost("observacion");

    $datosTipoIngreso = [
     "id"         => $id_tipo_ingreso,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->tipos_ingreso->save($datosTipoIngreso)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_TIPO_INGRESO;
      } else {
        $estado_traza = RECICLAR_TIPO_INGRESO;
      }

      $this->guardar_traza($id_tipo_ingreso, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el sector";
    }
  }

  public function guardar_traza($id_tipo_ingreso, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_tipo_ingreso" => $id_tipo_ingreso,
     "estado"          => $estado,
     "observacion"     => $observacion,
     "id_usuario"      => $id_usuario,
     "fecha"           => $fecha
    ];

    if (!$this->tipos_ingreso_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_tipos_ingreso_traza() {
    $this->validar_sesion();
    echo view("Finanzas/tipos_ingreso_traza");
  }

  public function datatable_tipos_ingreso_traza($id_tipo_ingreso) {
    $this->validar_sesion();
    echo $this->tipos_ingreso_traza->datatable_tipos_ingreso_traza($this->db, $id_tipo_ingreso);
  }

  public function v_tipos_ingreso_reciclar() {
    $this->validar_sesion();
    echo view("Finanzas/tipos_ingreso_reciclar");
  }

  public function datatable_tipos_ingreso_reciclar() {
    $this->validar_sesion();
    echo $this->tipos_ingreso->datatable_tipos_ingreso_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>