<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_convenio_traza extends Model {

  protected $table      = 'convenio_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_convenio',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_convenio_traza($db, $id_convenio) {
    $consulta = "SELECT 
							cte.glosa as estado,
							ifnull(ct.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(ct.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							convenio_traza ct
							inner join usuarios u on u.id = ct.id_usuario
							inner join convenio_traza_estados cte on cte.id = ct.estado
						where 
							ct.id_convenio = $id_convenio";

    $query          = $db->query($consulta);
    $convenio_traza = $query->getResultArray();

    foreach ($convenio_traza as $key) {
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