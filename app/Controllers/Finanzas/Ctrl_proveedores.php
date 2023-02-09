<?php

namespace App\Controllers\Finanzas;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_proveedores;
use App\Models\Finanzas\Md_proveedores_traza;

class Ctrl_proveedores extends BaseController {

  protected $proveedores;
  protected $proveedores_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->proveedores       = new Md_proveedores();
    $this->proveedores_traza = new Md_proveedores_traza();
    $this->sesión            = session();
    $this->db                = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_proveedores() {
    $this->validar_sesion();
    echo $this->proveedores->datatable_proveedores($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_proveedor() {
    $this->validar_sesion();
    define("CREAR_PROVEEDOR", 1);
    define("MODIFICAR_PROVEEDOR", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_proveedor        = $this->request->getPost("id_proveedor");
    $rut_proveedor       = $this->request->getPost("rut_proveedor");
    $razon_social        = $this->request->getPost("razon_social");
    $giro                = $this->request->getPost("giro");
    $id_comuna           = $this->request->getPost("id_comuna");
    $direccion_proveedor = $this->request->getPost("direccion_proveedor");
    $email_proveedor     = $this->request->getPost("email_proveedor");
    $fono                = $this->request->getPost("fono");

    if ($id_comuna == "") {
      $id_comuna = NULL;
    }

    $rut_completo = explode("-", $rut_proveedor);
    $rut          = $rut_completo[0];
    $dv           = $rut_completo[1];

    if ($this->proveedores->existe_proveedor($rut, $this->sesión->id_apr_ses) and $id_proveedor == "") {
      echo "Proveedor ya existe en el sistema";
      exit();
    }

    $datosProveedor = [
     "rut"          => $rut,
     "dv"           => $dv,
     "razon_social" => $razon_social,
     "giro"         => $giro,
     "id_comuna"    => $id_comuna,
     "direccion"    => $direccion_proveedor,
     "email"        => $email_proveedor,
     "fono"         => $fono,
     "id_usuario"   => $id_usuario,
     "fecha"        => $fecha,
     "id_apr"       => $id_apr,
    ];

    if ($id_proveedor != "") {
      $estado_traza         = MODIFICAR_PROVEEDOR;
      $datosProveedor["id"] = $id_proveedor;
    } else {
      $estado_traza = CREAR_PROVEEDOR;
    }

    if ($this->proveedores->save($datosProveedor)) {
      echo OK;

      if ($id_proveedor == "") {
        $obtener_id   = $this->proveedores->select("max(id) as id_proveedor")
                                          ->first();
        $id_proveedor = $obtener_id["id_proveedor"];
      }

      $this->guardar_traza($id_proveedor, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del socio";
    }
  }

  public function guardar_traza($id_proveedor, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_proveedor" => $id_proveedor,
     "estado"       => $estado,
     "observacion"  => $observacion,
     "id_usuario"   => $id_usuario,
     "fecha"        => $fecha
    ];

    if (!$this->proveedores_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_proveedor_traza() {
    $this->validar_sesion();
    echo view("Finanzas/proveedores_traza");
  }

  public function datatable_proveedores_traza($id_proveedor) {
    $this->validar_sesion();
    echo $this->proveedores_traza->datatable_proveedores_traza($this->db, $id_proveedor);
  }

  public function cambiar_estado_proveedor() {
    $this->validar_sesion();
    $id_proveedor = $this->request->getPost("id_proveedor");
    $estado       = $this->request->getPost("estado");
    $observacion  = $this->request->getPost("observacion");

    define("OK", 1);

    if ($estado == "eliminar") {
      $estado_traza = 3;
      $estado       = 0;
    } else {
      $estado_traza = 4;
      $estado       = 1;
    }

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosProveedor = [
     "id"         => $id_proveedor,
     "estado"     => $estado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    if ($this->proveedores->save($datosProveedor)) {
      echo OK;
      $this->guardar_traza($id_proveedor, $estado_traza, $observacion);
    } else {
      echo "Error al guardar los datos del proveedor";
    }
  }

  public function v_proveedor_reciclar() {
    $this->validar_sesion();
    echo view("Finanzas/proveedores_reciclar");
  }

  public function datatable_proveedores_reciclar() {
    $this->validar_sesion();
    echo $this->proveedores->datatable_proveedores_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>