<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_socios;

class Ctrl_alertas extends BaseController
{
    protected $socios;
    protected $sesion;
    protected $db;

    public function __construct()
    {
        $this->sesion = session();
        $this->socios = new Md_socios();
        $this->db     = \Config\Database::connect();
    }

    public function obtener_alertas_indicadores()
    {
        if (!$this->sesion->has("id_usuario_ses")) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Sesión expirada']);
        }

        $id_apr = $this->sesion->id_apr_ses;
        $hoy    = date("Y-m-d");

        // Subconsulta para obtener el consumo anterior del socio
        $subQueryConsumoAnterior = "(SELECT m2.metros 
                                     FROM metros m2 
                                     WHERE m2.id_socio = m.id_socio 
                                       AND m2.id_apr = m.id_apr 
                                       AND m2.estado != 0 
                                       AND m2.fecha_ingreso < m.fecha_ingreso 
                                     ORDER BY m2.fecha_ingreso DESC 
                                     LIMIT 1)";

        // Diccionario para meses en español
        $mesesEs = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        $builder = $this->socios
            ->select("m.id as id_lectura")
            ->select("DATE_FORMAT(m.fecha_ingreso, '%m') as num_mes")
            ->select("DATE_FORMAT(m.fecha_ingreso, '%Y') as anio_consumo")
            ->select("m.fecha_ingreso")
            ->select("DATE_FORMAT(IFNULL(m.fecha_vencimiento, m.fecha_ingreso), '%d/%m/%Y') as fecha_vencimiento_format")
            ->select("IFNULL(m.fecha_vencimiento, m.fecha_ingreso) as fecha_venc_raw")
            ->select("m.metros as y")
            ->select("IFNULL({$subQueryConsumoAnterior}, 0) as consumo_anterior")
            ->select("socios.id as id_socio")
            ->select("CONCAT(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre")
            ->select("m.total_mes as total")
            ->select("IFNULL(tp.glosa, 'BOLETA EXENTA') as tipo_dte")
            ->select("metros_estados.glosa as estado")
            ->select("m.estado as id_estado")
            ->join("metros m", "m.id_socio = socios.id")
            ->join("tipo_documento tp", "tp.id = m.id_tipo_documento", "left")
            ->join("metros_estados", "m.estado = metros_estados.id")
            ->where("m.id_apr", $id_apr)
            ->where("socios.estado", 1)
            ->where("m.estado !=", 0) // Excluye registros eliminados
            ->orderBy("m.fecha_ingreso", "DESC"); // Orden descendente por fecha de ingreso

        $lecturas = $builder->get()->getResultArray();

        $morosos           = [];
        $vencen_hoy        = [];
        $consumos_anomalos = [];

        $UMBRAL_ANOMALO = 50;

        foreach ($lecturas as $row) {
            $f_venc       = $row['fecha_venc_raw'];
            $cons_actual  = (float)$row['y'];
            $cons_anter   = (float)$row['consumo_anterior'];
            $diferencia   = $cons_actual - $cons_anter;

            $variacion_pct = 0;
            if ($cons_anter > 0) {
                $variacion_pct = (($cons_actual - $cons_anter) / $cons_anter) * 100;
            }

            $nombre_mes = $mesesEs[$row['num_mes']] ?? $row['num_mes'];
            $mes_consumo_texto = $nombre_mes . ' ' . $row['anio_consumo'];

            $item = [
                'id_lectura'        => $row['id_lectura'],
                'nombre'            => $row['nombre'],
                'mes_consumo'       => $mes_consumo_texto,
                'fecha_vencimiento' => $row['fecha_vencimiento_format'],
                'y'                 => $cons_actual,
                'consumo_anterior'  => $cons_anter,
                'diferencia'        => $diferencia,
                'variacion_pct'     => round($variacion_pct, 1),
                'total'             => (float)$row['total'],
                'tipo_dte'          => $row['tipo_dte'],
                'estado'            => $row['estado']
            ];

            // 1. Morosos: No pagado (id_estado != 2) y con fecha de vencimiento estrictamente anterior a hoy
            if ($row['id_estado'] != 2 && $f_venc < $hoy) {
                $morosos[] = $item;
            }

            // 2. Vencen Hoy: No pagado (id_estado != 2) y con fecha de vencimiento igual a hoy
            if ($row['id_estado'] != 2 && $f_venc == $hoy) {
                $vencen_hoy[] = $item;
            }

            // 3. Consumos Anómalos: No pagado (id_estado != 2) y consumo >= 50 m³
            if ($row['id_estado'] != 2 && $cons_actual >= $UMBRAL_ANOMALO) {
                $consumos_anomalos[] = $item;
            }
        }

        return $this->response
            ->setHeader('Content-Type', 'application/json')
            ->setJSON([
                'contadores' => [
                    'total_morosos'  => count($morosos),
                    'total_vencen'   => count($vencen_hoy),
                    'total_anomalos' => count($consumos_anomalos),
                    'total_general'  => count($morosos) + count($vencen_hoy) + count($consumos_anomalos)
                ],
                'detalles' => [
                    'morosos'           => $morosos,
                    'vencen_hoy'        => $vencen_hoy,
                    'consumos_anomalos' => $consumos_anomalos
                ]
            ]);
    }
}
