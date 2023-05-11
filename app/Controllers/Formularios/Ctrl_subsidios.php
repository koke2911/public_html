<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_subsidios;
use App\Models\Formularios\Md_porcentajes;
use App\Models\Formularios\Md_subsidio_traza;

class Ctrl_subsidios extends BaseController {

  protected $subsidios;
  protected $subsidio_traza;
  protected $porcentajes;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->subsidios      = new Md_subsidios();
    $this->subsidio_traza = new Md_subsidio_traza();
    $this->porcentajes    = new Md_porcentajes();
    $this->sesión         = session();
    $this->db             = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_subsidios() {
    $this->validar_sesion();
    echo $this->subsidios->datatable_subsidios($this->db, $this->sesión->id_apr_ses);
  }

  public function llenar_cmb_porcentaje() {
    $this->validar_sesion();
    $datos_porcentaje = $this->porcentajes->select("id")
                                          ->select("glosa as porcentaje")
                                          ->where("estado", 1)
                                          ->findAll();

    $data = [];

    foreach ($datos_porcentaje as $key) {
      $row = [
       "id"         => $key["id"],
       "porcentaje" => $key["porcentaje"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function guardar_subsidio() {
    $this->validar_sesion();
    define("CREAR_SUBSIDIO", 1);
    define("MODIFICAR_SUBSIDIO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_subsidio     = $this->request->getPost("id_subsidio");
    $id_socio        = $this->request->getPost("id_socio");
    $n_decreto       = $this->request->getPost("n_decreto");
    $fecha_decreto   = $this->request->getPost("fecha_decreto");
    $fecha_caducidad = $this->request->getPost("fecha_caducidad");
    $porcentaje      = $this->request->getPost("porcentaje");
    $fecha_encuesta  = $this->request->getPost("fecha_encuesta");
    $puntaje         = $this->request->getPost("puntaje");
    $n_unico         = $this->request->getPost("n_unico");
    $d_unico         = $this->request->getPost("d_unico");
    $n_viviendas     = $this->request->getPost("n_viviendas");

    if ($n_decreto == "") {
      $n_decreto = NULL;
    }
    if ($fecha_decreto == "") {
      $fecha_decreto = NULL;
    } else {
      $fecha_decreto = date_format(date_create($fecha_decreto), 'Y-m-d');
    }
    if ($fecha_caducidad == "") {
      $fecha_caducidad = NULL;
    } else {
      $fecha_caducidad = date_format(date_create($fecha_caducidad), 'Y-m-d');
    }
    if ($fecha_encuesta == "") {
      $fecha_encuesta = NULL;
    } else {
      $fecha_encuesta = date_format(date_create($fecha_encuesta), 'Y-m-d');
    }
    if ($puntaje == "") {
      $puntaje = NULL;
    }
    if ($n_unico == "") {
      $n_unico = NULL;
    }
    if ($d_unico == "") {
      $d_unico = NULL;
    }

    if ($n_viviendas == "") {
      $n_viviendas = NULL;
    }

    $datosSubsidio = [
     "id_socio"        => $id_socio,
     "numero_decreto"  => $n_decreto,
     "fecha_decreto"   => $fecha_decreto,
     "fecha_caducidad" => $fecha_caducidad,
     "id_porcentaje"   => $porcentaje,
     "fecha_encuesta"  => $fecha_encuesta,
     "puntaje"         => $puntaje,
     "numero_unico"    => $n_unico,
     "digito_unico"    => $d_unico,
     "id_usuario"      => $id_usuario,
     "fecha"           => $fecha,
     "id_apr"          => $id_apr,
     "n_viviendas"     => $n_viviendas
    ];

    if ($id_subsidio != "") {
      $estado_traza        = MODIFICAR_SUBSIDIO;
      $datosSubsidio["id"] = $id_subsidio;
    } else {
      $estado_traza            = CREAR_SUBSIDIO;
      $datosSubsidio["estado"] = $estado;
    }

    if ($this->subsidios->save($datosSubsidio)) {
      echo OK;

      if ($id_subsidio == "") {
        $obtener_id  = $this->subsidios->select("max(id) as id_subsidio")
                                       ->first();
        $id_subsidio = $obtener_id["id_subsidio"];
      }

      $this->guardar_traza($id_subsidio, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del arranque";
    }
  }

  public function eliminar_subsidio() {
    define("ELIMINAR_SUBSIDIO", 3);
    define("RECICLAR_SUBSIDIO", 4);
    define("ELIMINAR", 0);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_subsidio = $this->request->getPost("id_subsidio");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosSubsidio = [
     "id"         => $id_subsidio,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->subsidios->save($datosSubsidio)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_SUBSIDIO;
      } else {
        $estado_traza = RECICLAR_SUBSIDIO;
      }

      $this->guardar_traza($id_subsidio, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el sector";
    }
  }

  public function guardar_traza($id_subsidio, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_subsidio" => $id_subsidio,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->subsidio_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_subsidio_traza() {
    $this->validar_sesion();
    echo view("Formularios/subsidio_traza");
  }

  public function datatable_subsidio_traza($id_subsidio) {
    $this->validar_sesion();
    echo $this->subsidio_traza->datatable_subsidio_traza($this->db, $id_subsidio);
  }

  public function v_subsidio_reciclar() {
    $this->validar_sesion();
    echo view("Formularios/subsidio_reciclar");
  }

  public function datatable_subsidio_reciclar() {
    $this->validar_sesion();
    echo $this->subsidios->datatable_subsidio_reciclar($this->db, $this->sesión->id_apr_ses);
  }

  public function v_buscar_socio($origen) {
    $this->validar_sesion();
    $data = ['origen' => $origen];
    echo view("Formularios/buscar_socio", $data);
  }

  public function datatable_buscar_socio() {
    $this->validar_sesion();
    echo $this->subsidios->datatable_buscar_socio($this->db, $this->sesión->id_apr_ses);
  }
}

?>