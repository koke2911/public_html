<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_permisos_detalle extends Model {

  protected $table      = 'permisos_detalle';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'nombre',
   'id_grupo',
   'ruta',
   'estado'
  ];

  public function datatable_usuarios_permisos($db) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							pd.id as id_permiso,
						    pd.nombre as permiso,
						    pe.nombre as grupo
						from 
						    permisos_detalle pd
						    inner join permisos_enc pe on pe.id = pd.id_grupo
						where
							pd.estado = ?";

    $query    = $db->query($consulta, [$estado]);
    $permisos = $query->getResultArray();

    foreach ($permisos as $key) {
      $row = [
       "id_permiso" => $key["id_permiso"],
       "permiso"    => $key["permiso"],
       "grupo"      => $key["grupo"]
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

  public function data_permisos_usuario($db, $id_usuario) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT id_permiso from permisos_usuario where id_usuario = ? and estado = ?";

    $query    = $db->query($consulta, [
     $id_usuario,
     $estado
    ]);
    $permisos = $query->getResultArray();

    foreach ($permisos as $key) {
      $row = [
       "id_permiso" => $key["id_permiso"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      return json_encode($data);
    } else {
      return "[]";
    }
  }
}

?>