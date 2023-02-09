<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_cambio_medidor extends Model {

  protected $table = 'cambio_medidor';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_socio',
   'id_funcionario',
   'motivo_cambio',
   'fecha_cambio',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];

  public function datatable_cambio_medidor($db, $id_apr) {
    $consulta = "SELECT
							 cm.id as id_cambio,
						     cm.id_socio,
						     concat(s.rut, '-', s.dv) as rut_socio,
						     s.rol as rol_socio,
						     concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
						     f.id as id_funcionario,
						     concat(f.rut, '-', f.dv) as rut_funcionario,
						     concat(f.nombres, ' ', f.ape_pat, ' ', f.ape_mat) as nombre_funcionario,
						     cm.motivo_cambio,
						     date_format(cm.fecha_cambio, '%d-%m-%Y') as fecha_cambio,
						     u.usuario,
						     date_format(cm.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							cambio_medidor cm
						    inner join socios s on cm.id_socio = s.id
						    inner join funcionarios f on cm.id_funcionario = f.id
						    inner join usuarios u on cm.id_usuario = u.id
						    inner join apr on cm.id_apr = apr.id
						where
							cm.id_apr = $id_apr and
						    cm.estado = 1";

    $query = $db->query($consulta, [
     $id_apr,
     1
    ]);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}