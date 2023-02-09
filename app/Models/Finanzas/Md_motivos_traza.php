<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_motivos_traza extends Model {

  protected $table      = 'motivos_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_motivo',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_motivos_traza($db, $id_motivo) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							motivos_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join motivos_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_motivo = $id_motivo";

    $query         = $db->query($consulta);
    $motivos_traza = $query->getResultArray();

    foreach ($motivos_traza as $key) {
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