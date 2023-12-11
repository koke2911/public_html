<?php

namespace App\Controllers\RecursosH;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_funcionarios;


class Ctrl_vacaciones extends BaseController {

  protected $funcionarios;
  protected $sesión;
  protected $db;
  protected $vacaciones;


  public function __construct() {
    $this->funcionarios       = new Md_funcionarios();
    $this->vacaciones         = new Md_vacaciones();
    $this->sesión             = session();
    $this->db                 = \Config\Database::connect();
    
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function guardar_vacaciones(){
    $this->validar_sesion();
    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
        
    $id_funcionario=$this->request->getPost("id_funcionario");
    $desde=$this->request->getPost("desde");
    $hasta=$this->request->getPost("hasta");
    $dias=$this->request->getPost("dias");

    echo $id_funcionario .' '.$desde .' '.$hasta .' '.$dias;

    
    
    

  }

  // public function datatable_liquidaciones() {
  //   $this->validar_sesion();
  //   echo $this->liquidaciones->datatable_liquidaciones($this->db, $this->sesión->id_apr_ses);
  // }

  


}

?>
