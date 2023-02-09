<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_motivos extends Model {

  protected $table      = 'motivos';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'motivo',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_motivos($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_motivo,
                            m.motivo,
                            u.usuario,
                            date_format(m.fecha, '%d-%m-%Y') as fecha
						from 
							motivos m
							inner join usuarios u on u.id = m.id_usuario
						where
							m.id_apr = ? and
							m.estado = ?";

    $query   = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $motivos = $query->getResultArray();

    foreach ($motivos as $key) {
      $row = [
       "id_motivo" => $key["id_motivo"],
       "motivo"    => $key["motivo"],
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

  public function datatable_motivos_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							m.id as id_motivo,
                            m.motivo
						from 
							motivos m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query   = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $motivos = $query->getResultArray();

    foreach ($motivos as $key) {
      $row = [
       "id_motivo" => $key["id_motivo"],
       "motivo"    => $key["motivo"]
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

  public function datatable_buscar_motivo($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_motivo,
                            m.motivo
						from 
							motivos m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query   = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $motivos = $query->getResultArray();

    foreach ($motivos as $key) {
      $row = [
       "id_motivo" => $key["id_motivo"],
       "motivo"    => $key["motivo"]
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