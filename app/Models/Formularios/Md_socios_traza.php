<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_socios_traza extends Model {

  protected $table      = 'socios_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_socio',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_socio_traza($db, $id_socio) {
    $consulta = "SELECT 
							ste.glosa as estado,
						    ifnull(st.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(st.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							socios_traza st
						    inner join usuarios u on u.id = st.id_usuario
						    inner join socios_traza_estados ste on ste.id = st.estado
						where 
							st.id_socio = $id_socio";

    $query       = $db->query($consulta, [$id_socio]);
    $socio_traza = $query->getResultArray();

    foreach ($socio_traza as $key) {
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