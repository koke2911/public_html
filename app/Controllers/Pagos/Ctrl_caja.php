<?php

namespace App\Controllers\Pagos;

use Mpdf\Mpdf;
use Config\Database;
use App\Models\Pagos\Md_caja;
use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Pagos\Md_caja_traza;
use App\Models\Finanzas\Md_egresos;
use App\Models\Pagos\Md_caja_detalle;
use App\Models\Formularios\Md_socios;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_convenios;
use App\Models\Finanzas\Md_egresos_traza;
use App\Models\Formularios\Md_socios_traza;
use App\Models\Finanzas\Md_egresos_simples;
use App\Models\Formularios\Md_convenio_traza;
use App\Models\Formularios\Md_convenio_detalle;

class Ctrl_caja extends BaseController {

  protected $metros;
  protected $metros_traza;
  protected $caja;
  protected $caja_detalle;
  protected $caja_traza;
  protected $convenios;
  protected $convenio_detalle;
  protected $convenio_traza;
  protected $socios;
  protected $socios_traza;
  protected $egresos;
  protected $egresos_traza;
  protected $egresos_simples;
  protected $sesión;
  protected $db;
  protected $mpdf;

  public function __construct() {
    $this->metros           = new Md_metros();
    $this->metros_traza     = new Md_metros_traza();
    $this->caja             = new Md_caja();
    $this->caja_detalle     = new Md_caja_detalle();
    $this->caja_traza       = new Md_caja_traza();
    $this->convenios        = new Md_convenios();
    $this->convenio_detalle = new Md_convenio_detalle();
    $this->convenio_traza   = new Md_convenio_traza();
    $this->socios           = new Md_socios();
    $this->socios_traza     = new Md_socios_traza();
    $this->egresos          = new Md_egresos();
    $this->egresos_traza    = new Md_egresos_traza();
    $this->egresos_simples  = new Md_egresos_simples();
    $this->sesión           = session();
    $this->db               = Database::connect();
    $this->mpdf             = new Mpdf([
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

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_deuda_socio($id_socio) {
    $this->validar_sesion();

    $datosDeuda = $this->metros->select("id as id_metros")
                               ->select("total_mes as deuda")
                               ->select("date_format(fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento")
                               ->where("id_socio", $id_socio)
                               ->where("estado", 1)
                               ->findAll();

    foreach ($datosDeuda as $key) {
      $row = [
       "id_metros"         => $key["id_metros"],
       "deuda"             => $key["deuda"],
       "fecha_vencimiento" => $key["fecha_vencimiento"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      $salida = ["data" => ""];

      return json_encode($salida);
    }
  }

  public function guardar_pago() {
    $this->validar_sesion();

    define("OK", 1);
    define("PAGADO", 2);
    define("PAGADO_TRAZA", 5);
    define("ACTIVO", 1);
    define("CONVENIO_PAGADO", 1);
    define("CUOTA_PAGADA", 5);

    $id_socio       = $this->request->getPost("id_socio");
    $total_pagar    = $this->request->getPost("total_pagar");
    $entregado      = $this->request->getPost("entregado");
    $vuelto         = $this->request->getPost("vuelto");
    $descuento      = $this->request->getPost("descuento");
    $forma_pago     = $this->request->getPost("forma_pago");
    $n_transaccion  = $this->request->getPost("n_transaccion");
    $abono          = $this->request->getPost("abono");
    $arr_ids_metros = $this->request->getPost("arr_ids_metros");

    if ($n_transaccion == "") {
      $n_transaccion = NULL;
    }

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $datosPago = [
     "total_pagar"        => $total_pagar,
     "entregado"          => $entregado,
     "vuelto"             => $vuelto,
     "descuento"          => $descuento,
     "id_forma_pago"      => $forma_pago,
     "numero_transaccion" => $n_transaccion,
     "id_socio"           => $id_socio,
     "id_usuario"         => $id_usuario,
     "fecha"              => $fecha,
     "id_apr"             => $id_apr
    ];

    $this->db->transStart();

    $this->caja->save($datosPago);

    $datosPago_  = $this->caja->select("max(id) as id_caja")
                              ->where("estado", 1)
                              ->first();
    $id_caja     = $datosPago_["id_caja"];
    $estado_pago = PAGADO;

    foreach ($arr_ids_metros as $id_metros) {
      $datosPagoDetalle = [
       "id_caja"   => $id_caja,
       "id_metros" => $id_metros
      ];

      $this->caja_detalle->save($datosPagoDetalle);

      $datosMetros = $this->metros->select("total_servicios")
                                  ->select("date_format(fecha_vencimiento, '%m-%Y') as fecha_vencimiento")
                                  ->where("id", $id_metros)
                                  ->first();

      if ($datosMetros["total_servicios"] != 0) {
        $datosConvenio = $this->convenios->select("convenio_detalle.id as id_convenio_detalle")
                                         ->select("convenios.id as id_convenio")
                                         ->join("convenio_detalle", "convenio_detalle.id_convenio = convenios.id")
                                         ->where("convenios.estado", ACTIVO)
                                         ->where("date_format(convenio_detalle.fecha_pago, '%m-%Y')", $datosMetros["fecha_vencimiento"])
                                         ->where("convenios.id_socio", $id_socio)
                                         ->findAll();

        if ($datosConvenio != NULL) {
          foreach ($datosConvenio as $key) {
            $datosConvenioDetalle = [
             "id"     => $key["id_convenio_detalle"],
             "pagado" => CONVENIO_PAGADO
            ];

            $this->convenio_detalle->save($datosConvenioDetalle);

            $datosConvenioTraza = [
             "id_convenio" => $key["id_convenio"],
             "estado"      => CUOTA_PAGADA,
             "observacion" => "Cuota pagada, correspondiente a " . $datosMetros["fecha_vencimiento"] . ".",
             "id_usuario"  => $id_usuario,
             "fecha"       => $fecha
            ];

            $this->convenio_traza->save($datosConvenioTraza);
          }
        }
      }

      $datosMetrosSave = [
       "id"         => $id_metros,
       "estado"     => $estado_pago,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      $this->metros->save($datosMetrosSave);

      $datosMetrosTraza = [
       "id_metros"  => $id_metros,
       "estado"     => PAGADO_TRAZA,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      $this->metros_traza->save($datosMetrosTraza);
    }

    $datosPagoTraza = [
     "id_caja"    => $id_caja,
     "estado"     => 1,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->caja_traza->save($datosPagoTraza);

    if (intval($abono) > 0) {
      define("PAGO_ABONO", 7);

      if (intval($abono) <= intval($total_pagar)) {
        $abono = 0;
      } else {
        $abono = intval($abono) - intval($total_pagar);
      }

      $datosSocios = [
       "id"         => $id_socio,
       "abono"      => $abono,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      $this->socios->save($datosSocios);

      $datosSociosTraza = [
       "id_socio"   => $id_socio,
       "estado"     => PAGO_ABONO,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      $this->socios_traza->save($datosSociosTraza);
    }

    if (intval($descuento) > 0) {
      define("DESCUENTOS", 0);
      define("CREAR_EGRESO", 1);
      define("EGRESO_SIMPLE", 2);
      define("SOCIO", "socio");
      define("EGRESO_AUTOMATICO", 0);

      $datosEgreso = [
       "tipo_egreso" => EGRESO_SIMPLE,
       "id_usuario"  => $id_usuario,
       "fecha"       => $fecha,
       "id_apr"      => $id_apr
      ];

      $this->egresos->save($datosEgreso);

      $obtener_id = $this->egresos->select("max(id) as id_egreso")
                                  ->first();
      $id_egreso  = $obtener_id["id_egreso"];

      $datosEgresoTraza = [
       "id_egreso"  => $id_egreso,
       "estado"     => CREAR_EGRESO,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      $this->egresos_traza->save($datosEgresoTraza);

      $datosEgresoSimple = [
       "id_tipo_egreso" => DESCUENTOS,
       "fecha"          => date("Y-m-d"),
       "monto"          => $descuento,
       "tipo_entidad"   => SOCIO,
       "id_entidad"     => $id_socio,
       "id_motivo"      => EGRESO_AUTOMATICO,
       "id_egreso"      => $id_egreso
      ];

      $this->egresos_simples->save($datosEgresoSimple);
    }

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      $respuesta = [
       "estado"  => OK,
       "id_caja" => $id_caja
      ];

      return json_encode($respuesta);
    } else {
      $respuesta = [
       "estado"  => "Error al registrar el pago",
       "id_caja" => $id_caja
      ];

      return json_encode($respuesta);
    }
  }

  function emitir_comprobante_pago($datos) {
    $font_size = 60;

    $datos = json_decode($datos, TRUE);

    $total_pagar   = $datos["total_pagar"];
    $entregado     = $datos["entregado"];
    $vuelto        = $datos["vuelto"];
    $forma_pago    = $datos["forma_pago_glosa"];
    $n_transaccion = $datos["n_transaccion"];
    $nombre_socio  = $datos["nombre_socio"];
    $descuento     = $datos["descuento"];
    $id_caja       = $datos["id_caja"];
    $this->mpdf->SetTitle('Boucher Comprobante de Pago');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;" align="center"><img src="' . $this->sesión->id_apr_ses . '.png" width="50" /></div><br>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;" align="center"><b>COMPROBANTE DE PAGO</b></div><br>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;" align="center">' . $this->sesión->apr_ses . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;" align="center">Fecha: ' . date("d-m-Y") . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;" align="center">Hora: ' . date("H:i:s") . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;" align="center">Usuario: ' . $this->sesión->nombres_ses . ' ' . $this->sesión->ape_pat_ses . ' ' . $this->sesión->ape_mat_ses . '</div><br>');

    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;" align="center"><b>DETALLE DEL PAGO</b></div><br>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Folio de Boleta: ' . $id_caja . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Total a Pagar: ' . $total_pagar . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Descuento: ' . $descuento . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Entregado: ' . $entregado . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Vuelto: ' . $vuelto . '</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Medio de Pago: ' . $forma_pago . '</div>');

    if ($n_transaccion != "") {
      $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">N° Trans: ' . $n_transaccion . '</div>');
    }

    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Nombre Socio:</div>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">' . $nombre_socio . '</div><br>');
    $this->mpdf->WriteHTML('<div style="font-size: ' . $font_size . '%;">Comprobante no válido como boleta</div>');
    $this->mpdf->Output();
    exit;
    $verificar_dispositivo = $this->verificar_dispositivo();
    if ($verificar_dispositivo == "mobil" or $verificar_dispositivo == "tablet") {
      return $this->mpdf->Output("Boucher.pdf", "D");
    } else {
      return redirect()->to($this->mpdf->Output("Boucher.pdf", "I"));
    }
  }

  public function verificar_dispositivo() {
    $tablet_browser = 0;
    $mobile_browser = 0;
    $body_class     = 'desktop';

    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
      $tablet_browser ++;
      $body_class = "tablet";
    }

    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
      $mobile_browser ++;
      $body_class = "mobile";
    }

    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
      $mobile_browser ++;
      $body_class = "mobile";
    }

    $mobile_ua     = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = [
     'w3c ',
     'acs-',
     'alav',
     'alca',
     'amoi',
     'audi',
     'avan',
     'benq',
     'bird',
     'blac',
     'blaz',
     'brew',
     'cell',
     'cldc',
     'cmd-',
     'dang',
     'doco',
     'eric',
     'hipt',
     'inno',
     'ipaq',
     'java',
     'jigs',
     'kddi',
     'keji',
     'leno',
     'lg-c',
     'lg-d',
     'lg-g',
     'lge-',
     'maui',
     'maxo',
     'midp',
     'mits',
     'mmef',
     'mobi',
     'mot-',
     'moto',
     'mwbp',
     'nec-',
     'newt',
     'noki',
     'palm',
     'pana',
     'pant',
     'phil',
     'play',
     'port',
     'prox',
     'qwap',
     'sage',
     'sams',
     'sany',
     'sch-',
     'sec-',
     'send',
     'seri',
     'sgh-',
     'shar',
     'sie-',
     'siem',
     'smal',
     'smar',
     'sony',
     'sph-',
     'symb',
     't-mo',
     'teli',
     'tim-',
     'tosh',
     'tsm-',
     'upg1',
     'upsi',
     'vk-v',
     'voda',
     'wap-',
     'wapa',
     'wapi',
     'wapp',
     'wapr',
     'webc',
     'winw',
     'winw',
     'xda ',
     'xda-'
    ];

    if (in_array($mobile_ua, $mobile_agents)) {
      $mobile_browser ++;
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
      $mobile_browser ++;
      //Check for tablets on opera mini alternative headers
      $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
      if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
        $tablet_browser ++;
      }
    }
    if ($tablet_browser > 0) {
      // Si es tablet has lo que necesites
      return 'tablet';
    } else {
      if ($mobile_browser > 0) {
        // Si es dispositivo mobil has lo que necesites
        return 'mobil';
      } else {
        // Si es ordenador de escritorio has lo que necesites
        return 'ordenador';
      }
    }
  }
}