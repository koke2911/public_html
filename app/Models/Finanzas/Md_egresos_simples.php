<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_egresos_simples extends Model {

  protected $table      = 'egresos_simples';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_tipo_egreso',
   'fecha',
   'monto',
   'id_cuenta',
   'n_transaccion',
   'tipo_entidad',
   'id_entidad',
   'id_motivo',
   'observaciones',
   'id_egreso'
  ];

  public function datos_egreso($db, $id_egreso) {
    $consulta = "SELECT 
							es.id_tipo_egreso,
						    date_format(es.fecha, '%d-%m-%Y') as fecha,
						    es.monto,
						    es.id_cuenta,
							b.nombre_banco,
						    btc.glosa as tipo_cuenta,
						    c.n_cuenta,
						    concat(c.rut, '-', c.dv) as rut_cuenta,
						    c.nombre_cuenta,
						    c.email,
						    es.n_transaccion,
						    es.tipo_entidad,
						    es.id_entidad,
						    case es.tipo_entidad 
								when 'Proveedor' then
									(select concat(pro.rut, '-', pro.dv) from proveedores pro where pro.id = es.id_entidad)
								when 'Socio' then
									(select concat(s.rut, '-', s.dv) from socios s where s.id = es.id_entidad)
								when 'Funcionario' then
									(select concat(f.rut, '-', f.dv) from funcionarios f where f.id = es.id_entidad)
							end as rut_entidad,
						    case es.tipo_entidad
								when 'Proveedor' then
									(select razon_social from proveedores pro where pro.id = es.id_entidad)
								when 'Socio' then
									(select concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) from socios s where s.id = es.id_entidad)
								when 'Funcionario' then
									(select concat(f.nombres, '-', f.ape_pat, ' ', f.ape_mat) from funcionarios f where f.id = es.id_entidad)
							end as nombre_entidad,
						    es.id_motivo,
						    m.motivo,
						    es.observaciones
						from 
							egresos_simples es
						    left join cuentas c on es.id_cuenta = c.id
						    left join bancos b on c.id_banco = b.id
						    left join banco_tipo_cuenta btc on c.id_tipo_cuenta = btc.id
						    inner join motivos m on es.id_motivo = m.id
						where
							es.id_egreso = ?";

    $query = $db->query($consulta, [$id_egreso]);
    $data  = $query->getResultArray();

    return json_encode($data);
  }

  public function datatable_informe_egresos($db, $id_apr, $datosBusqueda) {
    define("ACTIVO", 1);
    $estado        = ACTIVO;
    $datosBusqueda = json_decode($datosBusqueda, TRUE);

    $fecha_desde     = date_format(date_create($datosBusqueda["fecha_desde"] . "00:00"), 'Y-m-d H:i');
    $fecha_hasta     = date_format(date_create($datosBusqueda["fecha_hasta"] . "23:59"), 'Y-m-d H:i');
    $id_cuenta       = $datosBusqueda["id_cuenta"];
    $id_tipo_egreso  = $datosBusqueda["id_tipo_egreso"];
    $id_tipo_entidad = $datosBusqueda["id_tipo_entidad"];
    $id_entidad      = $datosBusqueda["id_entidad"];

    $consulta = "SELECT
	    					es.id_egreso,
						    es.monto,
						    date_format(es.fecha, '%d-%m-%Y') as fecha_egreso,
                            te.tipo_egreso,
							b.nombre_banco as banco,
						    btc.glosa as tipo_cuenta,
						    c.n_cuenta,
						    concat(c.rut, '-', c.dv) as rut_cuenta,
						    c.nombre_cuenta,
						    es.n_transaccion,
						    es.tipo_entidad,
						    case es.tipo_entidad 
								when 'Proveedor' then
									(select concat(pro.rut, '-', pro.dv) from proveedores pro where pro.id = es.id_entidad)
								when 'Socio' then
									(select concat(s.rut, '-', s.dv) from socios s where s.id = es.id_entidad)
								when 'Funcionario' then
									(select concat(f.rut, '-', f.dv) from funcionarios f where f.id = es.id_entidad)
							end as rut_entidad,
						    case es.tipo_entidad
								when 'Proveedor' then
									(select razon_social from proveedores pro where pro.id = es.id_entidad)
								when 'Socio' then
									(select concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) from socios s where s.id = es.id_entidad)
								when 'Funcionario' then
									(select concat(f.nombres, '-', f.ape_pat, ' ', f.ape_mat) from funcionarios f where f.id = es.id_entidad)
							end as nombre_entidad,
						    m.motivo,
						    es.observaciones,
                            date_format(e.fecha, '%d-%m-%Y %H:%i:%s') as fecha_reg
						from 
							egresos_simples es
						    left join cuentas c on es.id_cuenta = c.id
						    left join bancos b on c.id_banco = b.id
						    left join banco_tipo_cuenta btc on c.id_tipo_cuenta = btc.id
						    inner join motivos m on es.id_motivo = m.id
                            inner join tipos_egreso te on es.id_tipo_egreso = te.id
                            inner join egresos e on es.id_egreso = e.id
						where
							e.id_apr = $id_apr and
                            e.estado = $estado";

    $bind = [
     $id_apr,
     $estado
    ];

    if ($fecha_desde != "" and $fecha_hasta != "") {
      $consulta .= " and es.fecha between '$fecha_desde' and '$fecha_hasta'";
      array_push($bind, [
       $fecha_desde,
       $fecha_hasta
      ]);
    }

    if ($id_tipo_egreso != "") {
      $consulta .= " and es.id_tipo_egreso = $id_tipo_egreso";
      array_push($bind, [$id_tipo_egreso]);
    }

    if ($id_cuenta != "") {
      $consulta .= " and es.id_cuenta = $id_cuenta";
      array_push($bind, [$id_cuenta]);
    }

    if ($id_tipo_entidad != "") {
      $consulta .= " and es.tipo_entidad = '$id_tipo_entidad'";
      array_push($bind, [$id_tipo_entidad]);
    }

    if ($id_entidad != "") {
      $consulta .= " and es.id_entidad = $id_entidad";
      array_push($bind, [$id_entidad]);
    }

    $query = $db->query($consulta, $bind);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>