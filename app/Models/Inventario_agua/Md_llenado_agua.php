<?php namespace App\Models\Inventario_agua;

use CodeIgniter\Model;

class Md_llenado_agua extends Model {

  protected $table = 'llenado_agua';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'fecha_hora',
   'id_operador',
   'cantidad_agua',
   'um_agua',
   'cantidad_cloro',
   'um_cloro',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_llenado($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							lla.id as id_llenado,
						    date_format(lla.fecha_hora, '%d-%m-%Y %H:%i') as fecha_hora,
						    lla.id_operador,
						    concat(f.rut, '-', f.dv) as rut_operador,
							concat(f.nombres, ' ', f.ape_pat, ' ', f.ape_mat) as nombre_operador,
						    lla.cantidad_agua,
						    lla.um_agua as id_um_agua,
						    IFNULL(ELT(FIELD(lla.um_agua, 1, 2), 'Metros Cúbicos', 'Litros'),'Sin registro') as um_agua,
						    lla.cantidad_cloro,
						    lla.um_cloro as id_um_cloro,
						    IFNULL(ELT(FIELD(lla.um_agua, 1, 2, 3), 'Gramos', 'kilos', 'Litros'), 'Sin registro') as um_cloro,
						    u.usuario,
						    date_format(lla.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						FROM 
							llenado_agua lla
						    inner join funcionarios f on lla.id_operador = f.id
						    inner join usuarios u on lla.id_usuario = u.id
						where
							lla.estado = $estado and
						    lla.id_apr = $id_apr";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function datatable_informe_llenado_agua($db, $id_apr, $datosBusqueda) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $datosBusqueda = json_decode($datosBusqueda, TRUE);
    $fecha_desde   = $datosBusqueda["fecha_desde"];
    $fecha_hasta   = $datosBusqueda["fecha_hasta"];
    $id_operador   = $datosBusqueda["id_operador"];
    $id_conversion = $datosBusqueda["id_conversion"];

    $consulta = "SELECT 
							lla.id as id_llenado,
						    date_format(lla.fecha_hora, '%d-%m-%Y %H:%i') as fecha_hora,
						    concat(f.rut, '-', f.dv) as rut_operador,
							concat(f.nombres, ' ', f.ape_pat, ' ', f.ape_mat) as nombre_operador,";

    if ($id_conversion == 1) {
      $consulta .= " case when lla.um_agua = 1 then lla.cantidad_agua else round(lla.cantidad_agua/1000) end as cantidad_agua,
								'Metros Cúbicos' as um_agua,";
    } else {
      $consulta .= " case when lla.um_agua = 1 then round(lla.cantidad_agua * 1000) else lla.cantidad_agua end as cantidad_agua,
								'Litros' as um_agua,";
    }

    $consulta .= " lla.cantidad_cloro,
						    IFNULL(ELT(FIELD(lla.um_agua, 1, 2, 3), 'Gramos', 'kilos', 'Litros'), 'Sin registro') as um_cloro
						FROM 
							llenado_agua lla
						    inner join funcionarios f on lla.id_operador = f.id
						    inner join usuarios u on lla.id_usuario = u.id
						where
							lla.estado = $estado and
						    lla.id_apr = $id_apr";

    if ($fecha_desde != "" and $fecha_hasta != "") {
      $consulta .= " and date_format(lla.fecha_hora, '%d-%m-%Y') between '$fecha_desde' and '$fecha_hasta'";
    }

    if ($id_operador != "") {
      $consulta .= " and lla.id_operador = $id_operador";
    }

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>