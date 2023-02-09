<?php

namespace App\Controllers\Informes;

use App\ThirdParty\Touchef;
use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Finanzas\Md_proveedores;
use App\Models\Finanzas\Md_facturas_municipalidad;

class Ctrl_informe_municipal extends BaseController {

  protected $metros;
  protected $proveedores;
  protected $facturas_municipalidad;
  protected $sesión;
  protected $db;
  protected $apr;

  public function __construct() {
    $this->metros                 = new Md_metros();
    $this->proveedores            = new Md_proveedores();
    $this->facturas_municipalidad = new Md_facturas_municipalidad();
    $this->sesión                 = session();
    $this->db                     = \Config\Database::connect();
    $this->apr                    = new Md_apr();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_municipal($mes_consumo) {
    $this->validar_sesion();
    echo $this->metros->datatable_informe_municipal($this->db, $this->sesión->id_apr_ses, $mes_consumo);
  }

  public function emitir_factura() {
    $this->validar_sesion();

    $validaDatosFactura = [
     "id_proveedor"  => "required|numeric",
     "sub50"         => "required|numeric",
     "sub100"        => "required|numeric",
     "mes_facturado" => "required"
    ];

    if ($this->request->getMethod() == "post" && $this->validate($validaDatosFactura)) {
      $url     = 'https://libredte.cl';
      $hash    = $this->sesión->hash_apr_ses;
      $rut_apr = $this->sesión->rut_apr_ses . "-" . $this->sesión->dv_apr_ses;

      define("FACTURA_EXENTA", 34);
      define("ACTIVO", 1);
      define("ERROR", 0);
      define("OK", 1);

      $id_proveedor  = $this->request->getPost("id_proveedor");
      $sub50         = $this->request->getPost("sub50");
      $sub100        = $this->request->getPost("sub100");
      $mes_facturado = $this->request->getPost("mes_facturado");

      $fecha      = date("Y-m-d H:i:s");
      $id_usuario = $this->sesión->id_usuario_ses;
      $id_apr     = $this->sesión->id_apr_ses;

      $datosFacturaMuni = $this->facturas_municipalidad
       ->select("count(*) as filas")
       ->where("date_format(mes_facturado, '%m-%Y')", $mes_facturado)
       ->where("id_apr", $id_apr)
       ->where("estado", ACTIVO)
       ->first();

      if ($datosFacturaMuni["filas"] > 0) {
        $respuesta = [
         "estado"  => ERROR,
         "mensaje" => "El mes consultado, ya se encuentra facturado"
        ];

        echo json_encode($respuesta);
        exit();
      }

      $datosMuni = $this->proveedores
       ->select("concat(proveedores.rut, '-', proveedores.dv) as rut")
       ->select("proveedores.razon_social")
       ->select("proveedores.giro")
       ->select("proveedores.direccion")
       ->select("c.nombre as comuna")
       ->join("comunas c", "proveedores.id_comuna = c.id")
       ->where("proveedores.id", $id_proveedor)
       ->first();

      $details     = [
       [
        'tax'      => 1,
        'name'     => 'Subsidios 100%',
        'quantity' => 1,
        'price'    => $sub100,
       ],
       [
        'tax'      => 1,
        'name'     => 'Subsidios 50%',
        'quantity' => 1,
        'price'    => $sub50,
       ]
      ];
      $client      = [
       'rut'      => $datosMuni["rut"],
       'name'     => $datosMuni["razon_social"],
       'address'  => $datosMuni["direccion"],
       'county'   => $datosMuni["comuna"],
       'activity' => $datosMuni["giro"],
      ];
      $credentials = $this->apr->touchef_credentials($this->db, $this->sesión->id_apr_ses);
      $touchef     = new Touchef($rut_apr, $credentials['touchef_token'], $credentials['touchef_password']);
      $response    = $touchef->create_document(34, date_format(date_create("01-" . $mes_facturado), 'Y-m-d'), $client, $details);

      if (!isset($response->records)) {
        $respuesta = [
         "estado"  => ERROR,
         "mensaje" => "Error al emitir DTE: " . json_encode($response)
        ];

        echo json_encode($respuesta);
        exit();
      } else {
        $datosFacturaMuniSave = [
         "mes_facturado" => date_format(date_create("01-" . $mes_facturado), 'Y-m-d'),
         "id_usuario"    => $id_usuario,
         "fecha"         => $fecha,
         "id_apr"        => $id_apr,
         "folio_factura" => $response->records->number
        ];

        $this->facturas_municipalidad->save($datosFacturaMuniSave);
        @mkdir(FCPATH . '/writable/xml');
        @mkdir(FCPATH . '/writable/xml/' . $rut_apr);
        file_put_contents(FCPATH . '/writable/xml/' . $rut_apr . '/T' . $response->records->documents_type_id . 'F' . $response->records->number . '.xml', base64_decode($response->records->xml_encoded));
        $respuesta = [
         "estado"  => OK,
         "mensaje" => "Factura emitida con éxito",
         "folio"   => $response->records->number
        ];

        echo json_encode($respuesta);
      }
    }
  }

  public function imprimir_factura($folio_sii) {
    $this->validar_sesion();

    define("FACTURA_EXENTA", 34);

    $credentials = $this->apr->touchef_credentials($this->db, $this->sesión->id_apr_ses);
    $touchef     = new Touchef($this->sesión->rut_apr_ses . $this->sesión->dv_apr_ses, $credentials['touchef_token'], $credentials['touchef_password']);
    $logo        = base64_encode(file_get_contents(FCPATH . "/" . $this->sesión->id_apr_ses . ".png"));
    // obtener el PDF del DTE
    $pdf = $touchef->sale(34, $folio_sii);
    if (!isset($pdf->records)) {
      die('Error al generar PDF del DTE: ' . json_encode($pdf) . "\n");
    }
    header("Content-type:application/pdf");
    echo base64_decode($pdf->records->pdf_encoded);
    exit;
  }
}