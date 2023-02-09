<?php

namespace App\Controllers\Configuracion;

use App\ThirdParty\Touchef;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Configuracion\Md_apr_traza;

class Ctrl_importar_certificado extends BaseController {

  protected $sesión;
  protected $db;
  protected $apr_traza;

  public function __construct() {
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
    $this->apr_traza = new Md_apr_traza();
    $this->apr       = new Md_apr();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function mostrar_certificado($id_apr) {
    $this->validar_sesion();
    if ($this->request->getMethod() == "get") {
      $credentials = $this->apr->touchef_credentials($this->db, $id_apr);
      $touchef     = new Touchef($credentials['rut'], $credentials['touchef_token'], $credentials['touchef_password']);
      $response    = $touchef->certificate();
      echo json_encode($response);
    }
  }

  public function importar_certificado($id_apr) {
    $this->validar_sesion();

    if ($this->request->getMethod() == "post") {
      $file     = $this->request->getFile("certificate");
      $password = $this->request->getPost('password');

      if (!$file->isValid()) {
        throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
      } else {
        $credentials = $this->apr->touchef_credentials($this->db, $id_apr);
        $touchef     = new Touchef($credentials['rut'], $credentials['touchef_token'], $credentials['touchef_password']);
        $touchef->update_certificate($file->getTempName(), $password);
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
      }
    } else {
      echo "No se han recibido los datos del certificado, favor actualice el sitio he intente nuevamente";
    }
  }
}