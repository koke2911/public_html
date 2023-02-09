<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_cambio_medidor_traza extends Model {

  protected $table = 'cambio_medidor_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_cambio',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_cambio_medidor_traza($db, $id_cambio) {
    $consulta = "SELECT 
							cte.glosa as estado,
							ifnull(ct.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(ct.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							cambio_medidor_traza ct
							inner join usuarios u on u.id = ct.id_usuario
							inner join cambio_medidor_traza_estados cte on cte.id = ct.estado
						where 
							ct.id_cambio = $id_cambio";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}