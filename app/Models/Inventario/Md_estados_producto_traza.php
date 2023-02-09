<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_estados_producto_traza extends Model {

  protected $table      = 'estados_producto_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_estado',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_estados_producto_traza($db, $id_estado) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							estados_producto_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join estados_producto_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_estado = $id_estado";

    $query                  = $db->query($consulta);
    $estados_producto_traza = $query->getResultArray();

    foreach ($estados_producto_traza as $key) {
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