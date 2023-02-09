<?php

namespace App\Controllers\Finanzas;

use Config\Database;
use App\ThirdParty\Touchef;
use Freshwork\ChileanBundle\Rut;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Formularios\Md_socios;
use App\Models\Configuracion\Md_comunas;

class Ctrl_registro_ventas extends BaseController {

  protected $ingresos;
  protected $ingresos_traza;
  protected $proveedores;
  protected $funcionarios;
  protected $socios;
  protected $motivos;
  protected $tipos_ingreso;
  protected $sesión;
  protected $db;
  protected $touchef;
  protected $apr;

  public function __construct() {
    $this->apr     = new Md_apr;
    $this->socios  = new Md_socios();
    $this->comunas = new Md_comunas();
    $this->sesión  = session();
    $this->db      = Database::connect();
    $rut_apr       = $this->sesión->rut_apr_ses . "-" . $this->sesión->dv_apr_ses;
    $credentials   = $this->apr->touchef_credentials($this->db, $this->sesión->id_apr_ses);
    $this->touchef = new Touchef($rut_apr, $credentials['touchef_token'], $credentials['touchef_password']);
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function crear() {
    $this->validar_sesion();
    $rut         = Rut::parse($this->request->getPost('user_id'));
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
     ->select('socios.email as email')
     ->join("arranques a", "a.id_socio = socios.id")
     ->join("sectores s", "a.id_sector = s.id")
     ->join("medidores m", "a.id_medidor = m.id")
     ->join("apr_cargo_fijo cf", "cf.id_apr = socios.id_apr and cf.id_diametro = m.id_diametro")
     ->where("socios.rut", $rut->number())
     ->where('socios.dv', $rut->vn())
     ->first();
    if (!isset($datosSocios['rut_socio'])) {
      die('El socio no existe');
    }
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
    $client  = [
     'rut'      => $rut_socio,
     'name'     => $nombre_socio,
     'activity' => 'Particular',
     'address'  => $direccion,
     'county'   => $comuna,
     'id'       => $datosSocios["rol"],
    ];
    $details = [];
    foreach ($this->request->getPost('quantity') as $key => $quantity) {
      if ($this->request->getPost('tax')[$key] != 1) {
        $details[] = [
         'quantity' => $quantity,
         'tax'      => (int) $this->request->getPost('tax')[$key],
         'price'    => $this->request->getPost('price')[$key],
         'name'     => $this->request->getPost('name')[$key],
        ];
      } else {
        $details[] = [
         'quantity' => $quantity,
         'price'    => $this->request->getPost('price')[$key],
         'name'     => $this->request->getPost('name')[$key],
        ];
      }
    }
    $response = $this->touchef->create_document(base64_encode(file_get_contents(FCPATH . "/" . $this->sesión->id_apr_ses . ".png")), $this->request->getPost('documents_type_id'), date('Y-m-d'), $client, $details, NULL, NULL, NULL, [
     'comments'  => $this->request->getPost('comments'),
     'apr_email' => $datosSocios['email']
    ]);
    if (isset($response->records)) {
      echo json_encode($response->records);
    } else {
      die('Error al generar el DTE');
    }
  }

  public function imprimir_dte($tipo, $folio) {
    $this->validar_sesion();
    $credentials = $this->apr->touchef_credentials($this->db, $this->sesión->id_apr_ses);
    $touchef     = new Touchef($this->sesión->rut_apr_ses . $this->sesión->dv_apr_ses, $credentials['touchef_token'], $credentials['touchef_password']);
    $logo        = base64_encode(file_get_contents(FCPATH . "/" . $this->sesión->id_apr_ses . ".png"));

    // obtener el PDF del DTE
    $pdf = $touchef->pdf($folio, $tipo, $logo);
    if (!isset($pdf->records)) {
      die('Error al generar PDF del DTE: ' . $pdf['body'] . "\n");
    }

    header("Content-type:application/pdf");
    echo base64_decode($pdf->records);
    exit;
  }
}