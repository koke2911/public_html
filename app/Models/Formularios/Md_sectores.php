<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_sectores extends Model {

  protected $table      = 'sectores';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'nombre',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];

  public function datatable_sectores($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_sector,
						    s.nombre,
						    IFNULL(ELT(FIELD(s.estado, 0, 1), 'Eliminado', 'Activo'),'Sin registro') as estado,
						    u.usuario,
						    date_format(s.fecha, '%d-%m-%Y %H:%m') as fecha
						from 
							sectores s
						    inner join usuarios u on u.id = s.id_usuario
						where
							s.id_apr = $id_apr
							and s.estado = 1";

    $query    = $db->query($consulta);
    $sectores = $query->getResultArray();

    foreach ($sectores as $key) {
      $row = [
       "id_sector" => $key["id_sector"],
       "nombre"    => $key["nombre"],
       "estado"    => $key["estado"],
       "usuario"   => $key["usuario"],
       "fecha"     => $key["fecha"]
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

  public function datatable_sector_reciclar($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_sector,
						    s.nombre,
						    u.usuario,
						    date_format(s.fecha, '%d-%m-%Y %H:%m') as fecha
						from 
							sectores s
						    inner join usuarios u on u.id = s.id_usuario
						where
							s.id_apr = $id_apr
							and s.estado = 0";

    $query    = $db->query($consulta);
    $sectores = $query->getResultArray();

    foreach ($sectores as $key) {
      $row = [
       "id_sector" => $key["id_sector"],
       "nombre"    => $key["nombre"],
       "usuario"   => $key["usuario"],
       "fecha"     => $key["fecha"]
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