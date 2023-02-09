<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_ubicaciones_producto_traza extends Model {

  protected $table      = 'ubicaciones_producto_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_ubicacion',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_ubicaciones_producto_traza($db, $id_ubicacion) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							ubicaciones_producto_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join ubicaciones_producto_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_ubicacion = $id_ubicacion";

    $query                      = $db->query($consulta);
    $ubicaciones_producto_traza = $query->getResultArray();

    foreach ($ubicaciones_producto_traza as $key) {
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