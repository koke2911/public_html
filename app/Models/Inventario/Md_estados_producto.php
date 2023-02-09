<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_estados_producto extends Model {

  protected $table      = 'estados_producto';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'estado_producto',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_estados_producto($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_estado,
                            m.estado_producto as estado,
                            u.usuario,
                            date_format(m.fecha, '%d-%m-%Y') as fecha
						from 
							estados_producto m
							inner join usuarios u on u.id = m.id_usuario
						where
							m.id_apr = ? and
							m.estado = ?";

    $query            = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $estados_producto = $query->getResultArray();

    foreach ($estados_producto as $key) {
      $row = [
       "id_estado" => $key["id_estado"],
       "estado"    => $key["estado"],
       "usuario"   => $key["usuario"],
       "fecha"     => $key["fecha"]
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

  public function datatable_estados_producto_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							m.id as id_estado,
                            m.estado_producto as estado
						from 
							estados_producto m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query            = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $estados_producto = $query->getResultArray();

    foreach ($estados_producto as $key) {
      $row = [
       "id_estado" => $key["id_estado"],
       "estado"    => $key["estado"]
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