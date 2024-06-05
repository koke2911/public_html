<?php namespace App\Models\Comunicaciones;

use CodeIgniter\Model;

class Md_correos extends Model {

  protected $table      = 'correos';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
        'id',
        'fecha',
        'asunto',
        'cuerpo',
        'id_usuario',
        'id_apr',
  ];


  
  public function datatable_correos($db, $id_apr) {
    
    $consulta = "SELECT C.id,
                        c.fecha,
                        c.asunto,
                        c.cuerpo,
                        concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usuario
                 from correos c
                 inner join usuarios u on u.id=c.id_usuario
                 where c.id_apr=$id_apr";

    $query  = $db->query($consulta);
    $socios = $query->getResultArray();

    foreach ($socios as $key) {
      $row = [
        "id"         => $key["id"],
        "fecha"      => $key["fecha"],
        "asunto"     => $key["asunto"],
        "cuerpo"     => $key["cuerpo"],
        "id_usuario"    => $key["usuario"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      return "{ \"data\": [] }";
    }
  }

   public function datatable_detalle($db, $id_correo) {
    
    $consulta = "SELECT  s.id, 
                        concat(s.nombres,' ',s.ape_pat,' ',s.ape_mat) as socio
                 from correos_detalle c
                 inner join socios s on c.id_socio=s.id
                 where c.id_correo=$id_correo";

                //  echo $consulta;

    $query  = $db->query($consulta);
    $socios = $query->getResultArray();

    foreach ($socios as $key) {
      $row = [
        "id"         => $key["id"],
        "socio"      => $key["socio"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      return "{ \"data\": [] }";
    }
  }
}