<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_subsidio_traza extends Model {

  protected $table      = 'subsidio_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_subsidio',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_subsidio_traza($db, $id_subsidio) {
    $consulta = "SELECT 
							ste.glosa as estado,
						    ifnull(st.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(st.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							subsidio_traza st
						    inner join usuarios u on u.id = st.id_usuario
						    inner join subsidio_traza_estados ste on ste.id = st.estado
						where 
							st.id_subsidio = $id_subsidio";

    $query          = $db->query($consulta);
    $subsidio_traza = $query->getResultArray();

    foreach ($subsidio_traza as $key) {
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