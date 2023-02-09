<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_egresos_traza extends Model {

  protected $table = 'egresos_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_egreso',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_egresos_traza($db, $id_egreso) {
    $consulta = "SELECT
	    					it.id as id_traza,
							ite.glosa as estado,
							ifnull(it.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(it.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							egresos_traza it
							inner join usuarios u on u.id = it.id_usuario
							inner join egresos_traza_estados ite on ite.id = it.estado
						where 
							it.id_egreso = $id_egreso";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>