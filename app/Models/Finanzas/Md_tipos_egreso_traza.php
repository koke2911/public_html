<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_tipos_egreso_traza extends Model {

  protected $table      = 'tipos_egreso_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_tipo_egreso',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_tipos_egreso_traza($db, $id_tipo_egreso) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							tipos_egreso_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join tipos_egreso_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_tipo_egreso = $id_tipo_egreso";

    $query              = $db->query($consulta);
    $tipos_egreso_traza = $query->getResultArray();

    foreach ($tipos_egreso_traza as $key) {
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