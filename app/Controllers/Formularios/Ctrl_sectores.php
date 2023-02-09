<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_sectores;
use App\Models\Formularios\Md_sector_traza;

class Ctrl_sectores extends BaseController {

  protected $sectores;
  protected $sector_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->sectores     = new Md_sectores();
    $this->sector_traza = new Md_sector_traza();
    $this->sesión       = session();
    $this->db           = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_sectores() {
    $this->validar_sesion();
    echo $this->sectores->datatable_sectores($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_sector() {
    $this->validar_sesion();
    define("CREAR_SECTOR", 1);
    define("MODIFICAR_SECTOR", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_sector = $this->request->getPost("id_sector");
    $nombre    = $this->request->getPost("nombre");

    $datosSector = [
     "nombre"     => $nombre,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "id_apr"     => $id_apr,
    ];

    if ($id_sector != "") {
      $estado_traza      = MODIFICAR_SECTOR;
      $datosSector["id"] = $id_sector;
    } else {
      $estado_traza          = CREAR_SECTOR;
      $datosSector["estado"] = $estado;
    }

    if ($this->sectores->save($datosSector)) {
      echo OK;

      if ($id_sector == "") {
        $obtener_id = $this->sectores->select("max(id) as id_sector")
                                     ->first();
        $id_sector  = $obtener_id["id_sector"];
      }

      $this->guardar_traza($id_sector, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del sector";
    }
  }

  public function eliminar_sector() {
    define("ELIMINAR_SECTOR", 3);
    define("RECICLAR_SECTOR", 4);
    define("ELIMINAR", 0);
    define("RECICLAR", 1);
    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_sector   = $this->request->getPost("id_sector");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $datosSector = [
     "id"         => $id_sector,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado,
    ];

    if ($this->sectores->save($datosSector)) {
      echo OK;

      if ($estado == ELIMINAR) {
        $estado_traza = ELIMINAR_SECTOR;
      } else {
        $estado_traza = RECICLAR_SECTOR;
      }

      $this->guardar_traza($id_sector, $estado_traza, $observacion);
    } else {
      echo "Error al actualizar el sector";
    }
  }

  public function guardar_traza($id_sector, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_sector"   => $id_sector,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->sector_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_sector_traza() {
    $this->validar_sesion();
    echo view("Formularios/sector_traza");
  }

  public function datatable_sector_traza($id_sector) {
    $this->validar_sesion();
    echo $this->sector_traza->datatable_sector_traza($this->db, $id_sector);
  }

  public function v_sector_reciclar() {
    $this->validar_sesion();
    echo view("Formularios/sector_reciclar");
  }

  public function datatable_sector_reciclar() {
    $this->validar_sesion();
    echo $this->sectores->datatable_sector_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>