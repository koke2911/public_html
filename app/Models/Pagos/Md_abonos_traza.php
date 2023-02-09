<?php namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_abonos_traza extends Model {

  protected $table = 'abonos_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_abono',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_abono_traza($db, $id_abono) {
    $consulta = "SELECT 
							ate.glosa as estado,
							ifnull(at.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(at.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							abonos_traza at
							inner join usuarios u on u.id = at.id_usuario
							inner join abonos_traza_estados ate on ate.id = at.estado
						where 
							at.id_abono = $id_abono";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>