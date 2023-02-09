<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_sector_traza extends Model {

  protected $table      = 'sector_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_sector',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_sector_traza($db, $id_sector) {
    $consulta = "SELECT 
							ste.glosa as estado,
						    ifnull(st.observacion, 'No registrado') as observacion,
						    u.usuario,
						    date_format(st.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							sector_traza st
						    inner join usuarios u on u.id = st.id_usuario
						    inner join sector_traza_estados ste on ste.id = st.estado
						where 
							st.id_sector = $id_sector";

    $query        = $db->query($consulta, [$id_sector]);
    $sector_traza = $query->getResultArray();

    foreach ($sector_traza as $key) {
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