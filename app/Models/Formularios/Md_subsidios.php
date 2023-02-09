<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_subsidios extends Model {

  protected $table      = 'subsidios';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_socio',
   'numero_decreto',
   'fecha_decreto',
   'fecha_caducidad',
   'id_porcentaje',
   'fecha_encuesta',
   'puntaje',
   'numero_unico',
   'digito_unico',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_subsidios($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_subsidio,
						    s.id_socio,
						    concat(so.rut, ' ', so.dv) as rut_socio,
						    so.rol as rol_socio,
						    concat(so.nombres, ' ', so.ape_pat, ' ', so.ape_mat) as nombre_socio,
						    s.numero_decreto as n_decreto,
						    date_format(s.fecha_decreto, '%d-%m-%Y') as fecha_decreto,
						    date_format(s.fecha_caducidad, '%d-%m-%Y') as fecha_caducidad,
						    s.id_porcentaje,
						    p.glosa as porcentaje,
						    date_format(s.fecha_encuesta, '%d-%m-%Y') as fecha_encuesta,
						    s.puntaje,
						    s.numero_unico as n_unico,
						    s.digito_unico as d_unico,
						    u.usuario,
						    date_format(s.fecha, '%d-%m-%Y %H:%i') as fecha
						FROM 
							subsidios s
						    inner join socios so on s.id_socio = so.id
						    inner join porcentajes p on s.id_porcentaje = p.id
						    inner join usuarios u on s.id_usuario = u.id
						    inner join apr on s.id_apr = apr.id
						where
							s.estado = 1 and
						    s.id_apr = $id_apr";

    $query     = $db->query($consulta);
    $subsidios = $query->getResultArray();

    foreach ($subsidios as $key) {
      $row = [
       "id_subsidio"     => $key["id_subsidio"],
       "id_socio"        => $key["id_socio"],
       "rut_socio"       => $key["rut_socio"],
       "rol_socio"       => $key["rol_socio"],
       "nombre_socio"    => $key["nombre_socio"],
       "n_decreto"       => $key["n_decreto"],
       "fecha_decreto"   => $key["fecha_decreto"],
       "fecha_caducidad" => $key["fecha_caducidad"],
       "id_porcentaje"   => $key["id_porcentaje"],
       "porcentaje"      => $key["porcentaje"],
       "fecha_encuesta"  => $key["fecha_encuesta"],
       "puntaje"         => $key["puntaje"],
       "n_unico"         => $key["n_unico"],
       "d_unico"         => $key["d_unico"],
       "usuario"         => $key["usuario"],
       "fecha"           => $key["fecha"],
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

  public function datatable_subsidio_reciclar($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_subsidio,
						    concat(so.nombres, ' ', so.ape_pat, ' ', so.ape_mat) as nombre_socio,
						    s.numero_decreto as n_decreto,
						    date_format(s.fecha_decreto, '%d-%m-%Y') as fecha_decreto,
							p.glosa as porcentaje,
						    u.usuario,
						    date_format(s.fecha, '%d-%m-%Y %H:%i') as fecha
						FROM 
							subsidios s
						    inner join socios so on s.id_socio = so.id
						    inner join porcentajes p on s.id_porcentaje = p.id
						    inner join usuarios u on s.id_usuario = u.id
						    inner join apr on s.id_apr = apr.id
						where
							s.estado = 0 and
						    s.id_apr = $id_apr";

    $query     = $db->query($consulta);
    $arranques = $query->getResultArray();

    foreach ($arranques as $key) {
      $row = [
       "id_subsidio"   => $key["id_subsidio"],
       "nombre_socio"  => $key["nombre_socio"],
       "n_decreto"     => $key["n_decreto"],
       "fecha_decreto" => $key["fecha_decreto"],
       "porcentaje"    => $key["porcentaje"],
       "usuario"       => $key["usuario"],
       "fecha"         => $key["fecha"]
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
							s.id as id_socio,
							concat(s.rut, '-', s.dv) as rut,
							s.rol,
							concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre,
							date_format(s.fecha_entrada, '%d-%m-%Y') as fecha_entrada
						from 
							subsidios sub
							right join socios s on sub.id_socio = s.id
						where 
							sub.id_socio is null and
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

  public function datatable_informe_subsidios($db, $id_apr) {
    $consulta = "SELECT 
							sub.id as id_subsidio,
						    soc.rol as rol_socio,
						    concat(soc.nombres, ' ', soc.ape_pat, ' ', soc.ape_mat) as nombre_socio,
						    ifnull(date_format(sub.fecha_decreto, '%d-%m-%Y'), 'Sin Registro') as fecha_decreto,
						    ifnull(date_format(sub.fecha_caducidad, '%d-%m-%Y'), 'Sin Registro') as fecha_caducidad,
						    p.glosa as porcentaje,
						    ifnull(date_format(sub.fecha_encuesta, '%d-%m-%Y'), 'Sin Registro') as fecha_encuesta,
						    ifnull(sub.puntaje, 'Sin Registro') as puntaje,
							ifnull(sub.numero_unico, 'Sin Registro') as numero_unico,
						    ifnull(sub.digito_unico, 'Sin Registro') as digito_unico,
						    IFNULL(ELT(FIELD(sub.estado, 0, 1), 'Desactivado','Activado'), 'Sin registro') as estado
						FROM 
							subsidios sub
						    inner join socios soc on sub.id_socio = soc.id
						    inner join porcentajes p on sub.id_porcentaje = p.id
						where
							sub.id_apr = ?";

    $query     = $db->query($consulta, [$id_apr]);
    $subsidios = $query->getResultArray();

    foreach ($subsidios as $key) {
      $row = [
       "id_subsidio"     => $key["id_subsidio"],
       "rol_socio"       => $key["rol_socio"],
       "nombre_socio"    => $key["nombre_socio"],
       "fecha_decreto"   => $key["fecha_decreto"],
       "fecha_caducidad" => $key["fecha_caducidad"],
       "porcentaje"      => $key["porcentaje"],
       "fecha_encuesta"  => $key["fecha_encuesta"],
       "puntaje"         => $key["puntaje"],
       "numero_unico"    => $key["numero_unico"],
       "digito_unico"    => $key["digito_unico"],
       "estado"          => $key["estado"]
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