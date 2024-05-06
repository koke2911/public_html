<?php

namespace App\Controllers\Configuracion;

use App\ThirdParty\Touchef;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Configuracion\Md_apr_traza;

class Ctrl_apr extends BaseController {

  protected $apr;
  protected $apr_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->apr       = new Md_apr();
    $this->apr_traza = new Md_apr_traza();
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function get_data_apr($id) {
    $this->validar_sesion();

    $obtener_id = $this->apr->select("*")
                              ->where("id",$id)
                              ->first();
       
    // $credentials = $this->apr->touchef_credentials($this->db, $id);
    // $touchef     = new Touchef($credentials['rut'], $credentials['touchef_token'], $credentials['touchef_password']);
    echo json_encode($obtener_id);


  }

  public function datatable_apr() {
    $this->validar_sesion();

    echo $this->apr->datatable_apr($this->db);
  }

  public function guardar_apr() {
    $this->validar_sesion();
    define("CREAR_APR", 1);
    define("MODIFICAR_APR", 2);

    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_apr               = $this->request->getPost("id_apr");
    $rut_apr              = $this->request->getPost("rut_apr");
    $nombre_apr           = $this->request->getPost("nombre_apr");
    $hash_sii             = $this->request->getPost("hash_sii");
    $codigo_comercio      = $this->request->getPost("codigo_comercio");
    $id_comuna            = $this->request->getPost("id_comuna");
    $resto_direccion      = $this->request->getPost("resto_direccion");
    $tope_subsidio        = $this->request->getPost("tope_subsidio");
    $tope_subsidio50        = $this->request->getPost("tope_subsidio50");
    $fono                 = $this->request->getPost("fono");
    $economic_activity_id = $this->request->getPost('economic_activity_id');
    $email                = $this->request->getPost('email');
    $activity             = $this->request->getPost('activity');
    $resolution_date      = $this->request->getPost('resolution_date');
    $resolution_number    = $this->request->getPost('resolution_number');
    $calle                = $this->request->getPost('calle');
    $numero               = $this->request->getPost('numero');
    $website              = $this->request->getPost('website');
    $ultimo_folio         = $this->request->getPost('ultimo_folio');
    $clave_dete           = $this->request->getPost('clave_dete');
    $email_dte            = $this->request->getPost('email_dte');
    $clave_appoct            = $this->request->getPost('clave_appoct');
    $horas_extras            = $this->request->getPost('horas_extras');

    $rut_completo = explode("-", $rut_apr);
    $rut          = $rut_completo[0];
    $dv           = $rut_completo[1];
    $datosAPR     = [
     "nombre"               => $nombre_apr,
     "id_comuna"            => $id_comuna,
     "resto_direccion"      => $resto_direccion,
     "hash_sii"             => $hash_sii,
     "codigo_comercio"      => $codigo_comercio,
     "tope_subsidio"        => $tope_subsidio,
     "rut"                  => $rut,
     "dv"                   => $dv,
     "id_usuario"           => $id_usuario,
     "fecha"                => $fecha,
     "fono"                 => $fono,
     'economic_activity_id' => $economic_activity_id,
     'email'                => $email,
     'activity'             => $activity,
     'resolution_date'      => $resolution_date,
     'resolution_number'    => $resolution_number,
     'calle'                => $calle,
     'numero'               => $numero,
     'website'              => $website, 
     'email_dte'            =>$email_dte,    
     'clave_dete'           => $clave_dete,
     'ultimo_folio'         => $ultimo_folio,
     'clave_appoct'         => $clave_appoct,
     'horas_extras'         => $horas_extras,
     "tope_subsidio50"      => $tope_subsidio50
    ];

    // print_r($datosAPR);

    if ($id_apr != "") {
      $estado_traza   = MODIFICAR_APR;
      $datosAPR["id"] = $id_apr;
    } else {
      $estado_traza = CREAR_APR;
    }

    if ($this->apr->save($datosAPR)) {

      
      // $credentials = $this->apr->touchef_credentials($this->db, $id_apr);

      // $touchef     = new Touchef($credentials['rut'], $credentials['touchef_token'], $credentials['touchef_password']);

      
      // if ($estado_traza == MODIFICAR_APR) {
      //   $touchef->update_client($id_comuna, $economic_activity_id, $nombre_apr, $rut . $dv, $activity, $calle . " " . $numero, $resolution_date, $resolution_number, $email, $website);
      // } else {
      //   $touchef->create_client($id_comuna, $economic_activity_id, $nombre_apr, $rut . $dv, $activity, $calle . " " . $numero, $resolution_date, $resolution_number, $email, $website);
      // }
      echo OK;

      if ($id_apr == "") {
        $obtener_id = $this->apr->select("max(id) as id_apr")
                                ->first();
        $id_apr     = $obtener_id["id_apr"];
      }

      $this->guardar_traza($id_apr, $estado_traza, "");
    } else {
      echo "Error al guardar los datos de la APR";
    }
  }

  public function guardar_traza($id_apr, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_apr"      => $id_apr,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->apr_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_apr_traza() {
    $this->validar_sesion();
    echo view("Configuracion/apr_traza");
  }

  public function datatable_apr_traza($id_apr) {
    $this->validar_sesion();
    echo $this->apr_traza->datatable_apr_traza($this->db, $id_apr);
  }

  public function v_importar_logo() {
    $this->validar_sesion();
    echo view("Configuracion/apr_importar_logo");
  }

  public function v_importar_certificado() {
    $this->validar_sesion();
    echo view("Configuracion/apr_importar_certificado");
  }

  public function v_importar_datos($tipo) {
    // echo $tipo;
    $datos = ["tipo" => $tipo];
    $this->validar_sesion();
    echo view("Configuracion/carga_masiva_socios", $datos);
    
  }
}