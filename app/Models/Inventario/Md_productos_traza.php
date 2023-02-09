<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_productos_traza extends Model {

  protected $table      = 'productos_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_producto',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_productos_traza($db, $id_producto) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							productos_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join productos_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_producto = $id_producto";

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