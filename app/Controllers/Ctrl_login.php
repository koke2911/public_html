<?php

namespace App\Controllers;

use App\Models\Configuracion\Md_apr;
use App\Models\Configuracion\Md_usuarios;
use App\Models\Configuracion\Md_usuario_traza;

class Ctrl_login extends BaseController {

  protected $usuarios;
  protected $reglasLogin;
  protected $reglasActivar;
  protected $usuario_traza;

  public function __construct() {
    $this->usuarios      = new Md_usuarios();
    $this->apr           = new Md_apr();
    $this->usuario_traza = new Md_usuario_traza();

    $this->reglasLogin = [
     "usuario"  => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "password" => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ]
    ];

    $this->reglasActivar = [
     "clave_activar" => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "clave_repetir" => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ]
    ];
  }

  public function valida() {
    if ($this->request->getMethod() == "post" && $this->validate($this->reglasLogin)) {
      $usuario  = $this->request->getPost("usuario");
      $password = $this->request->getPost("password");

      $datosUsuario = $this->usuarios->where("usuario", $usuario)
                                     ->first();
      if ($datosUsuario != NULL) {
        if (password_verify($password, $datosUsuario["clave"])) {
          $datosApr = $this->apr->select("*")
                                ->where("id", $datosUsuario["id_apr"])
                                ->first();
          define("ACTIVADO", 1);
          define("BLOQUEADO", 2);
          define("PENDIENTE", 0);
          switch ($datosUsuario["estado"]) {
            case ACTIVADO:
              define("OK", 1);

              $datosSesion = [
               "id_usuario_ses" => $datosUsuario["id"],
               "nombres_ses"    => $datosUsuario["nombres"],
               "ape_pat_ses"    => $datosUsuario["ape_paterno"],
               "ape_mat_ses"    => $datosUsuario["ape_materno"],
               "id_apr_ses"     => $datosUsuario["id_apr"],
               "apr_ses"        => $datosApr["nombre"],
               "hash_apr_ses"   => $datosApr["hash_sii"],
               "rut_apr_ses"    => $datosApr["rut"],
               "dv_apr_ses"     => $datosApr["dv"],
               "es_admin"       => $datosUsuario["es_admin"],
               "tipo_integracion_ses" => $datosApr["tipo_integracion"]
              ];

              $session = session();
              $session->set($datosSesion);

              echo OK;
              break;
            case BLOQUEADO:
              echo "Usuario BLOQUEADO";
              break;
            case PENDIENTE:
              echo "Active su clave";
              break;
          }
        } else {
            echo 1;
          echo "Usuario o clave errónea";
        }
      } else {
            echo 2;
        echo "Usuario o clave errónea";
      }
    } else {
        echo 3;
      echo "Usuario o clave errónea";
    }
  }

  public function activar_cuenta() {
    if ($this->request->getMethod() == "post" && $this->validate($this->reglasActivar)) {
      $clave_activar = $this->request->getPost("clave_activar");
      $usu_cod       = $this->request->getPost("usu_cod");
      $datosUsuario  = $this->usuarios->select("id")
                                      ->where("usuario", $usu_cod)
                                      ->first();

      define("OK", 1);
      define("ACTIVADO", 1);
      define("ACTIVAR_CLAVE", 7);
      $estado       = ACTIVADO;
      $estado_traza = ACTIVAR_CLAVE;

      $date       = date('Y-m-d');
      $time       = date('H:i:s');
      $fecha_reg  = $date . " " . $time;
      $clave_hash = password_hash($clave_activar, PASSWORD_DEFAULT);

      $data = [
       "clave"      => $clave_hash,
       "usuario"    => $usu_cod,
       "id_usuario" => $datosUsuario["id"],
       "fecha"      => $fecha_reg,
       "estado"     => $estado
      ];

      if ($this->usuarios->update($datosUsuario["id"], $data)) {
        echo OK;
        $this->guardar_traza($datosUsuario["id"], $estado_traza, "");
      } else {
        echo "Error al activar la clave";
      }
    }
  }

  public function guardar_traza($id_usuario, $estado, $observacion) {
    $fecha = date("Y-m-d H:i:s");

    $datosTraza = [
     "id_usuario"  => $id_usuario,
     "estado"      => $estado,
     "observacion" => $observacion,
     "usuario_reg" => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->usuario_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function logout() {
    $sesión = session();
    $sesión->destroy();

    return redirect()->to(base_url());
  }
}

?>