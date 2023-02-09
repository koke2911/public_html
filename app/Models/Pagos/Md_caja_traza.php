<?php namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_caja_traza extends Model {

  protected $table      = 'caja_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_caja',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_pago_traza($db, $id_caja) {
    $consulta = "SELECT 
							cte.glosa as estado,
							ifnull(ct.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(ct.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							caja_traza ct
							inner join usuarios u on u.id = ct.id_usuario
							inner join caja_traza_estados cte on cte.id = ct.estado
						where 
							ct.id_caja = $id_caja";

    $query      = $db->query($consulta);
    $caja_traza = $query->getResultArray();

    foreach ($caja_traza as $key) {
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