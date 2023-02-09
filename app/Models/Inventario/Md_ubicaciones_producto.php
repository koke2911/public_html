<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_ubicaciones_producto extends Model {

  protected $table      = 'ubicaciones_producto';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'ubicacion',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_ubicaciones_producto($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_ubicacion,
                            m.ubicacion as ubicacion,
                            u.usuario,
                            date_format(m.fecha, '%d-%m-%Y') as fecha
						from 
							ubicaciones_producto m
							inner join usuarios u on u.id = m.id_usuario
						where
							m.id_apr = ? and
							m.estado = ?";

    $query                = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $ubicaciones_producto = $query->getResultArray();

    foreach ($ubicaciones_producto as $key) {
      $row = [
       "id_ubicacion" => $key["id_ubicacion"],
       "ubicacion"    => $key["ubicacion"],
       "usuario"      => $key["usuario"],
       "fecha"        => $key["fecha"]
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

  public function datatable_ubicaciones_producto_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							m.id as id_ubicacion,
                            m.ubicacion as ubicacion
						from 
							ubicaciones_producto m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query                = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $ubicaciones_producto = $query->getResultArray();

    foreach ($ubicaciones_producto as $key) {
      $row = [
       "id_ubicacion" => $key["id_ubicacion"],
       "ubicacion"    => $key["ubicacion"]
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