<?php

namespace App\Controllers\Configuracion;

use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr_traza;

class Ctrl_importar_logo extends BaseController {

  protected $sesión;
  protected $db;
  protected $apr_traza;

  public function __construct() {
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
    $this->apr_traza = new Md_apr_traza();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function importar_logo($id_apr) {
    $this->validar_sesion();

    if ($this->request->getMethod() == "post") {
      $file = $this->request->getFile("archivos");

      if (!$file->isValid()) {
        throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
      } else {
        $file->move("../public/", $id_apr . ".png");

        if ($file->hasMoved()) {
          echo "[]";
          $fecha      = date("Y-m-d H:i:s");
          $id_usuario = $this->sesión->id_usuario_ses;

          $datosTraza = [
           "id_apr"     => $id_apr,
           "estado"     => 3,
           "id_usuario" => $id_usuario,
           "fecha"      => $fecha
          ];

          if (!$this->apr_traza->save($datosTraza)) {
            echo "Falló al guardar la traza";
          }
        } else {
          echo "Error al subir el logo";
        }
      }
    } else {
      echo "No se han recibido los datos del logo, favor actualice el sitio he intente nuevamente";
    }
  }
}

?>