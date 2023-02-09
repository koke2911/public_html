<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_arranque_traza extends Model {

  protected $table      = 'arranque_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_arranque',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_arranque_traza($db, $id_arranque) {
    $consulta = "SELECT 
							ate.glosa as estado,
							ifnull(at.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(at.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							arranque_traza at
							inner join usuarios u on u.id = at.id_usuario
							inner join arranque_traza_estados ate on ate.id = at.estado
						where 
							at.id_arranque = $id_arranque";

    $query          = $db->query($consulta);
    $arranque_traza = $query->getResultArray();

    foreach ($arranque_traza as $key) {
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