<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_cambio_medidor;
use App\Models\Formularios\Md_cambio_medidor_traza;

class Ctrl_cambio_medidor extends BaseController {

  protected $cambio_medidor;
  protected $cambio_medidor_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->cambio_medidor       = new Md_cambio_medidor();
    $this->cambio_medidor_traza = new Md_cambio_medidor_traza();
    $this->sesión               = session();
    $this->db                   = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_cambio_medidor() {
    $this->validar_sesion();
    echo $this->cambio_medidor->datatable_cambio_medidor($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_cambio_medidor() {
    $this->validar_sesion();
    define("CREAR_CAMBIO_MEDIDOR", 1);
    define("MODIFICAR_CAMBIO_MEDIDOR", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_cambio      = $this->request->getPost("id_cambio");
    $id_socio       = $this->request->getPost("id_socio");
    $id_funcionario = $this->request->getPost("id_funcionario");
    $motivo_cambio  = $this->request->getPost("motivo_cambio");
    $fecha_cambio   = $this->request->getPost("fecha_cambio");

    $datosCambioMedidor = [
     "id_socio"       => $id_socio,
     "id_funcionario" => $id_funcionario,
     "motivo_cambio"  => $motivo_cambio,
     "fecha_cambio"   => date_format(date_create($fecha_cambio), 'Y-m-d'),
     "id_usuario"     => $id_usuario,
     "fecha"          => $fecha,
     "id_apr"         => $id_apr
    ];

    if ($id_cambio != "") {
      $estado_traza             = MODIFICAR_CAMBIO_MEDIDOR;
      $datosCambioMedidor["id"] = $id_cambio;
    } else {
      $estado_traza = CREAR_CAMBIO_MEDIDOR;
    }

    if ($this->cambio_medidor->save($datosCambioMedidor)) {
      echo OK;

      if ($id_cambio == "") {
        $obtener_id = $this->cambio_medidor->select("max(id) as id_cambio")
                                           ->first();
        $id_cambio  = $obtener_id["id_cambio"];
      }

      $this->guardar_traza($id_cambio, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del arranque";
    }
  }

  public function anular_cambio_medidor() {
    define("ELIMINAR_CAMBIO_MEDIDOR", 3);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_cambio   = $this->request->getPost("id_cambio");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosCambioMedidor = [
     "id"         => $id_cambio,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->cambio_medidor->save($datosCambioMedidor)) {
      echo OK;
      $estado_traza = ELIMINAR_CAMBIO_MEDIDOR;
      $this->guardar_traza($id_cambio, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el cambio medidor";
    }
  }

  public function guardar_traza($id_cambio, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_cambio"   => $id_cambio,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->cambio_medidor_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_cambio_medidor_traza() {
    $this->validar_sesion();
    echo view("Formularios/cambio_medidor_traza");
  }

  public function datatable_cambio_medidor_traza($id_cambio) {
    $this->validar_sesion();
    echo $this->cambio_medidor_traza->datatable_cambio_medidor_traza($this->db, $id_cambio);
  }
}

?>