<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_convenios extends Model {

  protected $table      = 'convenios';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_socio',
   'id_servicio',
   'detalle_servicio',
   'fecha_servicio',
   'numero_cuotas',
   'fecha_pago',
   'costo_servicio',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];

  public function datatable_convenios($db, $id_apr) {
    $consulta = "SELECT 
							c.id as id_convenio,
						    c.id_socio,
						    concat(s.rut, ' ', s.dv) as rut_socio,
						    s.rol as rol_socio,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
						    c.id_servicio,
						    sv.glosa as servicio,
						    c.detalle_servicio,
						    date_format(c.fecha_servicio, '%d-%m-%Y') as fecha_servicio,
						    c.numero_cuotas,
						    date_format(c.fecha_pago, '%m-%Y') as fecha_pago,
						    c.costo_servicio,
						    u.usuario,
						    date_format(c.fecha, '%d-%m-%Y %H:%i') as fecha
						FROM 
							convenios c
						    inner join socios s on c.id_socio = s.id
						    inner join servicios sv on c.id_servicio = sv.id
						    inner join usuarios u on c.id_usuario = u.id
						where
							c.estado = 1 and
						    c.id_apr = $id_apr";

    $query     = $db->query($consulta);
    $convenios = $query->getResultArray();

    foreach ($convenios as $key) {
      $row = [
       "id_convenio"      => $key["id_convenio"],
       "id_socio"         => $key["id_socio"],
       "rut_socio"        => $key["rut_socio"],
       "rol_socio"        => $key["rol_socio"],
       "nombre_socio"     => $key["nombre_socio"],
       "id_servicio"      => $key["id_servicio"],
       "servicio"         => $key["servicio"],
       "detalle_servicio" => $key["detalle_servicio"],
       "fecha_servicio"   => $key["fecha_servicio"],
       "numero_cuotas"    => $key["numero_cuotas"],
       "fecha_pago"       => $key["fecha_pago"],
       "costo_servicio"   => $key["costo_servicio"],
       "usuario"          => $key["usuario"],
       "fecha"            => $key["fecha"]
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

  public function datatable_convenios_reciclar($db, $id_apr) {
    $consulta = "SELECT 
							c.id as id_convenio,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
						    sv.glosa as servicio,
						    date_format(c.fecha_servicio, '%d-%m-%Y') as fecha_servicio,
						    c.numero_cuotas,
						    date_format(c.fecha_pago, '%m-%Y') as fecha_pago,
						    c.costo_servicio,
						    u.usuario,
						    date_format(c.fecha, '%d-%m-%Y %H:%i') as fecha
						FROM 
							convenios c
						    inner join socios s on c.id_socio = s.id
						    inner join servicios sv on c.id_servicio = sv.id
						    inner join usuarios u on c.id_usuario = u.id
						where
							c.estado = 0 and
						    c.id_apr = $id_apr";

    $query     = $db->query($consulta);
    $arranques = $query->getResultArray();

    foreach ($arranques as $key) {
      $row = [
       "id_convenio"    => $key["id_convenio"],
       "nombre_socio"   => $key["nombre_socio"],
       "servicio"       => $key["servicio"],
       "fecha_servicio" => $key["fecha_servicio"],
       "numero_cuotas"  => $key["numero_cuotas"],
       "fecha_pago"     => $key["fecha_pago"],
       "costo_servicio" => $key["costo_servicio"],
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

  public function datatable_buscar_socio($db, $id_apr) {
    $consulta = "SELECT 
							distinct s.id as id_socio,
							concat(s.rut, '-', s.dv) as rut,
							s.rol,
							concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre,
							date_format(s.fecha_entrada, '%d-%m-%Y') as fecha_entrada
						from 
							convenios c
							right join socios s on c.id_socio = s.id
						where 
    						s.id_apr = $id_apr and
    						s.estado = 1";

    $query     = $db->query($consulta);
    $subsidios = $query->getResultArray();

    foreach ($subsidios as $key) {
      $row = [
       "id_socio"      => $key["id_socio"],
       "rut"           => $key["rut"],
       "rol"           => $key["rol"],
       "nombre"        => $key["nombre"],
       "fecha_entrada" => $key["fecha_entrada"]
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