<?php namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_caja_detalle extends Model {

  protected $table      = 'caja_detalle';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_caja',
   'id_metros'
  ];

  public function datatable_detalle_pago($db, $id_caja) {
    $consulta = "SELECT 
							m.id as id_metros,
							total_mes as deuda,
							date_format(m.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento
						from 
							caja_detalle cd
						    inner join metros m on cd.id_metros = m.id
						where 
							id_caja = ?";

    $query        = $db->query($consulta, [$id_caja]);
    $caja_detalle = $query->getResultArray();

    foreach ($caja_detalle as $key) {
      $row = [
       "id_metros"         => $key["id_metros"],
       "deuda"             => $key["deuda"],
       "fecha_vencimiento" => $key["fecha_vencimiento"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      return "{ \"data\": []}";
    }
  }
}

?>