<?php

namespace App\Controllers\Finanzas;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_motivos;
use App\Models\Finanzas\Md_ingresos;
use App\Models\Formularios\Md_socios;
use App\Models\Finanzas\Md_proveedores;
use App\Models\Finanzas\Md_funcionarios;
use App\Models\Finanzas\Md_tipos_ingreso;
use App\Models\Finanzas\Md_ingresos_traza;

class Ctrl_ingresos extends BaseController {

  protected $ingresos;
  protected $ingresos_traza;
  protected $proveedores;
  protected $funcionarios;
  protected $socios;
  protected $motivos;
  protected $tipos_ingreso;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->ingresos       = new Md_ingresos();
    $this->ingresos_traza = new Md_ingresos_traza();
    $this->proveedores    = new Md_proveedores();
    $this->funcionarios   = new Md_funcionarios();
    $this->socios         = new Md_socios();
    $this->motivos        = new Md_motivos();
    $this->tipos_ingreso  = new Md_tipos_ingreso();
    $this->sesión         = session();
    $this->db             = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_ingresos() {
    $this->validar_sesion();
    echo $this->ingresos->datatable_ingresos($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_ingreso() {
    $this->validar_sesion();
    define("CREAR_INGRESO", 1);
    define("MODIFICAR_INGRESO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_ingreso      = $this->request->getPost("id_ingreso");
    $monto           = $this->request->getPost("monto");
    $fecha_ingreso   = $this->request->getPost("fecha_ingreso");
    $id_tipo_ingreso = $this->request->getPost("id_tipo_ingreso");
    $tipo_entidad    = $this->request->getPost("tipo_entidad");
    $id_entidad      = $this->request->getPost("id_entidad");
    $id_motivo       = $this->request->getPost("id_motivo");
    $observaciones   = $this->request->getPost("observaciones");

    $datosIngresos = [
     "monto"           => $monto,
     "fecha_ingreso"   => date_format(date_create($fecha_ingreso), 'Y-m-d'),
     "id_tipo_ingreso" => $id_tipo_ingreso,
     "tipo_entidad"    => $tipo_entidad,
     "id_entidad"      => $id_entidad,
     "id_motivo"       => $id_motivo,
     "observaciones"   => $observaciones,
     "id_usuario"      => $id_usuario,
     "fecha"           => $fecha,
     "id_apr"          => $id_apr
    ];

    if ($id_ingreso != "") {
      $estado_traza        = MODIFICAR_INGRESO;
      $datosIngresos["id"] = $id_ingreso;
    } else {
      $estado_traza = CREAR_INGRESO;
    }

    if ($this->ingresos->save($datosIngresos)) {
      echo OK;

      if ($id_ingreso == "") {
        $obtener_id = $this->ingresos->select("max(id) as id_ingreso")
                                     ->first();
        $id_ingreso = $obtener_id["id_ingreso"];
      }

      $this->guardar_traza($id_ingreso, $estado_traza, "");
    } else {
      echo "Error al guardar el ingreso";
    }
  }

  public function guardar_traza($id_ingreso, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_ingreso"  => $id_ingreso,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->ingresos_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_ingresos_traza() {
    $this->validar_sesion();
    echo view("Finanzas/ingresos_traza");
  }

  public function datatable_ingresos_traza($id_ingreso) {
    $this->validar_sesion();
    echo $this->ingresos_traza->datatable_ingresos_traza($this->db, $id_ingreso);
  }

  public function anular_ingreso() {
    $this->validar_sesion();

    $id_ingreso  = $this->request->getPost("id_ingreso");
    $observacion = $this->request->getPost("observacion");

    define("OK", 1);
    $estado_traza = 3;
    $estado       = 0;

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosIngreso = [
     "id"         => $id_ingreso,
     "estado"     => $estado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    if ($this->ingresos->save($datosIngreso)) {
      echo OK;
      $this->guardar_traza($id_ingreso, $estado_traza, $observacion);
    } else {
      echo "Error al guardar los datos de la cuenta";
    }
  }

  public function v_buscar_proveedor() {
    $this->validar_sesion();
    echo view("Finanzas/buscar_proveedor");
  }

  public function datatable_buscar_proveedor() {
    $this->validar_sesion();
    echo $this->proveedores->datatable_buscar_proveedor($this->db, $this->sesión->id_apr_ses);
  }

  public function v_buscar_funcionario() {
    $this->validar_sesion();
    echo view("Finanzas/buscar_funcionario");
  }

  public function datatable_buscar_funcionario() {
    $this->validar_sesion();
    $datosFuncionario = $this->funcionarios->select("id as id_funcionario")
                                           ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre_funcionario")
                                           ->select("concat(rut, '-', dv) as rut_funcionario")
                                           ->where("id_apr", $this->sesión->id_apr_ses)
                                           ->where("estado", 1)
                                           ->findAll();

    $salida = ["data" => $datosFuncionario];

    return json_encode($salida);
  }

  public function v_buscar_socio() {
    $this->validar_sesion();
    echo view("Finanzas/buscar_socio");
  }

  public function datatable_buscar_socio() {
    $this->validar_sesion();
    $data = $this->socios->select("id as id_socio")
                         ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre_socio")
                         ->select("concat(rut, '-', dv) as rut_socio")
                         ->select("rol as rol_socio")
                         ->where("id_apr", $this->sesión->id_apr_ses)
                         ->where("estado", 1)
                         ->findAll();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function v_buscar_motivo() {
    $this->validar_sesion();
    echo view("Finanzas/buscar_motivo");
  }

  public function datatable_buscar_motivo() {
    $this->validar_sesion();
    echo $this->motivos->datatable_buscar_motivo($this->db, $this->sesión->id_apr_ses);
  }

  public function llenar_cmb_tipo_ingreso() {
    $this->validar_sesion();
    $data = $this->tipos_ingreso->select("id")
                                ->select("tipo_ingreso")
                                ->where("estado", 1)
                                ->where("id_apr", $this->sesión->id_apr_ses)
                                ->findAll();

    return json_encode($data);
  }
}

?>