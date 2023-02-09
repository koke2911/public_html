<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_costo_metros_traza extends Model {

  protected $table      = 'costo_metros_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_costo_metros',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_costo_metros_traza($db, $id_costo_metros) {
    $consulta = "SELECT 
							cmte.glosa as estado,
						    ifnull(cmt.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(cmt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							costo_metros_traza cmt
						    inner join usuarios u on u.id = cmt.id_usuario
						    inner join costo_metros_traza_estados cmte on cmte.id = cmt.estado
						where 
							cmt.id_costo_metros = $id_costo_metros";

    $query              = $db->query($consulta);
    $costo_metros_traza = $query->getResultArray();

    foreach ($costo_metros_traza as $key) {
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