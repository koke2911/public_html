<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_servicios_traza extends Model {

  protected $table      = 'servicios_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_servicio',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_servicios_traza($db, $id_servicio) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							servicios_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join servicios_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_servicio = $id_servicio";

    $query           = $db->query($consulta);
    $servicios_traza = $query->getResultArray();

    foreach ($servicios_traza as $key) {
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