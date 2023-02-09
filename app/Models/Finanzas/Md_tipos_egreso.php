<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_tipos_egreso extends Model {

  protected $table      = 'tipos_egreso';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'tipo_egreso',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_tipos_egreso($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_tipo_egreso,
                            m.tipo_egreso,
                            u.usuario,
                            date_format(m.fecha, '%d-%m-%Y') as fecha
						from 
							tipos_egreso m
							inner join usuarios u on u.id = m.id_usuario
						where
							m.id_apr = ? and
							m.estado = ?";

    $query        = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $tipos_egreso = $query->getResultArray();

    foreach ($tipos_egreso as $key) {
      $row = [
       "id_tipo_egreso" => $key["id_tipo_egreso"],
       "tipo_egreso"    => $key["tipo_egreso"],
       "usuario"        => $key["usuario"],
       "fecha"          => $key["fecha"]
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

  public function datatable_tipos_egreso_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							m.id as id_tipo_egreso,
                            m.tipo_egreso
						from 
							tipos_egreso m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query        = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $tipos_egreso = $query->getResultArray();

    foreach ($tipos_egreso as $key) {
      $row = [
       "id_tipo_egreso" => $key["id_tipo_egreso"],
       "tipo_egreso"    => $key["tipo_egreso"]
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

  public function datatable_buscar_tipo_egreso($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_tipo_egreso,
                            m.tipo_egreso
						from 
							tipos_egreso m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query        = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $tipos_egreso = $query->getResultArray();

    foreach ($tipos_egreso as $key) {
      $row = [
       "id_tipo_egreso" => $key["id_tipo_egreso"],
       "tipo_egreso"    => $key["tipo_egreso"]
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