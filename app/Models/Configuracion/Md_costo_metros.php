<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_costo_metros extends Model {

  protected $table      = 'costo_metros';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_cargo_fijo',
   'desde',
   'hasta',
   'costo',
   'estado',
   'id_usuario',
   'fecha'
  ];

  public function datatable_costo_metros($db, $id_apr, $id_diametro) {
    $consulta = "SELECT 
							cm.id as id_costo_metros,
						    cf.cargo_fijo,
						    cf.id_diametro,
						    d.glosa as diametro,
							cf.id_apr,
							apr.nombre as apr,
							cm.desde,
							cm.hasta,
							cm.costo,
							u.usuario,
							date_format(cm.fecha, '%d-%m-%Y %H:%i') as fecha
						from 
							costo_metros cm
						    inner join apr_cargo_fijo cf on cm.id_cargo_fijo = cf.id
							inner join apr on cf.id_apr = apr.id
						    inner join diametro d on cf.id_diametro = d.id
							inner join usuarios u on cm.id_usuario = u.id
						where
							cf.id_apr = $id_apr and
						    cf.id_diametro = $id_diametro and
							cm.estado = 1";

    $query        = $db->query($consulta);
    $costo_metros = $query->getResultArray();

    foreach ($costo_metros as $key) {
      $row = [
       "id_costo_metros" => $key["id_costo_metros"],
       "id_diametro"     => $key["id_diametro"],
       "diametro"        => $key["diametro"],
       "id_apr"          => $key["id_apr"],
       "apr"             => $key["apr"],
       "desde"           => $key["desde"],
       "hasta"           => $key["hasta"],
       "costo"           => $key["costo"],
       "usuario"         => $key["usuario"],
       "fecha"           => $key["fecha"]
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

  public function validar_metraje_existente($db, $desde, $hasta, $cantidad, $id_apr, $id_diametro, $id_costo_metros) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							count(*) as filas
						from 
							costo_metros cm
						    inner join apr_cargo_fijo cf on cm.id_cargo_fijo = cf.id
						where 
							cf.id_apr = ? and 
						    cf.id_diametro = ? and
							cm.estado = ? and
							(cm.desde between ? and ? or
							cm.hasta between ? and ? or
							?  between cm.desde and cm.hasta or
							?  between cm.desde and cm.hasta or
                            cf.cantidad > ?)";

    if ($id_costo_metros != "") {
      $consulta .= " and cm.id <> ?";
    }

    $bind = [
     $id_apr,
     $id_diametro,
     $estado,
     $desde,
     $hasta,
     $desde,
     $hasta,
     $desde,
     $hasta,
     $cantidad
    ];

    if ($id_costo_metros != "") {
      array_push($bind, $id_costo_metros);
    }

    $query = $db->query($consulta, $bind);
    $row   = $query->getRow();
    $filas = $row->filas;

    if ($filas > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function datatable_costo_metros_consumo($db, $id_apr, $id_diametro, $consumo_actual) {
    $consulta = "(SELECT 
							cm.id as id_costo_metros,
							cm.desde,
							cm.hasta,
							cm.costo
						from 
							costo_metros cm
							inner join apr_cargo_fijo cf on cm.id_cargo_fijo = cf.id
							inner join diametro d on cf.id_diametro = d.id
						where
							cf.id_apr = $id_apr and
							cf.id_diametro = $id_diametro and
							cm.estado = 1)
						union
						(select
						    0 as id_costo_metros,
						    0 as desde,
						    cantidad as hasta,
						    cargo_fijo as costo
						from
							apr_cargo_fijo
						where
							id_apr = $id_apr and
						    id_diametro = $id_diametro)
						order by desde";

    $query        = $db->query($consulta);
    $costo_metros = $query->getResultArray();
    $resto;
    $metros;

    foreach ($costo_metros as $key) {
      if ($key["id_costo_metros"] == 0) {
        $resto = ((intval($key["hasta"]) - intval($key["desde"])));
      } else {
        $resto = ((intval($key["hasta"]) - intval($key["desde"])) + 1);
      }

      if ($resto > intval($consumo_actual)) {
        $metros         = intval($consumo_actual);
        $consumo_actual = 0;
      } else {
        $metros         = $resto;
        $consumo_actual = intval($consumo_actual) - $resto;
      }

      $row = [
       "id_costo_metros" => $key["id_costo_metros"],
       "metros"          => $metros,
       "desde"           => $key["desde"],
       "hasta"           => $key["hasta"],
       "costo"           => $key["costo"],
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