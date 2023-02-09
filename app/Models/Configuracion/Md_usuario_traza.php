<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_usuario_traza extends Model {

  protected $table      = 'usuario_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_usuario',
   'estado',
   'observacion',
   'usuario_reg',
   'fecha'
  ];

  public function datatable_usuario_traza($db, $id_usuario) {
    $consulta = "SELECT 
							ute.glosa as estado,
						    ifnull(ut.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(ut.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							usuario_traza ut
						    inner join usuarios u on u.id = ut.usuario_reg
						    inner join usuario_traza_estados ute on ute.id = ut.estado
						where 
							ut.id_usuario = $id_usuario";

    $query         = $db->query($consulta, [$id_usuario]);
    $usuario_traza = $query->getResultArray();

    foreach ($usuario_traza as $key) {
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