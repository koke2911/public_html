<?php

namespace App\Controllers\Inventario_agua;

use App\Controllers\BaseController;
use App\Models\Inventario_agua\Md_llenado_agua;
use App\Models\Inventario_agua\Md_llenado_agua_traza;

class Ctrl_llenado extends BaseController {

  protected $llenado_agua;
  protected $llenado_agua_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->llenado_agua       = new Md_llenado_agua();
    $this->llenado_agua_traza = new Md_llenado_agua_traza();
    $this->sesión             = session();
    $this->db                 = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_llenado() {
    $this->validar_sesion();
    echo $this->llenado_agua->datatable_llenado($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_llenado() {
    $this->validar_sesion();
    define("INGRESAR_LLENADO", 1);
    define("MODIFICAR_LLENADO", 2);

    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_llenado     = $this->request->getPost("id_llenado");
    $fecha_hora     = $this->request->getPost("fecha_hora");
    $id_operador    = $this->request->getPost("id_operador");
    $cantidad_agua  = $this->request->getPost("cantidad_agua");
    $um_agua        = $this->request->getPost("um_agua");
    $cantidad_cloro = $this->request->getPost("cantidad_cloro");
    $um_cloro       = $this->request->getPost("um_cloro");

    $datosLlenado = [
     "fecha_hora"     => date_format(date_create($fecha_hora), "Y-m-d H:i"),
     "id_operador"    => $id_operador,
     "cantidad_agua"  => $cantidad_agua,
     "um_agua"        => $um_agua,
     "cantidad_cloro" => $cantidad_cloro,
     "um_cloro"       => $um_cloro,
     "id_usuario"     => $id_usuario,
     "fecha"          => $fecha,
     "id_apr"         => $id_apr,
    ];

    if ($id_llenado != "") {
      $estado_traza       = MODIFICAR_LLENADO;
      $datosLlenado["id"] = $id_llenado;
    } else {
      $estado_traza = INGRESAR_LLENADO;
    }

    if ($this->llenado_agua->save($datosLlenado)) {
      echo OK;

      if ($id_llenado == "") {
        $obtener_id = $this->llenado_agua->select("max(id) as id_llenado")
                                         ->first();
        $id_llenado = $obtener_id["id_llenado"];
      }

      $this->guardar_traza($id_llenado, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del sector";
    }
  }

  public function eliminar_llenado() {
    define("ELIMINAR_LLENADO", 3);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_llenado  = $this->request->getPost("id_llenado");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosLlenado = [
     "id"         => $id_llenado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado
    ];

    if ($this->llenado_agua->save($datosLlenado)) {
      echo OK;

      $this->guardar_traza($id_llenado, ELIMINAR_LLENADO, $observacion);
    } else {
      echo "Error al actualizar el sector";
    }
  }

  public function guardar_traza($id_llenado, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_llenado"  => $id_llenado,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->llenado_agua_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_llenado_traza() {
    $this->validar_sesion();
    echo view("Inventario_agua/llenado_agua_traza");
  }

  public function datatable_llenado_traza($id_llenado) {
    $this->validar_sesion();
    echo $this->llenado_agua_traza->datatable_llenado_traza($this->db, $id_llenado);
  }
}

?>