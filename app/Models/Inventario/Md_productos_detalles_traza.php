<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_productos_detalles_traza extends Model {

  protected $table      = 'productos_detalles_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_detalle',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_productos_detalles_traza($db, $id_detalle) {
    $consulta = "SELECT 
							ifnull(mte.estado_producto, 'No Registrado') as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							productos_detalles_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							left join estados_producto mte on mte.id = mt.estado
						where 
							mt.id_detalle = $id_detalle";

    $query           = $db->query($consulta);
    $productos_traza = $query->getResultArray();

    foreach ($productos_traza as $key) {
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