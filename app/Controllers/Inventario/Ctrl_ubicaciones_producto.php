<?php

namespace App\Controllers\Inventario;

use App\Controllers\BaseController;
use App\Models\Inventario\Md_ubicaciones_producto;
use App\Models\Inventario\Md_ubicaciones_producto_traza;

class Ctrl_ubicaciones_producto extends BaseController {

  protected $ubicaciones_producto;
  protected $ubicaciones_producto_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->ubicaciones_producto       = new Md_ubicaciones_producto();
    $this->ubicaciones_producto_traza = new Md_ubicaciones_producto_traza();
    $this->sesión                     = session();
    $this->db                         = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_ubicaciones_producto() {
    $this->validar_sesion();
    echo $this->ubicaciones_producto->datatable_ubicaciones_producto($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_ubicacion() {
    $this->validar_sesion();
    define("CREAR_UBICACION_PRODUCTO", 1);
    define("MODIFICAR_UBICACION_PRODUCTO", 2);

    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_ubicacion = $this->request->getPost("id_ubicacion");
    $ubicacion    = $this->request->getPost("ubicacion");

    $datosUbicacion = [
     "ubicacion"  => $ubicacion,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "id_apr"     => $id_apr,
    ];

    if ($id_ubicacion != "") {
      $estado_traza         = MODIFICAR_UBICACION_PRODUCTO;
      $datosUbicacion["id"] = $id_ubicacion;
    } else {
      $estado_traza = CREAR_UBICACION_PRODUCTO;
    }

    if ($this->ubicaciones_producto->save($datosUbicacion)) {
      echo OK;

      if ($id_ubicacion == "") {
        $obtener_id   = $this->ubicaciones_producto->select("max(id) as id_ubicacion")
                                                   ->first();
        $id_ubicacion = $obtener_id["id_ubicacion"];
      }

      $this->guardar_traza($id_ubicacion, $estado_traza, "");
    } else {
      echo "Error al guardar los datos de la ubicacion del producto";
    }
  }

  public function cambiar_estado_ubicacion() {
    define("ELIMINAR_UBICACION_PRODUCTO", 3);
    define("RECICLAR_UBICACION_PRODUCTO", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_ubicacion = $this->request->getPost("id_ubicacion");
    $estado       = $this->request->getPost("estado");
    $observacion  = $this->request->getPost("observacion");

    $datosUbicacion = [
     "id"         => $id_ubicacion,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->ubicaciones_producto->save($datosUbicacion)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_UBICACION_PRODUCTO;
      } else {
        $estado_traza = RECICLAR_UBICACION_PRODUCTO;
      }

      $this->guardar_traza($id_ubicacion, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar la ubicación del producto";
    }
  }

  public function guardar_traza($id_ubicacion, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_ubicacion" => $id_ubicacion,
     "estado"       => $estado,
     "observacion"  => $observacion,
     "id_usuario"   => $id_usuario,
     "fecha"        => $fecha
    ];

    if (!$this->ubicaciones_producto_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_ubicaciones_producto_traza() {
    $this->validar_sesion();
    echo view("Inventario/ubicaciones_producto_traza");
  }

  public function datatable_ubicaciones_producto_traza($id_ubicacion) {
    $this->validar_sesion();
    echo $this->ubicaciones_producto_traza->datatable_ubicaciones_producto_traza($this->db, $id_ubicacion);
  }

  public function v_ubicaciones_producto_reciclar() {
    $this->validar_sesion();
    echo view("Inventario/ubicaciones_producto_reciclar");
  }

  public function datatable_ubicaciones_producto_reciclar() {
    $this->validar_sesion();
    echo $this->ubicaciones_producto->datatable_ubicaciones_producto_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>