<?php namespace App\Models\Consumo;

use CodeIgniter\Model;

class Md_metros_traza extends Model {

  protected $table      = 'metros_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_metros',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_metros_traza($db, $id_metros) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							metros_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join metros_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_metros = $id_metros";

    $query        = $db->query($consulta);
    $metros_traza = $query->getResultArray();

    foreach ($metros_traza as $key) {
      $row = [
       "estado"      => $key["estado"],
       "observacion" => $key["observacion"],
       "usuario"     => $key["usuario"],
       "fecha"       => $key["fecha"]
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

?>