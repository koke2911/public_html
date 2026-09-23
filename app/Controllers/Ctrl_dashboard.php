<?php

namespace App\Controllers;

use App\Models\Formularios\Md_socios;
use App\Models\Pagos\Md_caja;

class Ctrl_dashboard extends BaseController
{

  protected $sesión;
  protected $socios;
  protected $caja;
  protected $db;

  public function __construct()
  {
    $this->sesión = session();
    $this->socios = new Md_socios();
    $this->caja   = new Md_caja();
    $this->db     = \Config\Database::connect();
  }

  public function validar_sesion()
  {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function llenar_graficos_pagos()
  {
    $this->validar_sesion();

    $consulta = "WITH RECURSIVE ultimos_meses AS (
            -- 1. Genera el primer mes (hace 11 meses atrás para completar 12 con el actual)
            SELECT DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL 8 MONTH), '%Y-%m-01') AS fecha_inicio
            UNION ALL
            -- 2. Incrementa mes a mes hasta el día de hoy
            SELECT DATE_ADD(fecha_inicio, INTERVAL 1 MONTH)
            FROM ultimos_meses
            WHERE fecha_inicio < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')
        )
        SELECT 
            DATE_FORMAT(m_rango.fecha_inicio, '%m-%Y') AS fecha_pago,
            COALESCE(COUNT(DISTINCT c.id), 0) AS cantidad_boletas,
            COALESCE(SUM(m.subtotal), 0) AS subtotal,
            COALESCE(SUM(m.monto_subsidio), 0) AS subsidios,
            COALESCE(SUM(m.multa), 0) AS multas,
            COALESCE(SUM(m.total_servicios), 0) AS servicios,
            COALESCE(SUM(c.total_pagar), 0) AS total_pagado
        FROM 
            ultimos_meses m_rango
        LEFT JOIN 
            caja c ON DATE_FORMAT(c.fecha, '%Y-%m') = DATE_FORMAT(m_rango.fecha_inicio, '%Y-%m')
                  AND c.id_apr = ? 
                  AND c.estado = 1
        LEFT JOIN 
            caja_detalle cd ON cd.id_caja = c.id
        LEFT JOIN 
            metros m ON cd.id_metros = m.id
        GROUP BY 
            m_rango.fecha_inicio
        ORDER BY 
            m_rango.fecha_inicio ASC";

    $query = $this->db->query($consulta, [
      $this->sesión->id_apr_ses
    ]);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    echo json_encode($salida);
    // echo $this->socios->llenar_grafico_socios($this->db, $this->sesión->id_apr_ses);
  }

  /*
   * ==========================================================
   * OBTENER ÚLTIMOS PAGOS REGISTRADOS
   * ==========================================================
   */
  public function datatable_ultimos_pagos()
  {
    $this->validar_sesion();
    // Utiliza el método del modelo de caja para listar los pagos
    echo $this->caja->datatable_historial_pagos($this->db, $this->sesión->id_apr_ses, "", "", "");
  }

  public function obtener_indicador_socios()
  {
    $id_apr = $this->sesión->id_apr_ses;

    
    $data = $this->socios->obtener_total_socios_activos($this->db, $id_apr);

    return $this->response->setJSON($data);
  }


  public function cargar_indicador_recaudacion()
  {
    $id_apr = $this->sesión->id_apr_ses;

    $datos   = $this->caja->obtener_indicador_recaudacion($this->db, $id_apr);

    return $this->response->setJSON($datos);
  }

  public function cargar_indicador_tasa_pago()
  {
    $id_apr = $this->sesión->id_apr_ses;
    $datos   = $this->caja->obtener_indicador_tasa_pago($this->db, $id_apr);

    return $this->response->setJSON($datos);
  }

  public function cargar_indicador_deudas_pendientes()
  {
    $id_apr = $this->sesión->id_apr_ses;
    $datos   = $this->caja->obtener_indicador_deudas_pendientes($this->db, $id_apr);

    return $this->response->setJSON($datos);
  }

}
