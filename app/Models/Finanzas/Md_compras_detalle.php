<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_compras_detalle extends Model {

  protected $table = 'compras_detalle';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_producto',
   'cantidad',
   'precio',
   'total_producto',
   'id_compra'
  ];

  public function datatable_productos_fac($db, $id_compra) {
    $consulta = "SELECT 
							cd.id_producto,
						    p.nombre as producto,
						    cd.cantidad,
						    cd.precio,
						    cd.total_producto
						from 
							compras_detalle cd
						    inner join productos p on cd.id_producto = p.id
						where 
							cd.id_compra = ?";

    $query = $db->query($consulta, [$id_compra]);
    $data  = $query->getResultArray();

    $salida = ['data' => $data];

    return json_encode($salida);
  }
}

?>