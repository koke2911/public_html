<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_convenio_detalle extends Model {

  protected $table      = 'convenio_detalle';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_convenio',
   'fecha_pago',
   'numero_cuota',
   'valor_cuota',
   'pagado'
  ];

  public function datatable_convenio_detalle($db, $id_convenio) {
    $consulta = "SELECT 
							cd.id,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as socio,
						    -- date_format(date_add(c.fecha_pago, INTERVAL cd.numero_cuota - 1 MONTH), '%m-%Y') as fecha_pago,
						    date_format(cd.fecha_pago, '%m-%Y') as fecha_pago,
						    cd.numero_cuota,
						    cd.valor_cuota
						FROM 
							convenio_detalle cd
						    inner join convenios c on cd.id_convenio = c.id
						    inner join socios s on c.id_socio = s.id
						where
							cd.id_convenio = $id_convenio";

    $query            = $db->query($consulta);
    $convenio_detalle = $query->getResultArray();

    foreach ($convenio_detalle as $key) {
      $row = [
       "id"           => $key["id"],
       "socio"        => $key["socio"],
       "fecha_pago"   => $key["fecha_pago"],
       "numero_cuota" => $key["numero_cuota"],
       "valor_cuota"  => $key["valor_cuota"]
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

  public function calcular_total_servicios($db, $fecha_vencimiento, $id_socio) {
    $fecha = date_format(date_create($fecha_vencimiento), 'm-Y');

    $consulta = "SELECT 
							ifnull(sum(cd.valor_cuota), 0) as total_servicios
						from 
							convenio_detalle cd
						    inner join convenios c on cd.id_convenio = c.id
						where 
							date_format(cd.fecha_pago, '%m-%Y') = ? and
						    c.id_socio = ?";

    $query = $db->query($consulta, [
     $fecha,
     $id_socio
    ]);
    $row   = $query->getRow();

    return $row->total_servicios;
  }

  public function datatable_repactar_convenio($db, $id_convenio) {
    $consulta = "SELECT 
							cd.id,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as socio,
						    -- date_format(date_add(c.fecha_pago, INTERVAL cd.numero_cuota - 1 MONTH), '%m-%Y') as fecha_pago,
						    date_format(cd.fecha_pago, '%m-%Y') as fecha_pago,
						    cd.numero_cuota,
						    cd.valor_cuota,
						    case when pagado = 1 then 'SI' else 'NO' end as pagado
						FROM 
							convenio_detalle cd
						    inner join convenios c on cd.id_convenio = c.id
						    inner join socios s on c.id_socio = s.id
						where
							cd.id_convenio = $id_convenio";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>