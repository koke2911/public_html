<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_desinfeccion;

class Ctrl_desinfeccion extends BaseController {
 
  protected $desinfecciones;  
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->desinfecciones = new Md_desinfeccion();
    $this->sesión         = session();
    $this->db             = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function v_buscar_socio($origen) {
    $this->validar_sesion();
    $data = ['origen' => $origen];
    echo view("Formularios/buscar_socio", $data);
  }

  public function guardar_desinfeccion(){
         $this->validar_sesion();

        $id= $this->request->getPost("id");
        $dia= $this->request->getPost("dia");
        $hora_ap= $this->request->getPost("hora_ap");
        $cloro_ap= $this->request->getPost("cloro_ap");
        $id_socio1= $this->request->getPost("id_socio1");
        $hora_socio1= $this->request->getPost("hora_socio1");
        $cloro_socio1= $this->request->getPost("cloro_socio1");
        $id_socio2= $this->request->getPost("id_socio2");
        $hora_socio2= $this->request->getPost("hora_socio2");
        $cloro_socio2= $this->request->getPost("cloro_socio2");
        $frecuencia= $this->request->getPost("frecuencia");
        $desp= $this->request->getPost("desp");
        $medidor_caudal= $this->request->getPost("medidor_caudal");
        $electricidad= $this->request->getPost("electricidad");
        $horometro= $this->request->getPost("horometro");

        $fecha      = date("Y-m-d H:i:s");
        $id_usuario = $this->sesión->id_usuario_ses;
        $id_apr     = $this->sesión->id_apr_ses;

        $datosDesinfeccion = [
          'id_apr'=>$id_apr,
          'dia'=>date_format(date_create($dia), 'Y-m-d'),
          'hora_ap'=>$hora_ap,
          'cloro_ap'=>$cloro_ap,
          'id_socio1'=>$id_socio1,
          'hora_socio1'=>$hora_socio1,
          'cloro_socio1'=>$cloro_socio1,
          'id_socio2'=>$id_socio2,
          'hora_socio2'=>$hora_socio2,
          'cloro_socio2'=>$cloro_socio2,
          'frecuencia'=>$frecuencia,
          'desp'=>$desp,
          'medidor_caudal'=>$medidor_caudal,
          'electricidad'=>$electricidad,
          'horometro'=>$horometro,
          'id_usuario'=>$id_usuario,
          'registrado'=>date_format(date_create($fecha), 'Y-m-d'),
          'estado'=>1
        ];
      
          if($id!=""){
            $datosDesinfeccion["id"] = $id;
          }
         
          if ($this->desinfecciones->save($datosDesinfeccion)) {
            echo 1;
          }else{
            echo 'no se pudo guardar el formulario';
          }
  }

  public function eliminar_registro(){
    $this->validar_sesion();
    $id= $this->request->getPost("id");
    $estado= $this->request->getPost("estado");

    $datosDesinfeccion = [
          'id'=>$id,     
          'estado'=>0
    ];

    if ($this->desinfecciones->save($datosDesinfeccion)) {
        echo 1;
      }else{
        echo 'no se pudo eliminar el registro';
      }
  }

  public function datatable_desinfecciones(){
     $this->validar_sesion();
     echo $this->desinfecciones->datatable_desinfecciones($this->db, $this->sesión->id_apr_ses);
  }

}

?>