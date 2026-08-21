<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

use App\Models\Pagos\Md_cola_emails;
use App\Models\Consumo\Md_metros;
use App\Models\Formularios\Md_socios;
use App\Controllers\Pagos\Ctrl_boleta_electronica;

class ProcesarColaEmails extends BaseCommand
{
    protected $group       = 'Emails';
    protected $name        = 'emails:procesar-cola';
    protected $description = 'Procesa y envía los correos pendientes en la tabla cola_emails.';

    public function run(array $params)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4096M');

        $colaModel   = new Md_cola_emails();
        $metrosModel = new Md_metros();
        $sociosModel = new Md_socios();

        // Instanciar controlador para reutilizar métodos auxiliares (generarGrafico e imagenTablaConsumo)
        $ctrlBoleta = new Ctrl_boleta_electronica();

        // Obtener hasta 50 correos pendientes cuya fecha/hora programada ya llegó
        $pendientes = $colaModel->where('estado', 'PENDIENTE')
            ->where('programado_para <=', date('Y-m-d H:i:s'))
            ->where('intentos <', 3)
            ->orderBy('id', 'ASC')
            ->limit(50)
            ->findAll();

        if (empty($pendientes)) {
            CLI::write('No hay correos pendientes para enviar.');
            return;
        }

        foreach ($pendientes as $item) {
            // Marcar como PROCESANDO para evitar concurrencia en la cola
            $colaModel->update($item['id'], ['estado' => 'PROCESANDO']);

            try {
                if ($item['formato'] === 'nuevo') {
                    $resultado = $this->procesarFormatoNuevo($item, $metrosModel, $sociosModel, $ctrlBoleta);
                } else {
                    $resultado = $this->procesarFormatoAntiguo($item, $metrosModel, $sociosModel);
                }

                if ($resultado) {
                    $colaModel->update($item['id'], ['estado' => 'ENVIADO']);
                    // Marcar en la tabla principal
                    $metrosModel->save([
                        'id'          => $item['id_metro'],
                        'estado_mail' => 'OK'
                    ]);
                    CLI::write("Correo enviado ID Metro: " . $item['id_metro'], 'green');
                } else {
                    $this->registrarError($colaModel, $item, 'Error en el envío del email (o socio sin email registrado)');
                }
            } catch (\Throwable $e) {
                $this->registrarError($colaModel, $item, $e->getMessage());
                log_message('error', 'Error procesando correo ID ' . $item['id'] . ': ' . $e->getMessage());
            }
        }
    }

    private function registrarError($colaModel, $item, $msg)
    {
        $intentos = $item['intentos'] + 1;
        $estado   = ($intentos >= 3) ? 'ERROR' : 'PENDIENTE';

        $colaModel->update($item['id'], [
            'estado'    => $estado,
            'intentos'  => $intentos,
            'error_msg' => $msg
        ]);
        CLI::write("Error en ID Metro " . $item['id_metro'] . ": " . $msg, 'red');
    }

    private function procesarFormatoAntiguo($item, $metrosModel, $sociosModel)
    {
        $datosMetros = $metrosModel->select("folio_bolect, id_socio, url_boleta, date_format(fecha_ingreso, '%m-%Y') as mes_consumo")
            ->where("id", $item['id_metro'])
            ->first();

        if (!$datosMetros) {
            return false;
        }

        $datosSocios = $sociosModel->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre_socio, ifnull(socios.email,'--') as email")
            ->where("socios.id", $datosMetros['id_socio'])
            ->first();

        if (!$datosSocios || $datosSocios['email'] == '--' || empty($datosSocios['email'])) {
            return false;
        }

        $email = \Config\Services::email();
        $email->clear(true);

        $subject = 'Tu boleta de Agua ya está disponible';
        $message = '<p>¡Hola ' . $datosSocios['nombre_socio'] . '!<br>
                    Tu Boleta de Agua Potable, correspondiente al Mes: ' . $datosMetros['mes_consumo'] . ', Ya está disponible.<BR>Puedes realizar el pago, de forma "online", a través de: www.puntoblue.cl<BR> o escanenado con tu teléfono móvil el "codigo QR" adjunto en este Correo.<BR><BR>
                    ¡Saludos Cordiales!</p>';

        if (file_exists(FCPATH . 'QR_punto_blue.png')) {
            $email->attach(FCPATH . 'QR_punto_blue.png', 'inline');
        }

        $email->setTo($datosSocios['email']);
        $email->setFrom("boletas@gestionapr.cl", "Software APR");
        $email->setSubject($subject);
        $email->setMessage($message);

        $boleta = $item['id_apr'] . '_' . $datosMetros['folio_bolect'] . '.pdf';
        $dirBoletas = FCPATH . 'boletas/';

        if (!is_dir($dirBoletas)) {
            mkdir($dirBoletas, 0775, true);
        }

        $pathBoleta = $dirBoletas . $boleta;

        if (!file_exists($pathBoleta)) {
            $homepage = file_get_contents($datosMetros['url_boleta']);
            file_put_contents($pathBoleta, $homepage);
        }

        $email->attach($pathBoleta);
        return $email->send();
    }

    private function procesarFormatoNuevo($item, $metrosModel, $sociosModel, $ctrlBoleta)
    {
        $folio  = $item['id_metro'];
        $id_apr = $item['id_apr'];

        if (!is_dir(FCPATH . 'uploads')) {
            mkdir(FCPATH . 'uploads', 0775, true);
        }

        if (!is_dir(FCPATH . 'boletas_nuevas')) {
            mkdir(FCPATH . 'boletas_nuevas', 0775, true);
        }

        // =========================================
        // DATOS BOLETA
        // =========================================
        $datosMetros = $metrosModel->select("folio_bolect, id_socio, url_boleta, fecha_ingreso")
            ->select("date_format(fecha_ingreso, '%m-%Y') as mes_consumo")
            ->where("id", $folio)
            ->first();

        if (!$datosMetros) {
            return false;
        }

        $folio_sii     = $datosMetros["folio_bolect"];
        $url_boleta    = $datosMetros["url_boleta"];
        $id_socio      = $datosMetros["id_socio"];
        $mes           = $datosMetros["mes_consumo"];
        $fecha_ingreso = $datosMetros["fecha_ingreso"];

        // =========================================
        // DATOS SOCIO
        // =========================================
        $datosSocios = $sociosModel->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre_socio")
            ->select("ifnull(socios.email, '--') as email")
            ->where("socios.id", $id_socio)
            ->first();

        if (!$datosSocios) {
            return false;
        }

        $nombre_socio = $datosSocios['nombre_socio'];
        $email_socio  = $datosSocios['email'];

        if ($email_socio == "--" || empty($email_socio)) {
            return false;
        }

        // =========================================
        // PDF FINAL
        // =========================================
        $pdf_final = FCPATH . 'boletas_nuevas/' . $id_apr . '_' . $folio_sii . '.pdf';

        if (!file_exists($pdf_final)) {
            $partes      = explode('-', $fecha_ingreso);
            $mes_consumo = $partes[1] . '-' . $partes[2];

            // Invocación de métodos gráficos desde el controlador
            $ctrlBoleta->generarGrafico($id_socio, $mes_consumo, $folio);
            $ctrlBoleta->imagenTablaConsumo($folio);

            $tempPdf = WRITEPATH . 'uploads/temp_' . $folio . '.pdf';
            file_put_contents($tempPdf, file_get_contents($url_boleta));

            if (!file_exists($tempPdf)) {
                return false;
            }

            // Path de Recortes
            $encabezadoPath = FCPATH . 'uploads/encabezado_' . $folio . '.jpg';
            $timbrePath     = FCPATH . 'uploads/timbre_' . $folio . '.jpg';
            $consumoPath    = FCPATH . 'uploads/consumo_' . $folio . '.jpg';
            $detallePath    = FCPATH . 'uploads/detalle_' . $folio . '.jpg';
            $socioPath      = FCPATH . 'uploads/socio_' . $folio . '.jpg';
            $glosaPath      = FCPATH . 'uploads/glosa_' . $folio . '.jpg';

            $generarImagenes = !file_exists($encabezadoPath)
                || !file_exists($timbrePath)
                || !file_exists($consumoPath)
                || !file_exists($detallePath)
                || !file_exists($socioPath)
                || !file_exists($glosaPath);

            if ($generarImagenes) {
                $pdf = new \Imagick();
                $pdf->setResolution(250, 250);
                $pdf->readImage($tempPdf . '[0]');
                $pdf->setImageFormat('jpg');

                $width  = $pdf->getImageWidth();
                $height = $pdf->getImageHeight();

                $normalizar = function ($img) {
                    $img->setImagePage(0, 0, 0, 0);
                    $img->setImageFormat('jpeg');
                    $img->stripImage();
                    $img->setImageColorspace(\Imagick::COLORSPACE_RGB);
                    $img->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                    $img->setBackgroundColor('white');
                    $img = $img->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
                    $img->setInterlaceScheme(\Imagick::INTERLACE_NO);
                    $img->thumbnailImage(900, 0);
                    $img->setImageCompressionQuality(75);
                    return $img;
                };

                // TIMBRE
                $img = clone $pdf;
                $img->cropImage($width * 0.6, $height * 0.15, $width * 0.45, $height * 0.75);
                $img = $normalizar($img);
                $img->writeImage($timbrePath);
                $img->clear();
                $img->destroy();

                // ENCABEZADO
                $img = clone $pdf;
                $img->cropImage($width * 0.9, $height * 0.13, $width * 0.04, 0);
                $img = $normalizar($img);
                $img->writeImage($encabezadoPath);
                $img->clear();
                $img->destroy();

                // CONSUMO
                $img = clone $pdf;
                $img->cropImage($width * 0.35, $height * 0.12, $width * 0.04, $height * 0.44);
                $img = $normalizar($img);
                $img->writeImage($consumoPath);
                $img->clear();
                $img->destroy();

                // DETALLE
                $img = clone $pdf;
                $img->cropImage($width * 0.45, $height * 0.32, $width * 0.52, $height * 0.44);
                $img = $normalizar($img);
                $img->writeImage($detallePath);
                $img->clear();
                $img->destroy();

                // SOCIO
                $img = clone $pdf;
                $img->cropImage($width * 0.92, $height * 0.075, $width * 0.03, $height * 0.13);
                $img = $normalizar($img);
                $img->writeImage($socioPath);
                $img->clear();
                $img->destroy();

                // GLOSA
                $img = clone $pdf;
                $img->cropImage($width * 0.60, $height * 0.066, $width * 0.36, $height * 0.214);
                $img = $normalizar($img);
                $img->writeImage($glosaPath);
                $img->clear();
                $img->destroy();

                $pdf->clear();
                $pdf->destroy();
            }

            $css = '
            <style>
                @page{ margin:8px; }
                body{ font-family: Arial, Helvetica, sans-serif; font-size:10px; color:#18343b; background:#eef2f3; }
                .page{ width:100%; background:#ffffff; border:1px solid #c8d3d6; padding:6px; }
                .section{ border:1px solid #cfdcdf; border-radius:7px; overflow:hidden; margin-bottom:6px; background:#ffffff; }
                .title{ background:#1f4e5f; border-bottom:1px solid #183c49; padding:5px 8px; font-size:10px; font-weight:bold; text-transform:uppercase; color:#ffffff; letter-spacing:.5px; }
                .content{ padding:4px; background:#fbfcfc; }
                .img-full{ width:100%; display:block; }
                .row{ width:100%; clear:both; }
                .left{ width:43%; float:left; }
                .right{ width:55%; float:right; }
                .card{ border:1px solid #d5e1e4; border-radius:6px; overflow:hidden; margin-bottom:6px; background:#ffffff; }
                .card-header{ background:#e8f0f2; border-bottom:1px solid #d1dfe3; padding:4px 7px; font-size:9px; font-weight:bold; color:#1f4e5f; letter-spacing:.3px; }
                .card-body{ padding:4px; background:#fcfdfd; }
                .compact img{ transform:scale(0.96); transform-origin:top left; }
                .detalle img{ width:100%; max-height:520px; }
                .consumo img{ width:100%; max-height:170px; }
                .tabla img{ width:100%; max-height:200px; }
                .grafico img{ width:100%; max-height:180px; }
                .badge{ text-align:center; margin-top:4px; }
                .badge span{ background:#1f4e5f; color:#ffffff; padding:4px 10px; border-radius:20px; font-size:8px; font-weight:bold; letter-spacing:.4px; }
                .timbre-img-full{ width:100%; max-height:110px; object-fit:contain; display:block; }
                .clear{ clear:both; }
            </style>';

            $html = '
            <div class="page">
                <div class="section"><div class="content"><img src="uploads/encabezado_' . $folio . '.jpg" class="img-full"></div></div>
                <div class="section"><div class="title">INFORMACIÓN DEL SOCIO</div><div class="content compact"><img src="uploads/socio_' . $folio . '.jpg" class="img-full"></div></div>
                <div class="section"><div class="title">DETALLE FACTURACIÓN</div><div class="content compact"><img src="uploads/glosa_' . $folio . '.jpg" class="img-full"></div></div>
                <div class="row">
                    <div class="left">
                        <div class="card consumo"><div class="card-header">RESUMEN CONSUMO</div><div class="card-body"><img src="uploads/consumo_' . $folio . '.jpg"></div></div>
                        <div class="card tabla"><div class="card-header">DETALLE POR TRAMO</div><div class="card-body"><img src="tabla_consumo_' . $folio . '.jpg"></div></div>
                        <div class="card grafico"><div class="card-header">HISTORIAL CONSUMO</div><div class="card-body"><img src="grafico_' . $folio . '.jpg"></div></div>
                    </div>
                    <div class="right">
                        <div class="section detalle"><div class="title">ESTADO DE CUENTA</div><div class="content"><img src="uploads/detalle_' . $folio . '.jpg"></div></div>
                        <div class="badge"><span>DOCUMENTO VÁLIDO S.I.I.</span></div>
                        <div class="timbre-box-full"><img src="uploads/timbre_' . $folio . '.jpg" class="timbre-img-full"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>';

            $mpdf = new \Mpdf\Mpdf([
                'format'  => 'LETTER',
                'tempDir' => WRITEPATH . 'mpdf'
            ]);

            $mpdf->SetBasePath(FCPATH);
            $mpdf->showImageErrors = true;
            $mpdf->WriteHTML($css, 1);
            $mpdf->WriteHTML($html, 2);
            $mpdf->Output($pdf_final, 'F');

            @unlink($tempPdf);
        }

        // =========================================
        // ENVÍO DE EMAIL
        // =========================================
        $email = \Config\Services::email();
        $email->clear(true);

        $subject = 'Tu boleta de Agua ya está disponible';
        $message = '
            <p>
                ¡Hola ' . $nombre_socio . '!<br><br>
                Tu Boleta de Agua Potable correspondiente al mes: <b>' . $mes . '</b> ya está disponible.<br><br>
                Puedes pagar online en: <b>www.puntoblue.cl</b><br><br>
                ¡Saludos Cordiales!
            </p>';

        $email->setTo($email_socio);
        $email->setFrom("boletas@gestionapr.cl", "Software APR");
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->attach($pdf_final);

        return $email->send();
    }
}
