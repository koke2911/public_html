<?php

namespace App\Libraries;

class ServicioAcuaria
{
    private $apiEstadistica   = "https://api.acuaria.gestionapr.cl/estadisticas";
    private $apiConsumo       = "https://api.acuaria.gestionapr.cl/predicciones/consumo";
    private $apiGanancias     = "https://api.acuaria.gestionapr.cl/predicciones/ganancias";
    private $apiMorosidad     = "https://api.acuaria.gestionapr.cl/predicciones/morosidad_total";
    private $apiPagos         = "https://api.acuaria.gestionapr.cl/predicciones/pagos";
    private $alertasPredictivas = "https://api.acuaria.gestionapr.cl/alertas/predictivas";
    private $ranking          = "https://api.acuaria.gestionapr.cl/ranking";
    private $simulaciones     = "https://api.acuaria.gestionapr.cl/simulaciones";

    private $timeout = 15;

    /**
     * Consume el endpoint de Alertas Predictivas de Acuaria
     */
    public function obtenerAlertasPredictivas($id_apr)
    {
        return $this->ejecutarPost($this->alertasPredictivas, ['id_apr' => (int)$id_apr]);
    }

    /**
     * Helper genérico para peticiones POST con cURL de CodeIgniter 4
     */
    private function ejecutarPost($url, $payload)
    {
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json'
                ],
                'json'        => $payload,
                'timeout'     => $this->timeout,
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en Acuaria API (' . $url . '): ' . $e->getMessage());
        }

        return null;
    }
}
