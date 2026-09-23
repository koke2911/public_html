<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_socios;
use App\Models\Consumo\Md_metros;
use Mpdf\Mpdf;

class Ctrl_deudores extends BaseController
{

    protected $sesión;
    protected $db;
    protected $socios;
    protected $metros;
    protected $mpdf;

    public function __construct()
    {
        $this->sesión        = session();
        $this->db            = \Config\Database::connect();
        $this->socios        = new Md_socios();
        $this->metros        = new Md_metros();
        $this->mpdf          = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => [
                48,
                90
            ],
            'margin_top'    => 2,
            'margin_left'   => 5,
            'margin_right'  => 3,
            'margin_bottom' => 3
        ]);
    }

    public function validar_sesion()
    {
        if (!$this->sesión->has("id_usuario_ses")) {
            echo "La sesión expiró, actualice el sitio web con F5";
            exit();
        }
    }

    public function datatable_informe_afecto_corte()
    {
        $this->validar_sesion();

        $n_meses       = $this->request->getPost("n_meses") ?? 2;
        $tipo_criterio = $this->request->getPost("tipo_criterio") ?? 'mayor_igual';

        $builder = $this->socios
            ->select("id as id_socio")
            ->select("rol as rol_socio")
            ->select("concat(rut, '-', dv) as rut")
            ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre_socio")
            ->select("afecto_corte(id, id_apr) as meses_pendientes")
            ->select("total_deuda(id, id_apr) as total_deuda")
            ->where("id_apr", $this->sesión->id_apr_ses);

        if ($tipo_criterio === 'exacto') {
            $builder->where("afecto_corte(id, id_apr) =", $n_meses);
        } else {
            $builder->where("afecto_corte(id, id_apr) >=", $n_meses);
        }

        $data = $builder->findAll();

        return json_encode(['data' => $data]);
    }

    public function datatable_deuda_socio()
    {
        $this->validar_sesion();

        $id_socio = $this->request->getPost("id_socio");

        if (empty($id_socio)) {
            return json_encode(["data" => []]);
        }

        $datosDeuda = $this->metros->select("id as id_metros")
            ->select("folio_bolect")
            ->select("metros")
            ->select("total_mes as deuda")
            ->select("date_format(fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento")
            ->select("id_tipo_documento as tipo_documento")
            ->select("url_boleta")
            ->where("id_socio", $id_socio)
            ->where("estado", 1) // 1: Pendiente/Deuda
            ->orderBy("fecha_vencimiento", "DESC")
            ->findAll();

        $data = [];

        foreach ($datosDeuda as $key) {
            $tipo_documento = $key["tipo_documento"];

            if ($tipo_documento == 3 || $tipo_documento == 4) {
                $total = $key["deuda"];
                $iva = intval($total * 0.19);
                $total = $total + $iva;
            } else {
                $total = $key["deuda"];
            }

            $row = [
                "id_metros"         => $key["id_metros"],
                "folio_bolect"      => $key["folio_bolect"] ?? 'S/F',
                "metros"            => $key["metros"] ?? 0,
                "deuda"             => $total,
                "fecha_vencimiento" => $key["fecha_vencimiento"],
                "url_boleta"        => $key["url_boleta"] ?? ''
            ];

            $data[] = $row;
        }

        return json_encode(["data" => $data]);
    }

    public function exportar_pdf($n_meses = 2, $tipo_criterio = 'mayor_igual')
    {
        $this->validar_sesion();

        $id_apr      = $this->sesión->id_apr_ses;
        $apr_ses     = $this->sesión->apr_ses;
        $rut_apr_ses = $this->sesión->rut_apr_ses;
        $dv_apr_ses  = $this->sesión->dv_apr_ses;

        // 1. Obtener información del APR y Comuna para la cabecera
        $datosApr = $this->db->table("apr")->where("id", $id_apr)->get()->getRowArray();
        $direccion_apr = ($datosApr) ? $datosApr["calle"] . ' ' . $datosApr["numero"] . ' ' . $datosApr["resto_direccion"] : '';

        $comuna = '';
        if ($datosApr && !empty($datosApr["id_comuna"])) {
            $datosComuna = $this->db->table("comuna")->where("id", $datosApr["id_comuna"])->get()->getRowArray();
            $comuna = ($datosComuna) ? $datosComuna["nombre"] : '';
        }

        // 2. Verificar existencia del Logo (.png)
        $ruta_logo = FCPATH . $id_apr . '.png';
        $html_logo = '';
        if (file_exists($ruta_logo)) {
            $html_logo = '<img src="' . $ruta_logo . '" style="max-width: 90px; max-height: 90px;" />';
        }

        // 3. Obtención de datos de socios deudores
        $builder = $this->socios
            ->select("id as id_socio")
            ->select("rol as rol_socio")
            ->select("concat(rut, '-', dv) as rut")
            ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre_socio")
            ->select("afecto_corte(id, id_apr) as meses_pendientes")
            ->select("total_deuda(id, id_apr) as total_deuda")
            ->where("id_apr", $id_apr);

        if ($tipo_criterio === 'exacto') {
            $builder->where("afecto_corte(id, id_apr) =", $n_meses);
        } else {
            $builder->where("afecto_corte(id, id_apr) >=", $n_meses);
        }

        $deudores = $builder->findAll();

        // 4. Instancia mPDF tamaño Carta (Letter)
        $mpdfReporte = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'Letter',
            'margin_top'    => 10,
            'margin_left'   => 12,
            'margin_right'  => 12,
            'margin_bottom' => 12
        ]);

        $criterioTexto = ($tipo_criterio === 'exacto') ? "EXACTAMENTE $n_meses MES(ES)" : "MAYOR O IGUAL A $n_meses MES(ES)";

        // 5. Maquetación HTML con el Logo y Datos de la Organización
        $html = '
        <style>
            body { font-family: sans-serif; font-size: 9pt; color: #333; }
            .header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
            .header-table td { vertical-align: top; }
            .title-report { text-align: center; margin: 10px 0; font-size: 13pt; font-weight: bold; color: #2c3e50; }
            .info-box { background-color: #f8f9fa; border: 1px solid #e9ecef; padding: 6px 10px; margin-bottom: 12px; border-radius: 4px; }
            .info-box table { width: 100%; border-collapse: collapse; }
            .info-box td { font-size: 8.5pt; }
            table.data-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
            table.data-table th { background-color: #343a40; color: #ffffff; font-weight: bold; padding: 6px; font-size: 8.5pt; text-align: left; border: 1px solid #343a40; }
            table.data-table td { padding: 5px 6px; font-size: 8.5pt; border: 1px solid #dee2e6; }
            table.data-table tr:nth-child(even) { background-color: #f8f9fa; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .text-bold { font-weight: bold; }
            .text-danger { color: #dc3545; }
            .total-row { background-color: #e9ecef !important; font-weight: bold; }
        </style>

        <table class="header-table">
            <tr>
                <td width="20%" class="text-center">' . $html_logo . '</td>
                <td width="80%" style="padding-left: 10px;">
                    <strong style="font-size: 11pt; color: #1a252f;">' . htmlspecialchars($apr_ses) . '</strong><br>
                    <span style="font-size: 8pt; color: #555;">
                        <strong>GIRO:</strong> CAPTACION, TRATAMIENTO Y DISTRIBUCION DE AGUA<br>
                        <strong>R.U.T:</strong> ' . htmlspecialchars($rut_apr_ses . '-' . $dv_apr_ses) . '<br>
                        <strong>DIRECCIÓN:</strong> ' . htmlspecialchars($direccion_apr) . '<br>
                        <strong>COMUNA:</strong> ' . htmlspecialchars($comuna) . '
                    </span>
                </td>
            </tr>
        </table>

        <div class="title-report">INFORME DE SOCIOS DEUDORES</div>

        <div class="info-box">
            <table>
                <tr>
                    <td><strong>Criterio:</strong> ' . $criterioTexto . '</td>
                    <td class="text-right"><strong>Fecha Emisión:</strong> ' . date('d/m/Y H:i:s') . '</td>
                </tr>
            </table>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th class="text-center" width="10%">N° ROL</th>
                    <th class="text-center" width="18%">RUT</th>
                    <th width="42%">NOMBRE COMPLETO DEL SOCIO</th>
                    <th class="text-center" width="15%">MESES MORA</th>
                    <th class="text-right" width="15%">TOTAL DEUDA</th>
                </tr>
            </thead>
            <tbody>';

        $totalDeudaGeneral = 0;
        $totalDeudores     = count($deudores);

        if (!empty($deudores)) {
            foreach ($deudores as $row) {
                $monto = (float) $row['total_deuda'];
                $totalDeudaGeneral += $monto;

                $html .= '
                <tr>
                    <td class="text-center">' . htmlspecialchars($row['rol_socio']) . '</td>
                    <td class="text-center">' . htmlspecialchars($row['rut']) . '</td>
                    <td>' . htmlspecialchars($row['nombre_socio']) . '</td>
                    <td class="text-center text-bold text-danger">' . htmlspecialchars($row['meses_pendientes']) . ' Meses</td>
                    <td class="text-right text-bold text-danger">$ ' . number_format($monto, 0, '', '.') . '</td>
                </tr>';
            }

            $html .= '
                <tr class="total-row">
                    <td colspan="3" class="text-right">TOTALES GENERALES (' . $totalDeudores . ' Socios):</td>
                    <td class="text-center text-bold">' . $totalDeudores . ' Deudores</td>
                    <td class="text-right text-bold text-danger">$ ' . number_format($totalDeudaGeneral, 0, '', '.') . '</td>
                </tr>';
        } else {
            $html .= '
                <tr>
                    <td colspan="5" class="text-center">No se encontraron socios deudores bajo el criterio seleccionado.</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        $mpdfReporte->WriteHTML($html);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdfReporte->Output('Informe_Deudores_' . $n_meses . 'meses_' . date('Ymd_His') . '.pdf', 'I');
    }
}
