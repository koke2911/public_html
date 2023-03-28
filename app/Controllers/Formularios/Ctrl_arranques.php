<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_sectores;
use App\Models\Formularios\Md_arranques;
use App\Models\Formularios\Md_medidores;
use App\Models\Formularios\Md_tipo_documento;
use App\Models\Formularios\Md_arranque_traza;
use App\Models\Configuracion\Md_tarifas;

class Ctrl_arranques extends BaseController {

  protected $arranques;
  protected $arranque_traza;
  protected $sectores;
  protected $tarifa;
  protected $tipo_documento;
  protected $medidores;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->arranques      = new Md_arranques();
    $this->tarifa         = new Md_tarifas();
    $this->arranque_traza = new Md_arranque_traza();
    $this->sectores       = new Md_sectores();
    $this->tipo_documento = new Md_tipo_documento();
    $this->medidores      = new Md_medidores();
    $this->sesión         = session();
    $this->db             = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_arranques() {
    $this->validar_sesion();
    echo $this->arranques->datatable_arranques($this->db, $this->sesión->id_apr_ses);
  }

  public function llenar_cmb_sector() {
    $this->validar_sesion();
    $datos_sectores = $this->sectores->select("id")
                                     ->select("nombre as sector")
                                     ->where("estado", 1)
                                     ->where("id_apr", $this->sesión->id_apr_ses)
                                     ->findAll();

    $data = [];

    foreach ($datos_sectores as $key) {
      $row = [
       "id"     => $key["id"],
       "sector" => $key["sector"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_tarifa() {
    $this->validar_sesion();

    $datos_tarifa = $this->tarifa->select("tarifas.id_tarifa")
                                     ->select("tarifas.tipo")
                                     ->join("apr_cargo_fijo ", "apr_cargo_fijo.tarifa = tarifas.id_tarifa")
                                     ->where("apr_cargo_fijo.id_apr", $this->sesión->id_apr_ses)
                                     ->findAll();

    $data = [];

    foreach ($datos_tarifa as $key) {
      $row = [
       "id"     => $key["id_tarifa"],
       "tarifa" => $key["tipo"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_tarifa_metros() {
    $this->validar_sesion();

    $datos_tarifa = $this->tarifa->select("id_tarifa")
                                     ->select("tipo")
                                     ->findAll();

    $data = [];

    foreach ($datos_tarifa as $key) {
      $row = [
       "id"     => $key["id_tarifa"],
       "tarifa" => $key["tipo"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_tipo_documento() {
    $this->validar_sesion();
    $datos_tipo_documento = $this->tipo_documento->select("id")
                                                 ->select("glosa as tipo_documento")
                                                 ->where("estado", 1)
                                                 ->findAll();

    $data = [];

    foreach ($datos_tipo_documento as $key) {
      $row = [
       "id"             => $key["id"],
       "tipo_documento" => $key["tipo_documento"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_medidores() {
    $this->validar_sesion();
    $opcion = $this->request->getPost("opcion");

    echo $this->medidores->llenar_cmb_medidores($this->db, $this->sesión->id_apr_ses, $opcion);
  }

  public function guardar_arranque() {
    $this->validar_sesion();
    define("CREAR_ARRANQUE", 1);
    define("MODIFICAR_ARRANQUE", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_arranque          = $this->request->getPost("id_arranque");
    $id_socio             = $this->request->getPost("id_socio");
    $id_medidor           = $this->request->getPost("id_medidor");
    $id_sector            = $this->request->getPost("id_sector");
    $alcantarillado       = $this->request->getPost("alcantarillado");
    $cuota_socio          = $this->request->getPost("cuota_socio");
    $otros                = $this->request->getPost("otros");
    $id_comuna            = $this->request->getPost("id_comuna");
    $calle                = $this->request->getPost("calle");
    $numero               = $this->request->getPost("numero");
    $resto_direccion      = $this->request->getPost("resto_direccion");
    $tipo_documento       = $this->request->getPost("tipo_documento");
    $descuento            = $this->request->getPost("descuento");
    $razon_social         = $this->request->getPost("razon_social");
    $giro                 = $this->request->getPost("giro");
    $monto_alcantarillado = $this->request->getPost("monto_alcantarillado");
    $monto_cuota_socio    = $this->request->getPost("monto_cuota_socio");
    $monto_otros          = $this->request->getPost("monto_otros");
    $id_tarifa            = $this->request->getPost("id_tarifa");

    if ($id_comuna == "") {
      $id_comuna = NULL;
    }
    if ($calle == "") {
      $calle = NULL;
    }
    if ($resto_direccion == "") {
      $resto_direccion = NULL;
    }

    $datosArranque = [
     "id_socio"             => $id_socio,
     "estado"               => $estado,
     "id_medidor"           => $id_medidor,
     "id_sector"            => $id_sector,
     "alcantarillado"       => $alcantarillado,
     "cuota_socio"          => $cuota_socio,
     "otros"                => $otros,
     "id_comuna"            => $id_comuna,
     "calle"                => $calle,
     "numero"               => $numero,
     "resto_direccion"      => $resto_direccion,
     "id_tipo_documento"    => $tipo_documento,
     "descuento"            => $descuento,
     "id_usuario"           => $id_usuario,
     "fecha"                => $fecha,
     "id_apr"               => $id_apr,
     "razon_social"         => $razon_social,
     "giro"                 => $giro,
     "monto_alcantarillado" => $monto_alcantarillado,
     "monto_cuota_socio"    => $monto_cuota_socio,
     "monto_otros"          => $monto_otros,
     "tarifa"               => $id_tarifa
    ];

    if ($id_arranque != "") {
      $estado_traza        = MODIFICAR_ARRANQUE;
      $datosArranque["id"] = $id_arranque;
    } else {
      $estado_traza            = CREAR_ARRANQUE;
      $datosArranque["estado"] = $estado;
    }

    if ($this->arranques->save($datosArranque)) {
      echo OK;

      if ($id_arranque == "") {
        $obtener_id  = $this->arranques->select("max(id) as id_arranque")
                                       ->first();
        $id_arranque = $obtener_id["id_arranque"];
      }

      $this->guardar_traza($id_arranque, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del arranque";
    }
  }

  public function eliminar_arranque() {
    define("ELIMINAR_ARRANQUE", 3);
    define("RECICLAR_ARRANQUE", 4);
    define("ELIMINAR", 0);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_arranque = $this->request->getPost("id_arranque");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosArranque = [
     "id"         => $id_arranque,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($estado == ELIMINAR) {
      $datosArranque["id_socio"] = NULL;
    }

    if ($this->arranques->save($datosArranque)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_ARRANQUE;
      } else {
        $estado_traza = RECICLAR_ARRANQUE;
      }

      $this->guardar_traza($id_arranque, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el arranque";
    }
  }

  public function guardar_traza($id_arranque, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_arranque" => $id_arranque,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->arranque_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_arranque_traza() {
    $this->validar_sesion();
    echo view("Formularios/arranque_traza");
  }

  public function datatable_arranque_traza($id_arranque) {
    $this->validar_sesion();
    echo $this->arranque_traza->datatable_arranque_traza($this->db, $id_arranque);
  }

  public function v_arranque_reciclar() {
    $this->validar_sesion();
    echo view("Formularios/arranque_reciclar");
  }

  public function datatable_arranque_reciclar() {
    $this->validar_sesion();
    echo $this->arranques->datatable_arranque_reciclar($this->db, $this->sesión->id_apr_ses);
  }

  public function v_buscar_socio($origen) {
    $this->validar_sesion();
    $data = ['origen' => $origen];
    echo view("Formularios/buscar_socio", $data);
  }

  public function datatable_buscar_socio() {
    $this->validar_sesion();
    echo $this->arranques->datatable_buscar_socio($this->db, $this->sesión->id_apr_ses);
  }
}