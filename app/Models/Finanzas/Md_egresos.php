<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_egresos extends Model {

  protected $table      = 'egresos';
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

  public function datatable_historial_egresos($db, $id_apr) {
    $consulta = "SELECT 
							e.id as id_egreso,
						    IFNULL(ELT(FIELD(e.tipo_egreso, 1, 2), 'Compra','Egreso Simple'),'Sin registro') as tipo_egreso,
						    u.usuario,
						    date_format(e.fecha, '%d-%m-%Y %H:%i:%s') as fecha,
						    IFNULL(ELT(FIELD(e.estado, 0, 1), 'Anulado','Activo'),'Sin registro') as estado
						from 
							egresos e
						    inner join usuarios u on e.id_usuario = u.id
						where 
							e.id_apr = $id_apr";

    $query   = $db->query($consulta, [$id_apr]);
    $egresos = $query->getResultArray();

    $salida = ["data" => $egresos];

    return json_encode($salida);
  }
}

?>