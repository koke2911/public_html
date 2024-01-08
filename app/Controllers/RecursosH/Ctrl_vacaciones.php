<?php

namespace App\Controllers\RecursosH;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_funcionarios;
use App\Models\RecursosH\Md_vacaciones;


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
    $disponibles=$this->request->getPost("disponibles");

    // echo $id_funcionario .' '.$desde .' '.$hasta .' '.$dias;

     $datosVaca = [
      "id_funcionario"=>$id_funcionario ,
      "desde"=> date_format(date_create($desde), 'Y-m-d'),
      "hasta"=> date_format(date_create($hasta), 'Y-m-d'),
      "cantidad"=>$dias ,
      "fecha_reg"=> date_format(date_create($fecha), 'Y-m-d'),
      "usu_reg"=>$id_usuario,
      "estado"=>1,
      "id_apr"=>$id_apr
    ];

     if ($this->vacaciones->save($datosVaca)) {

        $dispo=$disponibles-$dias;

        $datosFun = [
           "id"=>$id_funcionario ,
           "vacaciones_disponibles"=>$dispo          
        ];

        if ($this->funcionarios->save($datosFun)){
           echo 1;
        }else{
          echo "Error al  registrar vacaciones";
        }

     }else{
        echo "Error al  registrar vacaciones";
     }
    
  }

   public function datatable_vacaciones() {
    $this->validar_sesion();
    echo $this->vacaciones->datatable_vacaciones($this->db, $this->sesión->id_apr_ses);
  }

  public function anula_vacacion($id){

     $consulta = "SELECT
               f.vacaciones_disponibles,
               v.cantidad,
               f.id as id_funcionario
                from vacaciones v
                inner join funcionarios f on v.id_funcionario=f.id
                inner join usuarios u on u.id=v.usu_reg
                and v.id=$id and f.id=v.id_funcionario and v.estado=1 
                order by v.id asc";

    //             // echo $consulta;

    $query        = $this->db->query($consulta);
    $datosFucnionario = $query->getResultArray();

//     print_r($funcionarios);
// exit();
    $disponibles=$datosFucnionario[0]['vacaciones_disponibles'];
    $cantidad=$datosFucnionario[0]['cantidad'];
    $id_funcionario=$datosFucnionario[0]['id_funcionario'];

    $devolver=$cantidad+$disponibles;
    

      $datosVaca=[
         "id" => $id,
         "estado"=>0
      ];


       if ($this->vacaciones->save($datosVaca)) {

           $datosFun = [
               "id"=>$id_funcionario ,
               "vacaciones_disponibles"=>$devolver          
            ];

            // print_r($datosFun);

            if ($this->funcionarios->save($datosFun)){
               echo 1;
            }else{
              echo "Error al  registrar vacaciones";
            }
      }else{
        echo "Error al anular vacaciones";
      }

  }

 
}

?>
