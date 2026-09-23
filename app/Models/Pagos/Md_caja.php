<?php namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_caja extends Model {

  protected $table      = 'caja';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'total_pagar',
   'entregado',
   'vuelto',
   'id_forma_pago',
   'numero_transaccion',
   'id_socio',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr',
   'descuento',
   'abono',
   'fecha_pago'
  ];

  public function datatable_historial_pagos($db, $id_apr, $id_socio, $desde, $hasta) {
    $consulta = "SELECT 
							c.id as id_caja,
              concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
							c.total_pagar as pagado,
							c.entregado,
							c.vuelto,
							c.descuento,
						    fp.glosa as forma_pago,
                date_format(c.fecha_pago, '%d-%m-%Y') as fecha_transa,
						    -- ifnull(c.numero_transaccion, 'No Registrado') as n_transaccion,
							s.rol as rol_socio,
							IFNULL(ELT(FIELD(c.estado, 0, 1), 'Anulado', 'Pagado'),'Sin registro') as estado,
							u.usuario,
							date_format(c.fecha, '%d-%m-%Y') as fecha

						from 
							caja c
							inner join socios s on c.id_socio = s.id
							inner join usuarios u on c.id_usuario = u.id
						    inner join forma_pago fp on c.id_forma_pago = fp.id
						where
							c.id_apr = ?";

    if ($id_socio != "") {
      $consulta .= " and c.id_socio = ?";
    }

    if ($desde != "" && $hasta != "") {
      $consulta .= " and date_format(c.fecha, '%d-%m-%Y') between ? and ?";
    }

    $bind = [$id_apr];

    if ($id_socio != "") {
      array_push($bind, $id_socio);
    }

    if ($desde != "" && $hasta != "") {
      array_push($bind, $desde, $hasta);
    }

    $query = $db->query($consulta, $bind);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function datatable_informe_pagos_diarios($db, $id_apr,$fecha) {
    define("ACTIVO", 1);
    $estado = ACTIVO;
    // $fecha  = date("d-m-Y");

    $consulta = "SELECT 
							c.id as folio_caja,
						    s.rol as rol_socio,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
						    c.total_pagar,
						    c.entregado,
						    c.vuelto,
						    sum(m.metros) as consumo,
						    u.usuario as usu_reg,
                fp.glosa as forma_pago,
                c.fecha_pago as fecha_trans
						from 
							caja c
						    inner join caja_detalle cd on cd.id_caja = c.id
						    inner join socios s on c.id_socio = s.id
						    inner join metros m on cd.id_metros = m.id
						    inner join usuarios u on c.id_usuario = u.id
                inner join forma_pago fp on c.id_forma_pago = fp.id
						where 
							date_format(c.fecha, '%d-%m-%Y') = ? and
						    c.id_apr = ? and
						    c.estado = ?
                GROUP BY
    c.id, s.rol, s.nombres, s.ape_pat, s.ape_mat, c.total_pagar, c.entregado, c.vuelto, u.usuario, fp.glosa, c.fecha_pago";

                // echo $fecha;

    $query = $db->query($consulta, [
     $fecha,
     $id_apr,
     $estado
    ]);
    $caja  = $query->getResultArray();

    foreach ($caja as $key) {
      $row = [
       "folio_caja"   => $key["folio_caja"],
       "rol_socio"    => $key["rol_socio"],
       "nombre_socio" => $key["nombre_socio"],
       "total_pagar"  => $key["total_pagar"],
       "entregado"    => $key["entregado"],
       "vuelto"       => $key["vuelto"],
       "consumo"      => $key["consumo"],
       "usu_reg"      => $key["usu_reg"],
       "forma_pago"      => $key["forma_pago"],
       "fecha_trans"      => $key["fecha_trans"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      return "{ \"data\": []}";
    }
  }

  public function datatable_informe_mensual($db, $id_apr, $mes_consumo) {
    $consulta = "SELECT 
							date_format(c.fecha, '%d-%m-%Y') as fecha_pago,
							(select count(*) as cantidad_boletas from 
							caja c2 inner join caja_detalle cd on cd.id_caja = c2.id inner join metros m on cd.id_metros = m.id
							where  c2.id_apr = c.id_apr and date_format(c2.fecha, '%d-%m-%Y') = date_format(c.fecha, '%d-%m-%Y') and c2.estado=1) as cantidad_boletas,
							(select sum(m.subtotal) as subtotal from 
							caja c2 inner join caja_detalle cd on cd.id_caja = c2.id inner join metros m on cd.id_metros = m.id
							where  c2.id_apr = c.id_apr and date_format(c2.fecha, '%d-%m-%Y') = date_format(c.fecha, '%d-%m-%Y')and c2.estado=1) as subtotal,
							(select sum(m.monto_subsidio) as subtotal from 
							caja c2 inner join caja_detalle cd on cd.id_caja = c2.id inner join metros m on cd.id_metros = m.id
							where  c2.id_apr = c.id_apr and date_format(c2.fecha, '%d-%m-%Y') = date_format(c.fecha, '%d-%m-%Y')and c2.estado=1) as subsidios,
							(select sum(m.multa) as subtotal from 
							caja c2 inner join caja_detalle cd on cd.id_caja = c2.id inner join metros m on cd.id_metros = m.id
							where  c2.id_apr = c.id_apr and date_format(c2.fecha, '%d-%m-%Y') = date_format(c.fecha, '%d-%m-%Y')and c2.estado=1) as multas,
							(select sum(m.total_servicios) as subtotal from 
							caja c2 inner join caja_detalle cd on cd.id_caja = c2.id inner join metros m on cd.id_metros = m.id
							where  c2.id_apr = c.id_apr and date_format(c2.fecha, '%d-%m-%Y') = date_format(c.fecha, '%d-%m-%Y')and c2.estado=1) as servicios,
							sum(c.total_pagar) as total_pagado
						from 
							caja c
						where 
							c.id_apr = ? and
							date_format(c.fecha, '%m-%Y') = ? and
							c.estado = 1
						group by
							date_format(c.fecha, '%d-%m-%Y')";

    $query = $db->query($consulta, [
     $id_apr,
     $mes_consumo
    ]);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function obtener_indicador_recaudacion($db, $id_apr)
  {
    $consulta = "SELECT 
                    COALESCE(SUM(CASE WHEN MONTH(fecha) = MONTH(CURRENT_DATE()) AND YEAR(fecha) = YEAR(CURRENT_DATE()) THEN total_pagar ELSE 0 END), 0) as mes_actual,
                    COALESCE(SUM(CASE WHEN MONTH(fecha) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(fecha) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN total_pagar ELSE 0 END), 0) as mes_anterior
                 FROM caja 
                 WHERE id_apr = ? AND estado = 1";

    $query = $db->query($consulta, [$id_apr]);
    $row   = $query->getRowArray();

    $actual   = floatval($row['mes_actual'] ?? 0);
    $anterior = floatval($row['mes_anterior'] ?? 0);

    // Cálculo del porcentaje de variación
    $porcentaje = 0;
    if ($anterior > 0) {
      $porcentaje = (($actual - $anterior) / $anterior) * 100;
    } elseif ($actual > 0) {
      $porcentaje = 100;
    }

    return [
      'total'      => $actual,
      'porcentaje' => round($porcentaje, 1)
    ];
  }

  public function obtener_indicador_tasa_pago($db, $id_apr)
  {
    $consulta = "SELECT 
                    -- Pagos registrados en caja durante el mes y año actual
                    (SELECT COUNT(DISTINCT id) 
                     FROM caja 
                     WHERE id_apr = ? 
                       AND estado = 1 
                       AND MONTH(fecha) = MONTH(CURRENT_DATE()) 
                       AND YEAR(fecha) = YEAR(CURRENT_DATE())
                    ) as pagados_caja_mes,

                    -- Total de socios activos susceptibles a cobro
                    (SELECT COUNT(*) 
                     FROM socios 
                     WHERE id_apr = ? 
                       AND estado = 1
                    ) as total_socios_activos";

    $query = $db->query($consulta, [$id_apr, $id_apr]);
    $row   = $query->getRowArray();

    $pagados = intval($row['pagados_caja_mes'] ?? 0);
    $total   = intval($row['total_socios_activos'] ?? 0);

    $tasa_actual = ($total > 0) ? ($pagados / $total) * 100 : 0;

    return [
      'tasa_actual' => round($tasa_actual, 1),
      'pagados'     => $pagados,
      'total'       => $total
    ];
  }

  public function obtener_indicador_deudas_pendientes($db, $id_apr)
  {
    // 1. Deudas pendientes actuales (boletas emitidas en estado pendiente/no pagadas)
    $consulta_actual = "SELECT COUNT(*) as total_pendientes
                        FROM metros m
                        WHERE m.id_apr = ? 
                          AND m.estado = 0"; // 0 = Pendiente de pago

    $query_actual = $db->query($consulta_actual, [$id_apr]);
    $row_actual   = $query_actual->getRowArray();
    $pendientes_actual = intval($row_actual['total_pendientes'] ?? 0);

    // 2. Deudas resueltas/pagadas en los últimos 7 días
    $consulta_semana = "SELECT COUNT(*) as pagadas_semana
                        FROM caja c
                        INNER JOIN caja_detalle cd ON cd.id_caja = c.id
                        WHERE c.id_apr = ? 
                          AND c.estado = 1
                          AND c.fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";

    $query_semana = $db->query($consulta_semana, [$id_apr]);
    $row_semana   = $query_semana->getRowArray();
    $pagadas_semana = intval($row_semana['pagadas_semana'] ?? 0);

    return [
      'total'           => $pendientes_actual,
      'variacion_semana' => $pagadas_semana // Indica cuántas deudas se redujeron/pagaron esta semana
    ];
  }

}

?>