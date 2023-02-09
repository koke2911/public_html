<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_servicios;
use App\Models\Formularios\Md_servicios_traza;

class Ctrl_servicios extends BaseController {

  protected $servicios;
  protected $servicios_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->servicios       = new Md_servicios();
    $this->servicios_traza = new Md_servicios_traza();
    $this->sesión          = session();
    $this->db              = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_servicios() {
    $this->validar_sesion();
    echo $this->servicios->datatable_servicios($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_servicio() {
    $this->validar_sesion();
    define("CREAR_SERVICIO", 1);
    define("MODIFICAR_SERVICIO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_servicio = $this->request->getPost("id_servicio");
    $servicio    = $this->request->getPost("servicio");

    $datosServicio = [
     "glosa"      => $servicio,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "id_apr"     => $id_apr,
    ];

    if ($id_servicio != "") {
      $estado_traza        = MODIFICAR_SERVICIO;
      $datosServicio["id"] = $id_servicio;
    } else {
      $estado_traza = CREAR_SERVICIO;
    }

    if ($this->servicios->save($datosServicio)) {
      echo OK;

      if ($id_servicio == "") {
        $obtener_id  = $this->servicios->select("max(id) as id_servicio")
                                       ->first();
        $id_servicio = $obtener_id["id_servicio"];
      }

      $this->guardar_traza($id_servicio, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del sector";
    }
  }

  public function eliminar_SERVICIO() {
    define("ELIMINAR_SERVICIO", 3);
    define("RECICLAR_SERVICIO", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_servicio = $this->request->getPost("id_servicio");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosServicio = [
     "id"         => $id_servicio,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->servicios->save($datosServicio)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_SERVICIO;
      } else {
        $estado_traza = RECICLAR_SERVICIO;
      }

      $this->guardar_traza($id_servicio, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el servicio";
    }
  }

  public function guardar_traza($id_servicio, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_servicio" => $id_servicio,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->servicios_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_servicios_traza() {
    $this->validar_sesion();
    echo view("Formularios/servicios_traza");
  }

  public function datatable_servicios_traza($id_servicio) {
    $this->validar_sesion();
    echo $this->servicios_traza->datatable_servicios_traza($this->db, $id_servicio);
  }

  public function v_servicios_reciclar() {
    $this->validar_sesion();
    echo view("Formularios/servicios_reciclar");
  }

  public function datatable_servicios_reciclar() {
    $this->validar_sesion();
    echo $this->servicios->datatable_servicios_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>