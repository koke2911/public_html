<?php

namespace App\Controllers\Configuracion;

use App\ThirdParty\Touchef;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Configuracion\Md_comunas;
use App\Models\Configuracion\Md_usuarios;
use App\Models\Configuracion\Md_regiones;
use App\Models\Configuracion\Md_provincias;
use App\Models\Configuracion\Md_usuario_traza;
use App\Models\Configuracion\Md_permisos_detalle;
use App\Models\Configuracion\Md_permisos_usuario;

class Ctrl_usuarios extends BaseController {

  protected $usuarios;
  protected $apr;
  protected $regiones;
  protected $provincias;
  protected $comunas;
  protected $permisos_detalle;
  protected $permisos_usuario;
  protected $usuario_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->usuarios         = new Md_usuarios();
    $this->apr              = new Md_apr();
    $this->regiones         = new Md_regiones();
    $this->provincias       = new Md_provincias();
    $this->comunas          = new Md_comunas();
    $this->permisos_detalle = new Md_permisos_detalle();
    $this->permisos_usuario = new Md_permisos_usuario();
    $this->usuario_traza    = new Md_usuario_traza();
    $this->sesión           = session();
    $this->db               = \Config\Database::connect();

    $this->reglasUsuario = [
     "usuario" => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "id_apr"  => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "nombres" => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "apepat"  => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "apemat"  => [
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ]
    ];
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_usuarios() {
    $this->validar_sesion();
    //echo $this->usuarios->datatable_usuarios($this->db);
    echo $this->usuarios->datatable_usuarios($this->db, $this->sesión->id_apr_ses,$this->sesión->es_admin);
  }

  public function llenar_cmb_apr() {
    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;
    $es_admin=$this->sesión->es_admin;

    if($es_admin!=0){
      $datos_apr = $this->apr->select("id")
                           ->select("nombre as apr")
                           ->where("estado",1)
                           ->findAll();
    }else{
      $datos_apr = $this->apr->select("id")
                           ->select("nombre as apr")->where("id",$id_apr)
                           ->findAll();
    }

    

    $data = [];

    foreach ($datos_apr as $key) {
      $row = [
       "id"  => $key["id"],
       "apr" => $key["apr"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_economic_activities() {
    $this->validar_sesion();
    $credenciales = $this->apr->touchef_credentials($this->db, $this->sesión->id_apr_ses);
    $touchef      = new Touchef($credenciales['rut'], $credenciales['touchef_token'], $credenciales['touchef_password']);
    $data         = $touchef->economic_activities();
    echo json_encode($data);
  }

  public function llenar_cmb_region() {
    $this->validar_sesion();
    $datos_regiones = $this->regiones->select("id")
                                     ->select("nombre as region")
                                     ->findAll();

    $data = [];

    foreach ($datos_regiones as $key) {
      $row = [
       "id"     => $key["id"],
       "region" => $key["region"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_provincia($id_region) {
    $this->validar_sesion();

    if ($id_region != "null") {
      $datos_provincias = $this->provincias->select("id")
                                           ->select("nombre as provincia")
                                           ->where("id_region", $id_region)
                                           ->findAll();
    } else {
      $datos_provincias = $this->provincias->select("id")
                                           ->select("nombre as provincia")
                                           ->findAll();
    }

    $data = [];

    foreach ($datos_provincias as $key) {
      $row = [
       "id"        => $key["id"],
       "provincia" => $key["provincia"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function llenar_cmb_comuna($id_provincia) {
    $this->validar_sesion();
    if ($id_provincia != "null") {
      $datos_comunas = $this->comunas->select("id")
                                     ->select("nombre as comuna")
                                     ->where("id_provincia", $id_provincia)
                                     ->findAll();
    } else {
      $datos_comunas = $this->comunas->select("id")
                                     ->select("nombre as comuna")
                                     ->findAll();
    }

    $data = [];

    foreach ($datos_comunas as $key) {
      $row = [
       "id"     => $key["id"],
       "comuna" => $key["comuna"]
      ];

      $data[] = $row;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }

  public function guardar_usuario() {
    $this->validar_sesion();
    define("CREAR_USUARIO", 1);
    define("MODIFICAR_USUARIO", 2);

    if ($this->request->getMethod() == "post" && $this->validate($this->reglasUsuario)) {
      define("PENDIENTE", 0);
      define("OK", 1);

      $fecha       = date("Y-m-d H:i:s");
      $usuario_reg = $this->sesión->id_usuario_ses;
      $clave       = $this->generar_clave();
      $clave_hash  = password_hash($clave, PASSWORD_DEFAULT);
      $estado      = PENDIENTE;

      $id_usuario      = $this->request->getPost("id_usuario");
      $usuario         = $this->request->getPost("usuario");
      $id_apr          = $this->request->getPost("id_apr");
      $nombres         = $this->request->getPost("nombres");
      $apepat          = $this->request->getPost("apepat");
      $apemat          = $this->request->getPost("apemat");
      $id_comuna       = $this->request->getPost("id_comuna");
      $calle           = $this->request->getPost("calle");
      $numero          = $this->request->getPost("numero");
      $resto_direccion = $this->request->getPost("resto_direccion");
      $punto_blue      = $this->request->getPost("punto_blue");

      if ($id_comuna == "") {
        $id_comuna = NULL;
      }

      $datosUsuario = [
       "usuario"         => $usuario,
       "id_apr"          => $id_apr,
       "nombres"         => $nombres,
       "ape_paterno"     => $apepat,
       "ape_materno"     => $apemat,
       "calle"           => $calle,
       "numero"          => $numero,
       "resto_direccion" => $resto_direccion,
       "punto_blue"      => $punto_blue,
       "id_comuna"       => $id_comuna,
       "id_usuario"      => $usuario_reg,
       "fecha"           => $fecha
      ];

      if ($id_usuario != "") {
        $estado_traza       = MODIFICAR_USUARIO;
        $datosUsuario["id"] = $id_usuario;
      } else {
        $estado_traza           = CREAR_USUARIO;
        $datosUsuario["clave"]  = $clave_hash;
        $datosUsuario["estado"] = $estado;
      }

      if ($this->usuarios->save($datosUsuario)) {
        if ($id_usuario != "") {
          echo OK;
        } else {
          echo OK . "--" . $clave;
          $obtener_id = $this->usuarios->select("max(id) as id_usuario")
                                       ->first();
          $id_usuario = $obtener_id["id_usuario"];
        }
        $observacion = "";
        $this->guardar_traza($id_usuario, $estado_traza, $observacion);
      } else {
        echo "Error al guardar los datos del usuario";
      }
    } else {
      echo "Error en el envío de datos";
    }
  }

  public function generar_clave() {
    $cadena_base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $cadena_base .= '0123456789';
    $cadena_base .= '!@#%^&*()_,./<>?;:[]{}\|=+';

    $password = '';
    $limite   = strlen($cadena_base) - 1;

    for ($i = 0; $i < 20; $i ++) {
      $password .= $cadena_base[rand(0, $limite)];
    }

    return $password;
  }

  public function v_permisos() {
    $this->validar_sesion();
    echo view("Configuracion/usuario_permisos");
  }

  public function v_usuario_traza() {
    $this->validar_sesion();
    echo view("Configuracion/usuario_traza");
  }

  public function datatable_usuarios_permisos() {
    $this->validar_sesion();
    echo $this->permisos_detalle->datatable_usuarios_permisos($this->db);
  }

  public function data_permisos_usuario($id_usuario) {
    $this->validar_sesion();
    echo $this->permisos_detalle->data_permisos_usuario($this->db, $id_usuario);
  }

  public function guardar_permisos_usuario() {
    $this->validar_sesion();

    define("ACTIVAR", 1);
    define("DESACTIVAR", 0);
    define("OK", 1);
    define("ASIGNAR_PERMISO", 4);
    define("ELIMINAR_PERMISO", 5);

    $id_usuario  = $this->request->getPost("id_usuario");
    $id_permiso  = $this->request->getPost("id_permiso");
    $opcion      = $this->request->getPost("opcion");
    $fecha       = date("Y-m-d H:i:s");
    $usuario_reg = $this->sesión->id_usuario_ses;

    if ($opcion == "guardar") {
      $estado       = ACTIVAR;
      $estado_traza = ASIGNAR_PERMISO;
      $observacion  = "Id permiso agregado: $id_permiso";

      $consulta = $this->permisos_usuario->select("count(*) as filas")
                                         ->where("id_usuario", $id_usuario)
                                         ->where("id_permiso", $id_permiso)
                                         ->first();
      $filas    = $consulta["filas"];

      if ($filas > 0) {
        $insupd = "UPDATE permisos_usuario set estado = ?, usuario = ?, fecha = ? where id_usuario = ? and id_permiso = ?";
        $bind   = [
         $estado,
         $usuario_reg,
         $fecha,
         $id_usuario,
         $id_permiso
        ];

        $execute = $this->db->query($insupd, $bind);
      } else {
        $datosPermiso = [
         "id_usuario" => $id_usuario,
         "id_permiso" => $id_permiso,
         "estado"     => $estado,
         "usuario"    => $usuario_reg,
         "fecha"      => $fecha
        ];

        $execute = $this->permisos_usuario->insert($datosPermiso);
        if ($execute == 0) {
          $execute = 1;
        } else {
          $execute = 0;
        }
      }
    } else {
      $estado       = DESACTIVAR;
      $estado_traza = ELIMINAR_PERMISO;
      $observacion  = "Id permiso eliminado: $id_permiso";

      $insupd = "UPDATE permisos_usuario set estado = ?, usuario = ?, fecha = ? where id_usuario = ? and id_permiso = ?";
      $bind   = [
       $estado,
       $usuario_reg,
       $fecha,
       $id_usuario,
       $id_permiso
      ];

      $execute = $this->db->query($insupd, $bind);
    }

    if ($execute) {
      echo OK;
      $this->guardar_traza($id_usuario, $estado_traza, $observacion);
    } else {
      echo "Error al $opcion el permiso";
    }
  }

  public function resetear_clave() {
    $this->validar_sesion();
    define("PENDIENTE", 0);
    define("RESETEAR_CLAVE", 6);
    define("OK", 1);
    $id_usuario = $this->request->getPost("id_usuario");

    $fecha        = date("Y-m-d H:i:s");
    $usuario_reg  = $this->sesión->id_usuario_ses;
    $clave        = $this->generar_clave();
    $clave_hash   = password_hash($clave, PASSWORD_DEFAULT);
    $estado       = PENDIENTE;
    $estado_traza = RESETEAR_CLAVE;
    $observacion  = "";

    $datosUsuario = [
     "id"         => $id_usuario,
     "clave"      => $clave_hash,
     "estado"     => $estado,
     "id_usuario" => $usuario_reg,
     "fecha"      => $fecha
    ];

    if ($this->usuarios->save($datosUsuario)) {
      echo OK . "--" . $clave;
      $this->guardar_traza($id_usuario, $estado_traza, $observacion);
    } else {
      echo "Error al resetear la clave del usuario";
    }
  }

  public function guardar_traza($id_usuario, $estado, $observacion) {
    $this->validar_sesion();

    $fecha       = date("Y-m-d H:i:s");
    $usuario_reg = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_usuario"  => $id_usuario,
     "estado"      => $estado,
     "observacion" => $observacion,
     "usuario_reg" => $usuario_reg,
     "fecha"       => $fecha
    ];

    if (!$this->usuario_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function datatable_usuarios_traza($id_usuario) {
    $this->validar_sesion();

    echo $this->usuario_traza->datatable_usuario_traza($this->db, $id_usuario);
  }

  public function bloquear_usuario() {
    $this->validar_sesion();
    define("BLOQUEADO", 2);
    define("ACTIVAR", 1);
    define("BLOQUEAR_USUARIO", 3);
    define("DESBLOQUEAR_USUARIO", 8);
    define("OK", 1);
    $id_usuario  = $this->request->getPost("id_usuario");
    $observacion = $this->request->getPost("observacion");
    $opcion      = $this->request->getPost("opcion");

    $fecha       = date("Y-m-d H:i:s");
    $usuario_reg = $this->sesión->id_usuario_ses;

    if ($opcion == "bloquear") {
      $estado       = BLOQUEADO;
      $estado_traza = BLOQUEAR_USUARIO;
    } else {
      $estado       = ACTIVAR;
      $estado_traza = DESBLOQUEAR_USUARIO;
    }

    $datosUsuario = [
     "id"         => $id_usuario,
     "estado"     => $estado,
     "id_usuario" => $usuario_reg,
     "fecha"      => $fecha
    ];

    if ($this->usuarios->save($datosUsuario)) {
      echo OK;
      $this->guardar_traza($id_usuario, $estado_traza, $observacion);
    } else {
      echo "Error al resetear la clave del usuario";
    }
  }
}

?>