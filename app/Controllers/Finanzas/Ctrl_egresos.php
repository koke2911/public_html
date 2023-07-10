<?php

namespace App\Controllers\Finanzas;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_egresos;
use App\Models\Finanzas\Md_compras;
use App\Models\Finanzas\Md_cuentas;
use App\Models\Inventario\Md_productos;
use App\Models\Finanzas\Md_tipos_egreso;
use App\Models\Finanzas\Md_egresos_traza;
use App\Models\Finanzas\Md_compras_detalle;
use App\Models\Finanzas\Md_egresos_simples;
use App\Models\Inventario\Md_productos_traza;
use App\Models\Inventario\Md_productos_detalles;

class Ctrl_egresos extends BaseController {

  protected $egresos;
  protected $egresos_traza;
  protected $compras;
  protected $compras_detalle;
  protected $productos;
  protected $productos_detalles;
  protected $productos_traza;
  protected $tipos_egreso;
  protected $cuentas;
  protected $egresos_simples;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->egresos            = new Md_egresos();
    $this->egresos_traza      = new Md_egresos_traza();
    $this->compras            = new Md_compras();
    $this->compras_detalle    = new Md_compras_detalle();
    $this->productos          = new Md_productos();
    $this->productos_detalles = new Md_productos_detalles();
    $this->productos_traza    = new Md_productos_traza();
    $this->tipos_egreso       = new Md_tipos_egreso();
    $this->cuentas            = new Md_cuentas();
    $this->egresos_simples    = new Md_egresos_simples();
    $this->sesión             = session();
    $this->db                 = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function llenar_cmb_productos() {
    $datosProductos = $this->productos->select("id")
                                      ->select("nombre as producto")
                                      ->select("marca")
                                      ->select("modelo")
                                      ->findAll();

    $data = [];

    foreach ($datosProductos as $key) {
      if ($key["marca"] != "" && $key["modelo"] != "") {
        $producto = $key["producto"] . " - " . $key["marca"] . " - " . $key["modelo"];
      } else {
        if ($key["marca"] != "" && $key["modelo"] == "") {
          $producto = $key["producto"] . " - " . $key["marca"];
        } else {
          if ($key["marca"] == "" && $key["modelo"] != "") {
            $producto = $key["producto"] . " - " . $key["modelo"];
          } else {
            $producto = $key["producto"];
          }
        }
      }

      $row = [
       "id"       => $key["id"],
       "producto" => $producto
      ];

      $data[] = $row;
    }

    echo json_encode($data);
  }

  public function guardar_producto() {
    $this->validar_sesion();
    define("CREAR_PRODUCTOS", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $nombre = $this->request->getPost("nombre");
    $marca  = $this->request->getPost("marca");
    $modelo = $this->request->getPost("modelo");

    $datosProductos = [
     "nombre"     => $nombre,
     "marca"      => $marca,
     "modelo"     => $modelo,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "id_apr"     => $id_apr
    ];

    $this->productos->save($datosProductos);

    $obtener_id  = $this->productos->select("max(id) as id_producto")
                                   ->first();
    $id_producto = $obtener_id["id_producto"];

    $datosTraza = [
     "id_producto" => $id_producto,
     "estado"      => CREAR_PRODUCTOS,
     "observacion" => "Producto agregado desde el modulo de compras",
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    $this->productos_traza->save($datosTraza);

    echo $id_producto;
  }

  public function guardar_compra() {
    $this->validar_sesion();
    define("CREAR_EGRESO", 1);
    define("COMPRA", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_tipo_documento = $this->request->getPost("tipo_documento");
    $n_documento       = $this->request->getPost("n_documento");
    $fecha_documento   = $this->request->getPost("fecha_documento");
    $neto              = $this->request->getPost("neto");
    $iva               = $this->request->getPost("iva");
    $total             = $this->request->getPost("total");
    $id_proveedor      = $this->request->getPost("id_proveedor");
    $productos         = $this->request->getPost("productos");
    $id_tipo_gasto     = $this->request->getPost("id_tipo_gasto");

    $datosEgreso = [
     "tipo_egreso" => COMPRA,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha,
     "id_apr"      => $id_apr
    ];

    $this->egresos->save($datosEgreso);

    $obtener_id = $this->egresos->select("max(id) as id_egreso")
                                ->first();
    $id_egreso  = $obtener_id["id_egreso"];

    $datosEgresoTraza = [
     "id_egreso"  => $id_egreso,
     "estado"     => CREAR_EGRESO,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->egresos_traza->save($datosEgresoTraza);

    $datosCompras = [
     "id_tipo_documento" => $id_tipo_documento,
     "n_documento"       => $n_documento,
     "fecha_documento"   => date_format(date_create($fecha_documento), 'Y-m-d'),
     "neto"              => $neto,
     "iva"               => $iva,
     "total"             => $total,
     "id_proveedor"      => $id_proveedor,
     "id_egreso"         => $id_egreso,
     "tipo_gasto"         => $id_tipo_gasto

    ];

    $this->compras->save($datosCompras);

    $obtener_id = $this->compras->select("max(id) as id_compra")
                                ->first();
    $id_compra  = $obtener_id["id_compra"];

    foreach ($productos as $key) {
      $id_producto    = $key["id_producto"];
      $cantidad       = $key["cantidad"];
      $precio         = $key["precio"];
      $total_producto = $key["total_producto"];

      $datosComprasDetalle = [
       "id_producto"    => $id_producto,
       "cantidad"       => $cantidad,
       "precio"         => $precio,
       "total_producto" => $total_producto,
       "id_compra"      => $id_compra
      ];

      $this->compras_detalle->save($datosComprasDetalle);

      $datosTraza = [
         "id_producto" => $id_producto,
         "estado"      => 3,
         "observacion" => "Stock agregado desde el modulo de compras - cantidad : ".$cantidad,
         "id_usuario"  => $id_usuario,
         "fecha"       => $fecha
      ];

      $this->productos_traza->save($datosTraza);

      for ($i = 0; $i < $cantidad; $i ++) {
        $datosProductosDetalle = [
         "id_producto" => $id_producto
        ];

        $this->productos_detalles->save($datosProductosDetalle);
      }
    }



    echo OK;
  }

  public function llenar_cmb_tipo_egreso($opcion) {
    $this->validar_sesion();
    define("ACTIVO", 1);

    $datosTiposEgreso = $this->tipos_egreso->select("id")
                                           ->select("tipo_egreso")
                                           ->where("estado", ACTIVO)
                                           ->where("id_apr", $this->sesión->id_apr_ses)
                                           ->findAll();

    if ($opcion == 1) {
      $datosTiposEgreso[] = $this->tipos_egreso->select("id")
                                               ->select("tipo_egreso")
                                               ->where("id", 0)
                                               ->first();
    }

    echo json_encode($datosTiposEgreso);
  }

   public function llenar_cmb_tipo_gasto($opcion) {
    $this->validar_sesion();
    define("ACTIVO", 1);
    $db=$this->db;

    $consulta = "SELECT id,glosa FROM TIPO_GASTO";
    $query = $db->query($consulta);
    $datos_tipo  = $query->getResultArray();

    echo json_encode($datos_tipo);
  }

  public function v_buscar_cuenta() {
    $this->validar_sesion();
    echo view("Finanzas/buscar_cuenta");
  }

  public function datatable_buscar_cuenta() {
    $this->validar_sesion();
    echo $this->cuentas->datatable_buscar_cuenta($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_egreso() {
    $this->validar_sesion();
    define("CREAR_EGRESO", 1);
    define("EGRESO_SIMPLE", 2);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $monto          = $this->request->getPost("monto");
    $fecha_egreso   = $this->request->getPost("fecha_egreso");
    $id_tipo_egreso = $this->request->getPost("id_tipo_egreso");
    $tipo_entidad   = $this->request->getPost("tipo_entidad");
    $id_entidad     = $this->request->getPost("id_entidad");
    $id_motivo      = $this->request->getPost("id_motivo");
    $id_cuenta      = $this->request->getPost("id_cuenta");
    $n_transaccion  = $this->request->getPost("n_transaccion");
    $observaciones  = $this->request->getPost("observaciones");
    $id_tipo_gasto  = $this->request->getPost("id_tipo_gasto");
    // echo $id_tipo_gasto;
    // exit();

    $datosEgreso = [
     "tipo_egreso" => EGRESO_SIMPLE,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha,
     "id_apr"      => $id_apr
    ];

    $this->egresos->save($datosEgreso);

    $obtener_id = $this->egresos->select("max(id) as id_egreso")
                                ->first();
    $id_egreso  = $obtener_id["id_egreso"];

    $datosEgresoTraza = [
     "id_egreso"  => $id_egreso,
     "estado"     => CREAR_EGRESO,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->egresos_traza->save($datosEgresoTraza);

    $datosEgresoSimple = [
     "id_tipo_egreso" => $id_tipo_egreso,
     "fecha"          => date_format(date_create($fecha_egreso), 'Y-m-d'),
     "monto"          => $monto,
     "id_cuenta"      => ($id_cuenta != "") ? $id_cuenta : NULL,
     "n_transaccion"  => $n_transaccion,
     "tipo_entidad"   => $tipo_entidad,
     "id_entidad"     => $id_entidad,
     "id_motivo"      => $id_motivo,
     "observaciones"  => $observaciones,
     "id_egreso"      => $id_egreso,
     "tipo_gasto"     => $id_tipo_gasto
    ];

    $this->egresos_simples->save($datosEgresoSimple);

    echo OK;
  }

  public function datatable_historial_egresos() {
    $this->validar_sesion();
    echo $this->egresos->datatable_historial_egresos($this->db, $this->sesión->id_apr_ses);
  }

  public function v_egresos_traza() {
    $this->validar_sesion();
    echo view("Finanzas/egresos_traza");
  }

  public function datatable_egresos_traza($id_egreso) {
    $this->validar_sesion();
    echo $this->egresos_traza->datatable_egresos_traza($this->db, $id_egreso);
  }

  public function anular_egreso() {
    $this->validar_sesion();

    $id_egreso   = $this->request->getPost("id_egreso");
    $observacion = $this->request->getPost("observacion");

    define("OK", 1);
    define("ANULAR_EGRESO", 2);
    $estado = 0;

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosEgreso = [
     "id"         => $id_egreso,
     "estado"     => 0,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    if ($this->egresos->save($datosEgreso)) {
      $datosEgresoTraza = [
       "id_egreso"   => $id_egreso,
       "estado"      => ANULAR_EGRESO,
       "observacion" => $observacion,
       "id_usuario"  => $id_usuario,
       "fecha"       => $fecha
      ];

      $this->egresos_traza->save($datosEgresoTraza);

      echo OK;
    } else {
      echo "Error al guardar los datos de la cuenta";
    }
  }

  public function v_compra($id_egreso) {
    $this->validar_sesion();
    $data = ["id_egreso" => $id_egreso];

    echo view("Finanzas/compras", $data);
  }

  public function datos_compra() {
    $this->validar_sesion();

    $id_egreso = $this->request->getPost("id_egreso");
    echo $this->compras->datos_compra($this->db, $id_egreso);
  }

  public function datatable_productos_fac($id_compra) {
    $this->validar_sesion();
    echo $this->compras_detalle->datatable_productos_fac($this->db, $id_compra);
  }

  public function v_egreso_simple($id_egreso) {
    $this->validar_sesion();
    $data = ["id_egreso" => $id_egreso];

    echo view("Finanzas/egresos", $data);
  }

  public function datos_egreso() {
    $this->validar_sesion();

    $id_egreso = $this->request->getPost("id_egreso");
    echo $this->egresos_simples->datos_egreso($this->db, $id_egreso);
  }
}

?>