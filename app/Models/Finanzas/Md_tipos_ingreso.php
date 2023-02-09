<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_tipos_ingreso extends Model {

  protected $table      = 'tipos_ingreso';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'tipo_ingreso',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_tipos_ingreso($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_tipo_ingreso,
                            m.tipo_ingreso,
                            u.usuario,
                            date_format(m.fecha, '%d-%m-%Y') as fecha
						from 
							tipos_ingreso m
							inner join usuarios u on u.id = m.id_usuario
						where
							m.id_apr = ? and
							m.estado = ?";

    $query         = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $tipos_ingreso = $query->getResultArray();

    foreach ($tipos_ingreso as $key) {
      $row = [
       "id_tipo_ingreso" => $key["id_tipo_ingreso"],
       "tipo_ingreso"    => $key["tipo_ingreso"],
       "usuario"         => $key["usuario"],
       "fecha"           => $key["fecha"]
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

  public function datatable_tipos_ingreso_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							m.id as id_tipo_ingreso,
                            m.tipo_ingreso
						from 
							tipos_ingreso m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query         = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $tipos_ingreso = $query->getResultArray();

    foreach ($tipos_ingreso as $key) {
      $row = [
       "id_tipo_ingreso" => $key["id_tipo_ingreso"],
       "tipo_ingreso"    => $key["tipo_ingreso"]
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

  public function datatable_buscar_tipo_ingreso($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_tipo_ingreso,
                            m.tipo_ingreso
						from 
							tipos_ingreso m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query         = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $tipos_ingreso = $query->getResultArray();

    foreach ($tipos_ingreso as $key) {
      $row = [
       "id_tipo_ingreso" => $key["id_tipo_ingreso"],
       "tipo_ingreso"    => $key["tipo_ingreso"]
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