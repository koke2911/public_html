<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_productos_detalles extends Model {

  protected $table      = 'productos_detalles';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'codigo',
   'id_estado',
   'id_ubicacion',
   'id_producto'
  ];

  public function datatable_productos_detalles($db, $id_producto) {
    define("DE_BAJA", 0);
    $estado = DE_BAJA;

    $consulta = "SELECT 
							pd.id as id_detalle,
						    pd.codigo as codigo_barra,
						    pd.id_estado,
						    ifnull(ep.estado_producto, 'SIN ESTADO') as estado_producto,
						    pd.id_ubicacion,
						    ifnull(up.ubicacion, 'SIN UBICACIÓN') as ubicacion
						from 
							productos_detalles pd
						    left join estados_producto ep on pd.id_estado = ep.id
						    left join ubicaciones_producto up on pd.id_ubicacion = up.id
						where 
							pd.id_producto = ? and
						    (pd.id_estado <> ? or pd.id_estado is null)";

    $query            = $db->query($consulta, [
     $id_producto,
     $estado
    ]);
    $estados_producto = $query->getResultArray();

    foreach ($estados_producto as $key) {
      $row = [
       "id_detalle"   => $key["id_detalle"],
       "codigo_barra" => $key["codigo_barra"],
       "id_estado"    => $key["id_estado"],
       "estado"       => $key["estado_producto"],
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

  //    public function datatable_estados_producto_reciclar($db, $id_apr) {
  //    	define("DESACTIVADO", 0);
  //    	$estado = DESACTIVADO;

  //    	$consulta = "SELECT
  // 					m.id as id_estado,
  //                           m.estado_producto as estado
  // 				from
  // 					estados_producto m
  // 				where
  // 					m.id_apr = ? and
  // 					m.estado = ?";

  // 	$query = $db->query($consulta, [$id_apr, $estado]);
  // 	$estados_producto = $query->getResultArray();

  // 	foreach ($estados_producto as $key) {
  // 		$row = array(
  // 			"id_estado" => $key["id_estado"],
  // 			"estado" => $key["estado"]
  // 		);

  // 		$data[] = $row;
  // 	}

  // 	if (isset($data)) {
  // 		$salida = array("data" => $data);
  // 		return json_encode($salida);
  // 	} else {
  // 		return "{ \"data\": [] }";
  // 	}
  // }
}

?>