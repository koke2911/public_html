<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_cuentas_traza extends Model {

  protected $table      = 'cuentas_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_cuenta',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_cuentas_traza($db, $id_cuenta) {
    $consulta = "SELECT 
							cte.glosa as estado,
							ifnull(ct.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(ct.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							cuentas_traza ct
							inner join usuarios u on u.id = ct.id_usuario
							inner join cuentas_traza_estados cte on cte.id = ct.estado
						where 
							ct.id_cuenta = $id_cuenta";

    $query         = $db->query($consulta, [$id_cuenta]);
    $cuentas_traza = $query->getResultArray();

    foreach ($cuentas_traza as $key) {
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