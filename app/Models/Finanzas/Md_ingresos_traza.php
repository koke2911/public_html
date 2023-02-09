<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_ingresos_traza extends Model {

  protected $table      = 'ingresos_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_ingreso',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_ingresos_traza($db, $id_ingreso) {
    $consulta = "SELECT 
							ite.glosa as estado,
							ifnull(it.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(it.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							ingresos_traza it
							inner join usuarios u on u.id = it.id_usuario
							inner join ingresos_traza_estados ite on ite.id = it.estado
						where 
							it.id_ingreso = $id_ingreso";

    $query          = $db->query($consulta);
    $ingresos_traza = $query->getResultArray();

    foreach ($ingresos_traza as $key) {
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