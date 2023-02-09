<?php namespace App\Models\Inventario_agua;

use CodeIgniter\Model;

class Md_llenado_agua_traza extends Model {

  protected $table = 'llenado_agua_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_llenado',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_llenado_traza($db, $id_llenado) {
    $consulta = "SELECT 
							mte.glosa as estado,
							ifnull(mt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(mt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							llenado_agua_traza mt
							inner join usuarios u on u.id = mt.id_usuario
							inner join llenado_agua_traza_estados mte on mte.id = mt.estado
						where 
							mt.id_llenado = $id_llenado";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>
