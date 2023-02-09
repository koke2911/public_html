<?php

namespace App\Controllers\Finanzas;

use App\Models\Finanzas\Md_bancos;
use App\Models\Finanzas\Md_cuentas;
use App\Controllers\BaseController;
use App\Models\Finanzas\Md_cuentas_traza;
use App\Models\Finanzas\Md_banco_tipo_cuenta;

class Ctrl_cuentas extends BaseController {

  protected $cuentas;
  protected $cuentas_traza;
  protected $bancos;
  protected $banco_tipo_cuenta;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->cuentas           = new Md_cuentas();
    $this->cuentas_traza     = new Md_cuentas_traza();
    $this->bancos            = new Md_bancos();
    $this->banco_tipo_cuenta = new Md_banco_tipo_cuenta();
    $this->sesión            = session();
    $this->db                = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_cuentas() {
    $this->validar_sesion();
    echo $this->cuentas->datatable_cuentas($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_cuenta() {
    $this->validar_sesion();
    define("CREAR_CUENTA", 1);
    define("MODIFICAR_CUENTA", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_cuenta      = $this->request->getPost("id_cuenta");
    $rut_cuenta     = $this->request->getPost("rut_cuenta");
    $id_banco       = $this->request->getPost("id_banco");
    $id_tipo_cuenta = $this->request->getPost("id_tipo_cuenta");
    $n_cuenta       = $this->request->getPost("n_cuenta");
    $nombre_cuenta  = $this->request->getPost("nombre_cuenta");
    $email_cuenta   = $this->request->getPost("email_cuenta");

    if ($email_cuenta == "") {
      $email_cuenta = NULL;
    }

    $rut_completo = explode("-", $rut_cuenta);
    $rut          = $rut_completo[0];
    $dv           = $rut_completo[1];

    if ($this->cuentas->existe_cuenta($rut, $id_banco, $id_tipo_cuenta, $n_cuenta, $this->sesión->id_apr_ses) and $id_cuenta == "") {
      echo "Cuenta ya existe en el sistema";
      exit();
    }

    $datosCuenta = [
     "id_cuenta"      => $id_cuenta,
     "rut"            => $rut,
     "dv"             => $dv,
     "id_banco"       => $id_banco,
     "id_tipo_cuenta" => $id_tipo_cuenta,
     "n_cuenta"       => $n_cuenta,
     "nombre_cuenta"  => $nombre_cuenta,
     "email_cuenta"   => $email_cuenta,
     "id_usuario"     => $id_usuario,
     "fecha"          => $fecha,
     "id_apr"         => $id_apr
    ];

    if ($id_cuenta != "") {
      $estado_traza      = MODIFICAR_CUENTA;
      $datosCuenta["id"] = $id_cuenta;
    } else {
      $estado_traza = CREAR_CUENTA;
    }

    if ($this->cuentas->save($datosCuenta)) {
      echo OK;

      if ($id_cuenta == "") {
        $obtener_id = $this->cuentas->select("max(id) as id_cuenta")
                                    ->first();
        $id_cuenta  = $obtener_id["id_cuenta"];
      }

      $this->guardar_traza($id_cuenta, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del socio";
    }
  }

  public function guardar_traza($id_cuenta, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_cuenta"   => $id_cuenta,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->cuentas_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_cuentas_traza() {
    $this->validar_sesion();
    echo view("Finanzas/cuentas_traza");
  }

  public function datatable_cuentas_traza($id_cuenta) {
    $this->validar_sesion();
    echo $this->cuentas_traza->datatable_cuentas_traza($this->db, $id_cuenta);
  }

  public function cambiar_estado_cuenta() {
    $this->validar_sesion();
    $id_cuenta   = $this->request->getPost("id_cuenta");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

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

    $datosCuenta = [
     "id"         => $id_cuenta,
     "estado"     => $estado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    if ($this->cuentas->save($datosCuenta)) {
      echo OK;
      $this->guardar_traza($id_cuenta, $estado_traza, $observacion);
    } else {
      echo "Error al guardar los datos de la cuenta";
    }
  }

  public function v_cuentas_reciclar() {
    $this->validar_sesion();
    echo view("Finanzas/cuentas_reciclar");
  }

  public function datatable_cuentas_reciclar() {
    $this->validar_sesion();
    echo $this->cuentas->datatable_cuentas_reciclar($this->db, $this->sesión->id_apr_ses);
  }

  public function llenar_cmb_banco() {
    $datosBancos = $this->bancos->select("id")
                                ->select("nombre_banco as banco")
                                ->findAll();

    $data = [];

    foreach ($datosBancos as $key) {
      $row = [
       "id"    => $key["id"],
       "banco" => $key["banco"]
      ];

      $data[] = $row;
    }

    echo json_encode($data);
  }

  public function llenar_cmb_tipo_cuenta() {
    $datosTipoCuenta = $this->banco_tipo_cuenta->select("id")
                                               ->select("glosa as tipo_cuenta")
                                               ->findAll();

    $data = [];

    foreach ($datosTipoCuenta as $key) {
      $row = [
       "id"          => $key["id"],
       "tipo_cuenta" => $key["tipo_cuenta"]
      ];

      $data[] = $row;
    }

    echo json_encode($data);
  }
}

?>