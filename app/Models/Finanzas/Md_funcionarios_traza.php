<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_funcionarios_traza extends Model {

  protected $table      = 'funcionarios_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_funcionario',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_funcionario_traza($db, $id_funcionario) {
    $consulta = "SELECT 
							ste.glosa as estado,
						    ifnull(st.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(st.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							funcionarios_traza st
						    inner join usuarios u on u.id = st.id_usuario
						    inner join funcionarios_traza_estados ste on ste.id = st.estado
						where 
							st.id_funcionario = $id_funcionario";

    $query             = $db->query($consulta, [$id_funcionario]);
    $funcionario_traza = $query->getResultArray();

    foreach ($funcionario_traza as $key) {
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