<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_medidor_traza extends Model {

  protected $table      = 'medidor_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_medidor',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_medidor_traza($db, $id_medidor) {
    $consulta = "SELECT 
							mte.glosa as estado,
						    ifnull(mt.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							medidor_traza mt
						    inner join usuarios u on u.id = mt.id_usuario
						    inner join medidor_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_medidor = $id_medidor";

    $query         = $db->query($consulta);
    $medidor_traza = $query->getResultArray();

    foreach ($medidor_traza as $key) {
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