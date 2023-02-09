<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_diametro;
use App\Models\Formularios\Md_medidores;
use App\Models\Formularios\Md_medidor_traza;

class Ctrl_medidores extends BaseController {

  protected $medidores;
  protected $medidor_traza;
  protected $diametro;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->medidores     = new Md_medidores();
    $this->medidor_traza = new Md_medidor_traza();
    $this->diametro      = new Md_diametro();
    $this->sesión        = session();
    $this->db            = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_medidores() {
    $this->validar_sesion();
    echo $this->medidores->datatable_medidores($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_medidor() {
    $this->validar_sesion();
    define("CREAR_MEDIDOR", 1);
    define("MODIFICAR_MEDIDOR", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_medidor  = $this->request->getPost("id_medidor");
    $numero      = $this->request->getPost("numero");
    $id_diametro = $this->request->getPost("id_diametro");
    $marca       = $this->request->getPost("marca");
    $tipo        = $this->request->getPost("tipo");

    $datosMedidor = [
     "numero"      => $numero,
     "id_diametro" => $id_diametro,
     "marca"       => $marca,
     "tipo"        => $tipo,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha,
     "id_apr"      => $id_apr
    ];

    if ($id_medidor != "") {
      $estado_traza       = MODIFICAR_MEDIDOR;
      $datosMedidor["id"] = $id_medidor;
    } else {
      $estado_traza = CREAR_MEDIDOR;
    }

    if ($this->medidores->save($datosMedidor)) {
      echo OK;

      if ($id_medidor == "") {
        $obtener_id = $this->medidores->select("max(id) as id_medidor")
                                      ->first();
        $id_medidor = $obtener_id["id_medidor"];
      }

      $this->guardar_traza($id_medidor, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del medidor";
    }
  }

  public function eliminar_medidor() {
    define("ELIMINAR_MEDIDOR", 3);
    define("RECICLAR_MEDIDOR", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_medidor  = $this->request->getPost("id_medidor");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosMedidor = [
     "id"         => $id_medidor,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->medidores->save($datosMedidor)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_MEDIDOR;
      } else {
        $estado_traza = RECICLAR_MEDIDOR;
      }

      $this->guardar_traza($id_medidor, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el medidor";
    }
  }

  public function guardar_traza($id_medidor, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_medidor"  => $id_medidor,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->medidor_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_medidor_traza() {
    $this->validar_sesion();
    echo view("Formularios/medidor_traza");
  }

  public function datatable_medidor_traza($id_medidor) {
    $this->validar_sesion();
    echo $this->medidor_traza->datatable_medidor_traza($this->db, $id_medidor);
  }

  public function v_medidor_reciclar() {
    $this->validar_sesion();
    echo view("Formularios/medidor_reciclar");
  }

  public function datatable_medidor_reciclar() {
    $this->validar_sesion();
    echo $this->medidores->datatable_medidor_reciclar($this->db, $this->sesión->id_apr_ses);
  }

  public function llenar_cmb_diametro() {
    $this->validar_sesion();
    $datos_diametro = $this->diametro->select("id")
                                     ->select("glosa as diametro")
                                     ->where("estado", 1)
                                     ->findAll();

    $data = [];

    foreach ($datos_diametro as $key) {
      $row = [
       "id"       => $key["id"],
       "diametro" => $key["diametro"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }
}

?>