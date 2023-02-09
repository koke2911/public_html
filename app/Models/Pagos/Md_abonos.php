<?php namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_abonos extends Model {

  protected $table = 'abonos';
  protected $primaryKey = 'id';

  protected $returnType = 'array';

  protected $allowedFields = [
   'id',
   'abono',
   'id_socio',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];

  public function datatable_abonos($db, $id_apr) {
    $consulta = "SELECT 
							a.id as id_abono,
						    a.id_socio,
						    concat(s.rut, '-', s.dv) as rut_socio,
						    s.rol as rol_socio,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
						    a.abono,
						    u.usuario,
						    date_format(a.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							abonos a 
						    inner join socios s on a.id_socio = s.id
						    inner join usuarios u on a.id_usuario = u.id
						where 
							a.estado = 1 and 
						    a.id_apr = $id_apr";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}