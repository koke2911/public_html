<?php

namespace App\Controllers\Inventario;

use App\Controllers\BaseController;
use App\Models\Inventario\Md_estados_producto;
use App\Models\Inventario\Md_estados_producto_traza;

class Ctrl_estados_producto extends BaseController {

  protected $estados_producto;
  protected $estados_producto_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->estados_producto       = new Md_estados_producto();
    $this->estados_producto_traza = new Md_estados_producto_traza();
    $this->sesión                 = session();
    $this->db                     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_estados_producto() {
    $this->validar_sesion();
    echo $this->estados_producto->datatable_estados_producto($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_estado() {
    $this->validar_sesion();
    define("CREAR_ESTADOS_PRODUCTO", 1);
    define("MODIFICAR_ESTADOS_PRODUCTO", 2);

    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_estado = $this->request->getPost("id_estado");
    $estado    = $this->request->getPost("estado");

    $datosEstado = [
     "estado_producto" => $estado,
     "id_usuario"      => $id_usuario,
     "fecha"           => $fecha,
     "id_apr"          => $id_apr,
    ];

    if ($id_estado != "") {
      $estado_traza      = MODIFICAR_ESTADOS_PRODUCTO;
      $datosEstado["id"] = $id_estado;
    } else {
      $estado_traza = CREAR_ESTADOS_PRODUCTO;
    }

    if ($this->estados_producto->save($datosEstado)) {
      echo OK;

      if ($id_estado == "") {
        $obtener_id = $this->estados_producto->select("max(id) as id_estado")
                                             ->first();
        $id_estado  = $obtener_id["id_estado"];
      }

      $this->guardar_traza($id_estado, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del estado del producto";
    }
  }

  public function cambiar_estado() {
    define("ELIMINAR_ESTADO_PRODUCTO", 3);
    define("RECICLAR_ESTADO_PRODUCTO", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_estado   = $this->request->getPost("id_estado");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosEstado = [
     "id"         => $id_estado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->estados_producto->save($datosEstado)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_ESTADO_PRODUCTO;
      } else {
        $estado_traza = RECICLAR_ESTADO_PRODUCTO;
      }

      $this->guardar_traza($id_estado, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el estado del producto";
    }
  }

  public function guardar_traza($id_estado, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_estado"   => $id_estado,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->estados_producto_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_estados_producto_traza() {
    $this->validar_sesion();
    echo view("Inventario/estados_producto_traza");
  }

  public function datatable_estados_producto_traza($id_estado) {
    $this->validar_sesion();
    echo $this->estados_producto_traza->datatable_estados_producto_traza($this->db, $id_estado);
  }

  public function v_estados_producto_reciclar() {
    $this->validar_sesion();
    echo view("Inventario/estados_producto_reciclar");
  }

  public function datatable_estados_producto_reciclar() {
    $this->validar_sesion();
    echo $this->estados_producto->datatable_estados_producto_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>