<?php

namespace App\Controllers\Inventario;

use App\Controllers\BaseController;
use App\Models\Inventario\Md_productos;
use App\Models\Inventario\Md_productos_traza;
use App\Models\Inventario\Md_estados_producto;
use App\Models\Inventario\Md_productos_detalles;
use App\Models\Inventario\Md_ubicaciones_producto;
use App\Models\Inventario\Md_productos_detalles_traza;

class Ctrl_productos extends BaseController {

  protected $productos;
  protected $productos_traza;
  protected $productos_detalles;
  protected $productos_detalles_traza;
  protected $estados_producto;
  protected $ubicaciones_producto;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->productos                = new Md_productos();
    $this->productos_traza          = new Md_productos_traza();
    $this->productos_detalles       = new Md_productos_detalles();
    $this->productos_detalles_traza = new Md_productos_detalles_traza();
    $this->estados_producto         = new Md_estados_producto();
    $this->ubicaciones_producto     = new Md_ubicaciones_producto();
    $this->sesión                   = session();
    $this->db                       = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_productos() {
    $this->validar_sesion();
    echo $this->productos->datatable_productos($this->db, $this->sesión->id_apr_ses);
  }

  public function datatable_productos_detalles($id_producto) {
    $this->validar_sesion();
    echo $this->productos_detalles->datatable_productos_detalles($this->db, $id_producto);
  }

  public function guardar_productos() {
    $this->validar_sesion();
    define("CREAR_PRODUCTOS", 1);
    define("MODIFICAR_PRODUCTOS", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_producto = $this->request->getPost("id_producto");
    $nombre      = $this->request->getPost("nombre");
    $marca       = $this->request->getPost("marca");
    $modelo      = $this->request->getPost("modelo");
    $cantidad    = $this->request->getPost("cantidad");

    $datos_detalles = $this->request->getPost("datos_detalles");

    $datosProductos = [
     "nombre"     => $nombre,
     "marca"      => $marca,
     "modelo"     => $modelo,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "id_apr"     => $id_apr
    ];

    if ($id_producto != "") {
      $estado_traza         = MODIFICAR_PRODUCTOS;
      $datosProductos["id"] = $id_producto;
    } else {
      $estado_traza = CREAR_PRODUCTOS;
    }

    if ($this->productos->save($datosProductos)) {
      echo OK;

      if ($id_producto == "") {
        $obtener_id  = $this->productos->select("max(id) as id_producto")
                                       ->first();
        $id_producto = $obtener_id["id_producto"];
      }

      $this->guardar_traza($id_producto, $estado_traza, "");

      foreach ($datos_detalles as $key) {
        if ($key["codigo_barra"] != "") {
          $codigo_barra = $key["codigo_barra"];
        } else {
          $codigo_barra = NULL;
        }
        if ($key["id_estado"] != "") {
          $id_estado = $key["id_estado"];
        } else {
          $id_estado = NULL;
        }
        if ($key["id_ubicacion"] != "") {
          $id_ubicacion = $key["id_ubicacion"];
        } else {
          $id_ubicacion = NULL;
        }

        $datosDetalleValida = $this->productos_detalles->select("id")
                                                       ->select("codigo")
                                                       ->select("id_estado")
                                                       ->select("id_ubicacion")
                                                       ->select("id_producto")
                                                       ->where("id", $key["id_detalle"])
                                                       ->first();

        $datosDetalle = [
         "id"           => $key["id_detalle"],
         "codigo"       => $codigo_barra,
         "id_estado"    => $id_estado,
         "id_ubicacion" => $id_ubicacion,
         "id_producto"  => $id_producto
        ];

        $this->productos_detalles->save($datosDetalle);

        if ($key["id_detalle"] != "") {
          $id_detalle = $key["id_detalle"];
        } else {
          $obtener_id_det = $this->productos_detalles->select("max(id) as id_detalle")
                                                     ->first();
          $id_detalle     = $obtener_id_det["id_detalle"];
        }

        if ($datosDetalleValida != NULL) {
          if (!($datosDetalleValida["id"] == $id_detalle && $datosDetalleValida["codigo"] == $codigo_barra && $datosDetalleValida["id_estado"] == $id_estado && $datosDetalleValida["id_ubicacion"] == $id_ubicacion && $datosDetalleValida["id_producto"] == $id_producto)) {
            $datosDetalleTraza = [
             "id_detalle" => $id_detalle,
             "estado"     => $id_estado,
             "id_usuario" => $id_usuario,
             "fecha"      => $fecha
            ];

            $this->productos_detalles_traza->save($datosDetalleTraza);
          }
        } else {
          $datosDetalleTraza = [
           "id_detalle" => $id_detalle,
           "estado"     => $id_estado,
           "id_usuario" => $id_usuario,
           "fecha"      => $fecha
          ];

          $this->productos_detalles_traza->save($datosDetalleTraza);
        }
      }
    } else {
      echo "Error al guardar los datos del producto";
    }
  }

  public function cambiar_estado_producto() {
    define("ELIMINAR_PRODUCTO", 3);
    define("RECICLAR_PRODUCTO", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_producto = $this->request->getPost("id_producto");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosProductos = [
     "id"         => $id_producto,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->productos->save($datosProductos)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_PRODUCTO;
      } else {
        $estado_traza = RECICLAR_PRODUCTO;
      }

      $this->guardar_traza($id_producto, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el productos";
    }
  }

  public function guardar_traza($id_producto, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_producto" => $id_producto,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->productos_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_productos_traza() {
    $this->validar_sesion();
    echo view("Inventario/productos_traza");
  }

  public function datatable_productos_traza($id_producto) {
    $this->validar_sesion();
    echo $this->productos_traza->datatable_productos_traza($this->db, $id_producto);
  }

  public function llenar_cmb_estado() {
    $this->validar_sesion();
    $datosEstados = $this->estados_producto->select("id")
                                           ->select("estado_producto as estado")
                                           ->where("estado", 1)
                                           ->where("id_apr", $this->sesión->id_apr_ses)
                                           ->findAll();

    $data = [];

    foreach ($datosEstados as $key) {
      $row = [
       "id"     => $key["id"],
       "estado" => $key["estado"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_ubicacion() {
    $this->validar_sesion();
    $datosUbicaciones = $this->ubicaciones_producto
     ->select("id")
     ->select("ubicacion")
     ->where("estado", 1)
     ->where("id_apr", $this->sesión->id_apr_ses)
     ->findAll();

    $data = [];

    foreach ($datosUbicaciones as $key) {
      $row = [
       "id"        => $key["id"],
       "ubicacion" => $key["ubicacion"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function v_productos_detalles_traza() {
    $this->validar_sesion();
    echo view("Inventario/productos_detalles_traza");
  }

  public function datatable_productos_detalles_traza($id_detalle) {
    $this->validar_sesion();
    echo $this->productos_detalles_traza->datatable_productos_detalles_traza($this->db, $id_detalle);
  }

  public function dar_baja_unidad() {
    $this->validar_sesion();
    define("DAR_BAJA", 0);
    define("OK", 1);

    $id_detalle  = $this->request->getPost("id_detalle");
    $observacion = $this->request->getPost("observacion");

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosDetalle = [
     "id"        => $id_detalle,
     "id_estado" => DAR_BAJA
    ];

    $this->productos_detalles->save($datosDetalle);

    $datosDetalleTraza = [
     "id_detalle"  => $id_detalle,
     "estado"      => DAR_BAJA,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    $this->productos_detalles_traza->save($datosDetalleTraza);

    echo OK;
  }
}

?>