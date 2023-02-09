<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_apr_traza extends Model {

  protected $table      = 'apr_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_apr',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_apr_traza($db, $id_apr) {
    $consulta = "SELECT 
							ate.glosa as estado,
						    ifnull(at.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(at.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							apr_traza at
						    inner join usuarios u on u.id = at.id_usuario
						    inner join apr_traza_estados ate on ate.id = at.estado
						where 
							at.id_apr = $id_apr";

    $query     = $db->query($consulta, [$id_apr]);
    $apr_traza = $query->getResultArray();

    foreach ($apr_traza as $key) {
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