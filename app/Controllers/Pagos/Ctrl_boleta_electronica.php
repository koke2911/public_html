<?php

namespace App\Controllers\Pagos;

use App\ThirdParty\Touchef;
use App\Models\Pagos\Md_caja;
use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Formularios\Md_socios;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_arranques;
use App\Models\Formularios\Md_medidores;
use App\Models\Configuracion\Md_comunas;
use App\Models\Formularios\Md_repactaciones;
use App\Models\Configuracion\Md_observaciones_dte;

class Ctrl_boleta_electronica extends BaseController {

  protected $metros;
  protected $metros_traza;
  protected $socios;
  protected $comunas;
  protected $apr;
  protected $arranques;
  protected $medidores;
  protected $repactaciones;
  protected $observaciones_dte;
  protected $caja;
  protected $sesión;
  protected $db;
  protected $error = "";

  public function __construct() {
    $this->metros            = new Md_metros();
    $this->metros_traza      = new Md_metros_traza();
    $this->socios            = new Md_socios();
    $this->comunas           = new Md_comunas();
    $this->apr               = new Md_apr();
    $this->arranques         = new Md_arranques();
    $this->medidores         = new Md_medidores();
    $this->repactaciones     = new Md_repactaciones();
    $this->observaciones_dte = new Md_observaciones_dte();
    $this->caja              = new Md_caja();
    $this->sesión            = session();
    $this->db                = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_boleta_electronica($datosBusqueda) {
    $this->validar_sesion();
    echo $this->metros->datatable_boleta_electronica($this->db, $this->sesión->id_apr_ses, $datosBusqueda);
  }

  public function periodo_desde($mes_consumo) {
    $mes_consumo = explode("-", $mes_consumo);
    $month       = $mes_consumo[0];
    $year        = $mes_consumo[1];
    $my_date     = $year . '-' . $month . '-01';

    return $my_date;
  }

  public function periodo_hasta($mes_consumo) {
    $mes_consumo = explode("-", $mes_consumo);
    $month       = $mes_consumo[0];
    $year        = $mes_consumo[1];
    $my_date     = date("Y-m-t", strtotime($year . '-' . $month . '-01'));

    return $my_date;
  }

  public function mes_pago($fecha_vencimiento) {
    $mes_pago = explode("-", $fecha_vencimiento);
    $month    = $mes_pago[1];
    $year     = $mes_pago[0];
    $my_date  = $month . '-' . $year;

    return $my_date;
  }

  public function emitir_dte() {
    $this->validar_sesion();
    define("BOLETA_EXENTA", 41);
    define("FACTURA_EXENTA", 34);
    define("ASIGNA_FOLIO_BOLECT", 7);
    define("PENDIENTE", 1);
    define("ACTIVO", 1);

    $url     = 'https://libredte.cl';
    $hash    = $this->sesión->hash_apr_ses;
    $rut_apr = $this->sesión->rut_apr_ses . "-" . $this->sesión->dv_apr_ses;

    $folios = $this->request->getPost("arr_boletas");

    foreach ($folios as $folio) {
      $datosMetros = $this->metros
       ->select("id_socio")
       ->select("monto_facturable")
       ->select("total_mes")
       ->select("total_servicios")
       ->select("multa")
       ->select("cuota_repactacion")
       ->select("consumo_anterior")
       ->select("consumo_actual")
       ->select("metros")
       ->select("monto_subsidio")
       ->select("subtotal")
       ->select("alcantarillado")
       ->select("cuota_socio")
       ->select("otros")
       ->select("iva")
       ->select("date_format(fecha_ingreso, '%m-%Y') as mes_consumo")
       ->select("date_format(fecha_vencimiento, '%Y-%m-%d') as fecha_vencimiento")
       ->select("ifnull(elt(field(tipo_facturacion, 1, 2), 'NORMAL', 'TÉRMINO MEDIO'), 'NO REGISTRADO') as tipo_facturacion")
       ->where("id", $folio)
       ->first();

      $consumo_anterior  = $datosMetros["consumo_anterior"];
      $consumo_actual    = $datosMetros["consumo_actual"];
      $metros_           = $datosMetros["metros"];
      $total_mes         = $datosMetros["total_mes"];
      $monto_facturable  = $datosMetros["monto_facturable"];
      $cuota_repactacion = $datosMetros["cuota_repactacion"];
      $total_servicios   = $datosMetros["total_servicios"];
      $multa             = $datosMetros["multa"];
      $monto_subsidio    = $datosMetros["monto_subsidio"];
      $subtotal          = $datosMetros["subtotal"];
      $alcantarillado    = $datosMetros["alcantarillado"];
      $cuota_socio       = $datosMetros["cuota_socio"];
      $otros             = $datosMetros["otros"];
      $iva               = $datosMetros["iva"];
      $mes_consumo       = $datosMetros["mes_consumo"];
      $periodo_desde     = $this->periodo_desde($mes_consumo);
      $periodo_hasta     = $this->periodo_hasta($mes_consumo);
      $fecha_vencimiento = $datosMetros["fecha_vencimiento"];
      $id_socio          = $datosMetros["id_socio"];

      if (intval($total_mes) > 0) {
        $datosSocios = $this->socios
         ->select("concat(socios.rut, '-', socios.dv) as rut_socio")
         ->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre_socio")
         ->select("concat(socios.calle, ', ', socios.numero, ', ', socios.resto_direccion) as direccion")
         ->select("socios.rol")
         ->select("socios.id_comuna")
         ->select("a.id_tipo_documento as tipo_documento")
         ->select("m.numero as num_medidor")
         ->select("cf.cargo_fijo")
         ->select("s.nombre as sector")
         ->join("arranques a", "a.id_socio = socios.id")
         ->join("sectores s", "a.id_sector = s.id")
         ->join("medidores m", "a.id_medidor = m.id")
         ->join("apr_cargo_fijo cf", "cf.id_apr = socios.id_apr and cf.id_diametro = m.id_diametro")
         ->where("socios.id", $id_socio)
         ->first();

        if ($datosSocios["rut_socio"] != "") {
          $rut_socio = $datosSocios["rut_socio"];
        } else {
          $rut_socio = "66666666-6";
        }

        if ($datosSocios["nombre_socio"] != "") {
          $nombre_socio = $datosSocios["nombre_socio"];
        } else {
          $nombre_socio = "Sin RUT";
        }

        if ($datosSocios["direccion"] != ", , ") {
          $direccion = $datosSocios["direccion"];
        } else {
          $direccion = "Sin Dirección";
        }

        if ($datosSocios["id_comuna"] != "") {
          $datosComuna = $this->comunas->select("nombre")
                                       ->where("id", $datosSocios["id_comuna"])
                                       ->first();
          $comuna      = $datosComuna["nombre"];
        } else {
          $comuna = "Sin Comuna";
        }

        helper('tipo_dte');
        $tipo_dte = tipo_dte($datosSocios["tipo_documento"]);

        $num_medidor = $datosSocios["num_medidor"];
        $cargo_fijo  = $datosSocios["cargo_fijo"];
        $sector      = $datosSocios["sector"];

        $datosParaGrafico = $this->metros->select("date_format(fecha_ingreso, '%m-%Y') as fecha")
                                         ->select("consumo_actual")
                                         ->where("id_socio", $id_socio)
                                         ->whereNotIn("estado", [0])
                                         ->findAll();
        $datos_graf       = [];

        foreach ($datosParaGrafico as $key) {
          $datos_graf[$key["fecha"]] = $key["consumo_actual"];
        }

        $datosDeuda            = $this->metros->select("total_mes")
                                              ->where("id_socio", $id_socio)
                                              ->where("estado", PENDIENTE)
                                              ->where("id<", $folio)
                                              ->findAll();
        $datosObservacionesDte = $this->observaciones_dte
         ->select("titulo")
         ->select("observacion")
         ->where("id_apr", $this->sesión->id_apr_ses)
         ->where("estado", ACTIVO)
         ->findAll();

        $datosUltPagoId = $this->caja
         ->selectMax("id")
         ->where("id_socio", $id_socio)
         ->where("estado", ACTIVO)
         ->first();

        $datosUltPago = $this->caja
         ->select("total_pagar")
         ->select("date_format(fecha, '%d-%m-%Y') as fecha")
         ->where("id", $datosUltPagoId["id"])
         ->first();

        $consumo_anterior_nf = 0;

        if ($datosDeuda != NULL) {
          foreach ($datosDeuda as $key) {
            $consumo_anterior_nf = $consumo_anterior_nf + intval($key["total_mes"]);
          }
        }

        $observaciones = "TIPO FACTURACIÓN, " . $datosMetros["tipo_facturacion"] . "\n";

        if ($datosUltPago != NULL) {
          $observaciones .= "ÚLTIMO PAGO REALIZADO: " . $datosUltPago["fecha"] . ", POR $" . number_format($datosUltPago["total_pagar"], 0, ",", ".") . "\n";
        }

        if ($datosObservacionesDte != NULL) {
          foreach ($datosObservacionesDte as $key) {
            $observaciones .= $key["titulo"] . ", " . $key["observacion"] . "\n";
          }
        }

        $monto_metros = intval($subtotal) - intval($cargo_fijo);
        $exento       = $tipo_dte === BOLETA_EXENTA || $tipo_dte === FACTURA_EXENTA;

        $dte = [
         'Encabezado' => [
          'IdDoc'    => [
           'TipoDTE'      => $tipo_dte,
           'FchVenc'      => $fecha_vencimiento,
           'PeriodoDesde' => $periodo_desde,
           'PeriodoHasta' => $periodo_hasta,
          ],
          'Emisor'   => [
           'RUTEmisor' => $rut_apr,
          ],
          'Receptor' => [
           'RUTRecep'    => $rut_socio,
           'RznSocRecep' => $nombre_socio,
           'GiroRecep'   => 'Particular',
           'DirRecep'    => $direccion,
           'CmnaRecep'   => $comuna,
           'CdgIntRecep' => $datosSocios["rol"],
           'Contacto'    => "N° MEDIDOR: $num_medidor, SECTOR: $sector"
          ],
         ],
         'Detalle'    => [
          [
           'IndExe'  => $exento ? 1 : FALSE,
           'NmbItem' => "Cargo Fijo",
           'QtyItem' => 1,
           'PrcItem' => $cargo_fijo
          ]
         ],
         'LibreDTE'   => [
          'extra' => [
           'dte'               => [
            'Encabezado' => [
             'IdDoc' => [
              "TermPagoGlosa" => $this->request->getPost("comments")
             ]
            ]
           ],
           'historial'         => [
            'titulo' => 'Consumo de Agua Potable',
            'datos'  => $datos_graf
           ],
           "servicios_basicos" => [
            "consumos" => [
             "unidad"            => "M3",
             "lectura_actual"    => $consumo_actual,
             "lectura_anterior"  => $consumo_anterior,
             "consumo_calculado" => $metros_,
             "consumo_facturado" => $metros_,
            ]
           ],
          ]
         ]
        ];

        $monto_nf = intval($consumo_anterior_nf) + intval($cuota_repactacion) + intval($multa) + intval($total_servicios) + intval($alcantarillado) + intval($cuota_socio) + intval($otros);

        if ($exento) {
          $vlr_pagar = intval($total_mes) + intval($consumo_anterior_nf);

          $dte["Encabezado"]["Totales"] = [
           'MontoNF'       => $monto_nf,
           'SaldoAnterior' => $consumo_anterior_nf,
           'VlrPagar'      => $vlr_pagar
          ];
        } else {
          $monto_neto = intval($monto_subsidio) > 0 ? intval($subtotal) - intval($monto_subsidio) : intval($subtotal);
          $vlr_pagar  = intval($total_mes) + intval($consumo_anterior_nf);

          $dte["Encabezado"]["Totales"] = [
           'MontoNF'       => $monto_nf,
           'SaldoAnterior' => $consumo_anterior_nf,
           'MntNeto'       => $monto_neto,
           'IVA'           => $iva,
           'MntTotal'      => intval($iva),
           'VlrPagar'      => $vlr_pagar
          ];
        }

        if (intval($monto_metros) > 0) {
          array_push($dte["Detalle"], [
           'IndExe'  => $exento ? 1 : FALSE,
           'NmbItem' => 'Consumo de Agua Potable',
           'QtyItem' => 1,
           'PrcItem' => intval($monto_metros)
          ]);
        }

        if (intval($consumo_anterior_nf) > 0) {
          array_push($dte["Detalle"], [
           'IndExe'  => 2,
           'NmbItem' => 'Consumo Anterior',
           'QtyItem' => 1,
           'PrcItem' => intval($consumo_anterior_nf)
          ]);
        }

        if (intval($cuota_repactacion) > 0) {
          $datosCuotaRepactacion = $this->repactaciones
           ->select("concat('(', rd.numero_cuota, '/', repactaciones.n_cuotas, ')') as cuotas")
           ->join("repactaciones_detalle rd", "rd.id_repactacion = repactaciones.id")
           ->where("repactaciones.id_socio", $id_socio)
           ->where("date_format(rd.fecha_pago, '%m-%Y')", $this->mes_pago($fecha_vencimiento))
           ->first();
          $cuotas                = $datosCuotaRepactacion["cuotas"];

          array_push($dte["Detalle"], [
           'IndExe'  => 2,
           'NmbItem' => 'Cuota Repactación ' . $cuotas,
           'QtyItem' => 1,
           'PrcItem' => intval($cuota_repactacion)
          ]);
        }

        if (intval($multa) > 0) {
          array_push($dte["Detalle"], [
           'IndExe'  => 2,
           'NmbItem' => 'Multa',
           'QtyItem' => 1,
           'PrcItem' => intval($multa)
          ]);
        }

        if (intval($total_servicios) > 0) {
          array_push($dte["Detalle"], [
           'IndExe'  => 2,
           'NmbItem' => 'Total Servicios',
           'QtyItem' => 1,
           'PrcItem' => intval($total_servicios)
          ]);
        }

        if (intval($monto_subsidio) > 0) {
          $dte["DscRcgGlobal"] = [
           'TpoMov'   => 'D',
           'IndExeDR' => $exento ? 1 : FALSE,
           'GlosaDR'  => "Monto del subsidio",
           'TpoValor' => '$',
           'ValorDR'  => intval($monto_subsidio)
          ];
        }

        if (intval($alcantarillado) > 0) {
          array_push($dte["Detalle"], [
           'IndExe'  => 2,
           'NmbItem' => 'Alcantarillado',
           'QtyItem' => 1,
           'PrcItem' => intval($alcantarillado)
          ]);
        }

        if (intval($cuota_socio) > 0) {
          array_push($dte["Detalle"], [
           'IndExe'  => 2,
           'NmbItem' => 'Cuota Socio',
           'QtyItem' => 1,
           'PrcItem' => intval($cuota_socio)
          ]);
        }

        if (intval($otros) > 0) {
          array_push($dte["Detalle"], [
           'IndExe'  => 2,
           'NmbItem' => 'Otros',
           'QtyItem' => 1,
           'PrcItem' => intval($otros)
          ]);
        }

        // return json_encode($dte);

        $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

        // crear DTE temporal
        $emitir = $LibreDTE->post('/dte/documentos/emitir', $dte);
        if ($emitir['status']['code'] != 200) {
          die('Error al emitir DTE temporal: ' . $emitir['body'] . "\n");
        }

        // crear DTE real
        $generar = $LibreDTE->post('/dte/documentos/generar', $emitir['body']);
        if ($generar['status']['code'] != 200) {
          die('Error al generar DTE real: ' . $generar['body'] . "\n");
        } else {
          $datosMetrosSave = [
           "folio_bolect"      => $generar['body']['folio'],
           "id_tipo_documento" => $datosSocios["tipo_documento"],
           "id"                => $folio
          ];

          if ($this->metros->save($datosMetrosSave)) {
            $fecha      = date("Y-m-d H:i:s");
            $id_usuario = $this->sesión->id_usuario_ses;
            $estado     = ASIGNA_FOLIO_BOLECT;

            $datosTraza = [
             "id_metros"  => $folio,
             "estado"     => $estado,
             "id_usuario" => $id_usuario,
             "fecha"      => $fecha
            ];

            if (!$this->metros_traza->save($datosTraza)) {
              $this->error .= "Id Metros: $folio, <br>";
              $this->error .= "Error: Falló al ingresar traza. <br><br>";
            }
          } else {
            $this->error .= "Id Metros: $folio, <br>";
            $this->error .= "Error: Falló al actualizar el folio SII. <br><br>";
          }
        }
      }

      // // guardar PDF en el disco
      // file_put_contents("../../../../pdf/boletas_sii/" . $generar['body']['folio'] . ".pdf", $generar_pdf['body']);
    }

    if ($this->error == "") {
      echo 1;
    } else {
      echo $this->error;
    }
  }

  public function observaciones() {
    $this->validar_sesion();
    $datosObservacionesDte = $this->observaciones_dte
     ->select("titulo")
     ->select("observacion")
     ->where("id_apr", $this->sesión->id_apr_ses)
     ->where("estado", 1)
     ->findAll();
    $response              = "";
    if ($datosObservacionesDte != NULL) {
      foreach ($datosObservacionesDte as $key) {
        $response .= $key["observacion"] . "\n";
      }
    }
    echo $response;
  }

  public function imprimir_dte($arr_boletas) {
    $this->validar_sesion();
    $mpdf = new \Mpdf\Mpdf();

    $url      = 'https://libredte.cl';
    $hash     = $this->sesión->hash_apr_ses;
    $rut_apr  = $this->sesión->rut_apr_ses;
    $LibreDTE = new \sasco\LibreDTE\SDK\LibreDTE($hash, $url);

    $folios = explode(",", $arr_boletas);

    foreach ($folios as $folio) {
      $datosMetros    = $this->metros->select("folio_bolect")
                                     ->select("id_tipo_documento")
                                     ->where("id", $folio)
                                     ->first();
      $folio_sii      = $datosMetros["folio_bolect"];
      $tipo_documento = $datosMetros["id_tipo_documento"];

      helper('tipo_dte');
      $tipo_dte = tipo_dte($tipo_documento);

      // obtener el PDF del DTE
      $pdf = $LibreDTE->get('/dte/dte_emitidos/pdf/' . $tipo_dte . '/' . $folio_sii . '/' . $rut_apr);
      if ($pdf['status']['code'] != 200) {
        die('Error al generar PDF del DTE: ' . $pdf['body'] . "\n");
      }

      file_put_contents($folio_sii . ".pdf", $pdf['body']);

      $pagecount = $mpdf->SetSourceFile($folio_sii . ".pdf");
      $tplId     = $mpdf->ImportPage($pagecount);
      $mpdf->AddPage();
      $mpdf->UseTemplate($tplId);

      unlink($folio_sii . ".pdf");
    }
    header("Content-type:application/pdf");
    echo $mpdf->Output();
    exit;
    return redirect()->to($mpdf->Output());
  }

  public function imprimir_aviso_cobranza($arr_boletas) {
    $this->validar_sesion();
    $mpdf = new \Mpdf\Mpdf([
                            'mode'          => 'utf-8',
                            'format'        => 'letter',
                            'margin_bottom' => 1
                           ]);

    $datosApr = $this->apr->select("nombre")
                          ->select("concat(rut, '-', dv) as rut")
                          ->select("ifnull(resto_direccion, 'Sin Registro') direccion")
                          ->select("ifnull(fono, 'Sin Registro') as fono")
                          ->where("id", $this->sesión->id_apr_ses)
                          ->first();

    $nombre_apr    = $datosApr["nombre"];
    $rut_apr       = $datosApr["rut"];
    $fono_apr      = $datosApr["fono"];
    $direccion_apr = $datosApr["direccion"];

    $folios = explode(",", $arr_boletas);

    foreach ($folios as $folio) {
      $datosMetros = $this->metros->select("id_socio")
                                  ->select("consumo_anterior")
                                  ->select("consumo_actual")
                                  ->select("metros")
                                  ->select("subtotal")
                                  ->select("total_mes")
                                  ->select("monto_subsidio")
                                  ->select("multa")
                                  ->select("date_format(fecha, '%d-%m-%Y') as fecha_emision")
                                  ->select("date_format(fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento")
                                  ->where("id", $folio)
                                  ->first();

      $id_socio          = $datosMetros["id_socio"];
      $consumo_anterior  = $datosMetros["consumo_anterior"];
      $consumo_actual    = $datosMetros["consumo_actual"];
      $consumo_metros    = $datosMetros["metros"];
      $subtotal          = $datosMetros["subtotal"];
      $monto_subsidio    = $datosMetros["monto_subsidio"];
      $multa             = $datosMetros["multa"];
      $total_mes         = $datosMetros["total_mes"];
      $fecha_emision     = $datosMetros["fecha_emision"];
      $fecha_vencimiento = $datosMetros["fecha_vencimiento"];

      $query = $this->db->query("SELECT total_mes as saldo_anterior from metros where id = (select max(id) from metros where id_socio = (select id_socio from metros where id = $folio) and estado = 1 and id < $folio)");

      $datosConsumoAnterior = $query->getRow();
      $saldo_anterior       = $datosConsumoAnterior->saldo_anterior;
      if ($saldo_anterior == "") {
        $saldo_anterior = 0;
      }

      $total_pagar = $total_mes + $saldo_anterior;

      $datosSocio = $this->socios->select("case when rut is null then 'Sin RUT registrado' else concat(rut, '-', dv) end as rut_socio")
                                 ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre_socio")
                                 ->select("concat(calle, ', ', numero, ', ', resto_direccion) as direccion_socio")
                                 ->select("rol as codigo_socio")
                                 ->select('ruta')
                                 ->where("id", $id_socio)
                                 ->first();

      $rut_socio       = $datosSocio["rut_socio"];
      $nombre_socio    = $datosSocio["nombre_socio"];
      $direccion_socio = $datosSocio["direccion_socio"];
      $codigo_socio    = $datosSocio["codigo_socio"];

      $datosArranque = $this->arranques->select("id_medidor")
                                       ->where("id_socio", $id_socio)
                                       ->first();
      $datosMedidor  = $this->medidores->select("numero")
                                       ->where("id", $datosArranque["id_medidor"])
                                       ->first();

      $numero_medidor = $datosMedidor["numero"];

      $pagecount = $mpdf->SetSourceFile("005.pdf");
      $tplId     = $mpdf->ImportPage($pagecount);
      $mpdf->AddPage();
      $mpdf->UseTemplate($tplId);
      $mpdf->WriteHTML('
					<div style="height: 2%;"></div>
					<div>
						<div style="width: 20%; float: left;">
							<img src="' . $this->sesión->id_apr_ses . '.png" width="200">
						</div>
			        </div>
					<div>
			        	<div style="font-size: 90%; width: 60%; float: left;">
			        		<br>
			        		<b>' . $nombre_apr . '</b><br>
							RUT: ' . $rut_apr . '<br>
							CAPTACIÓN, PURIFICACIÓN Y DIST. DE AGUA<br>
							' . $direccion_apr . '<br>
							FONOS: ' . $fono_apr . '
			        	</div>
			        	
			        	<div style="width: 40%; float: left;">
			        		<div style="font-size: 140%;">
			        			<b>N° ' . $folio . '<br></b><br>
			        		</div>
			        		<div style="font-size: 90%;">
				        		BOLETA DE VENTAS Y SERVICIOS<br>
								NO AFECTAS O EXENTAS DE IVA<br>
								OFICIO N° 2.413 DEL 30 - 08 - 96 DEL S.I.I.<br>
								RESOLUCIÓN N° 78 03-04-1998
							</div>
			        	</div>
			        </div>
			        <br><br>
					<div>
			        	<div style="width: 60%; float: left; margin-left: 3%;">
			        		' . $rut_socio . '<br>
			        		' . $nombre_socio . '<br>
							' . $direccion_socio . '<br>
							' . $codigo_socio . '<br>
							' . $numero_medidor . '
			        	</div>
			        </div>
			        <br><br><br><br><br>
					<div>
			        	<div style="width: 28%; float: left; margin-left: 3%;">
			        		' . $consumo_anterior . ' M<sup>3</sup><br>
			        	</div>
			        	<div style="width: 29%; float: left;">
			        		' . $consumo_actual . ' M<sup>3</sup><br>
			        	</div>
			        	<div style="width: 40%; float: left;">
			        		' . $consumo_metros . ' M<sup>3</sup><br>
			        	</div>
			        </div>
			        <div style="height: 6.5%;"></div>
					<div>
			        	<div style="width: 20%; float: left; margin-left: 50%;">
			        		$ ' . number_format($subtotal, 0, ",", ".") . '<br><br><br>
			        		$ ' . number_format($saldo_anterior, 0, ",", ".") . '<br><br><br>
			        		$ ' . number_format($monto_subsidio, 0, ",", ".") . '
			        	</div>
			        	<div style="width: 20%; float: left; margin-left: 10%;">
			        		<br><br><br>
			        		$ ' . number_format($multa, 0, ",", ".") . '
			        	</div>
			        </div>
			        <div style="height: 7%;"></div>
					<div>
			        	<div style="width: 35%; float: left; margin-left: 10%;">
			        		' . $fecha_emision . '
			        	</div>
			        	<div style="width: 35%; float: left;">
			        		' . $fecha_vencimiento . '
			        	</div>
			        	<div style="width: 15%; float: left;">
			        		$ ' . number_format($total_pagar, 0, ",", ".") . '
			        	</div>
			        </div>
				');
    }

    $verificar_dispositivo = $this->verificar_dispositivo();

    if ($verificar_dispositivo == "mobil" or $verificar_dispositivo == "tablet") {
      return $mpdf->Output("Aviso de Cobranza.pdf", "D");
    } else {
      return redirect()->to($mpdf->Output("Aviso de Cobranza.pdf", "I"));
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