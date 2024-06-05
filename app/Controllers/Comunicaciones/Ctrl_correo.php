<?php

namespace App\Controllers\Comunicaciones;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_socios;
use App\Models\Comunicaciones\Md_correos;
use App\Models\Comunicaciones\Md_correos_detalle;

class Ctrl_correo extends BaseController {

 
  protected $socios;
  protected $correos;
  protected $correos_detalle;
  protected $sesión;
  protected $db;
  protected $error = "";

  public function __construct() {
    
    $this->socios            = new Md_socios();
    $this->correos_detalle   = new Md_correos_detalle();
    $this->correos           = new Md_correos();
    $this->sesión            = session();
    $this->db                = \Config\Database::connect();


  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_correos() {
    $this->validar_sesion();
    echo $this->correos->datatable_correos($this->db,$this->sesión->id_apr_ses);
  }

  public function datatable_detalle($id_correo) {
    // echo $id_correo;
    $this->validar_sesion();
    echo $this->correos->datatable_detalle($this->db,$id_correo);
  }

  public function envia_mail($arr_socios){
        $this->validar_sesion(); 
        $asunto = $this->request->getPost("asunto");
        $cuerpo = $this->request->getPost("cuerpo");
        $socios = explode(",", $arr_socios);
        $id_apr = $this->sesión->id_apr_ses;
        $nombreAPR = $this->sesión->apr_ses;
        $fecha      = date("Y-m-d H:i:s");
        $id_usuario = $this->sesión->id_usuario_ses;
        
        $datosCorreo=['fecha'=>$fecha,
                      'asunto'=>$asunto ,
                      'cuerpo'=>$cuerpo ,
                      'id_usuario'=>$id_usuario ,
                      'id_apr'=>$id_apr ];
        
        if($this->correos->save($datosCorreo)){

            $obtener_id = $this->correos->select("max(id) as id_correo")
                                   ->first();
            $id_correo   = $obtener_id["id_correo"];

          
            foreach ($socios as $id_socio) {

                    $datosSocios = $this->socios
                    ->select("concat(socios.rut, '-', socios.dv) as rut_socio")
                    ->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre_socio")
                    ->select("concat(socios.calle, ', ', socios.numero, ', ', socios.resto_direccion) as direccion")
                    ->select("socios.rol")
                    ->select("socios.id_comuna")
                    ->select("ifnull(socios.email,'--') as email")
                    ->where("socios.id", $id_socio)
                    ->first();          

                    $nombre_socio=$datosSocios['nombre_socio'];
                    $rut_socio=$datosSocios['rut_socio'];
                    $email_socio=$datosSocios['email'];

                  $this->email = \Config\Services::email();
                
                  $subject = $asunto;
                  $message = '<p>¡Hola '.$nombre_socio.'!<br>
                            Tu APR :'.$nombreAPR.' Tiene información importante para tí:<br><br>
                            <i>'.$cuerpo.'</i><br><br>
                            ¡Este es un correo automatico no responder!';

                

                  $this->email->setTo($email_socio);

                  $this->email->setFrom("boletas@softwareapr.cl", "Informaciones APR");


                  $this->email->setSubject($subject);
                  $this->email->setMessage($message);

                  $detalle_correo=['id_correo'=>$id_correo,'id_socio'=>$id_socio];
                  
                  

                  if ($this->email->send()){
                      $this->correos_detalle->save($detalle_correo);
                  }

            }

            echo 1;
        }else{
          echo 0;
        }

  

  }


}