<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_proveedores_traza extends Model {

  protected $table      = 'proveedores_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_proveedor',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];

  public function datatable_proveedores_traza($db, $id_proveedor) {
    $consulta = "SELECT 
							pte.glosa as estado,
							ifnull(pt.observacion, 'No registrado') as observacion,
							u.usuario,
							date_format(pt.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							proveedores_traza pt
							inner join usuarios u on u.id = pt.id_usuario
							inner join proveedores_traza_estados pte on pte.id = pt.estado
						where 
							pt.id_proveedor = $id_proveedor";

    $query             = $db->query($consulta);
    $proveedores_traza = $query->getResultArray();

    foreach ($proveedores_traza as $key) {
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