<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_servicios extends Model {

  protected $table = 'servicios';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'glosa',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_servicios($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							m.id as id_servicio,
                            m.glosa as servicio,
                            u.usuario,
                            date_format(m.fecha, '%d-%m-%Y') as fecha
						from 
							servicios m
							inner join usuarios u on u.id = m.id_usuario
						where
							m.id_apr = ? and
							m.estado = ?";

    $query = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function datatable_servicios_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							m.id as id_servicio,
                            m.glosa as servicio
						from 
							servicios m
						where
							m.id_apr = ? and
							m.estado = ?";

    $query = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>