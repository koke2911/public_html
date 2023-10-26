<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_socios;
use App\Models\Formularios\Md_socios_traza;

class Ctrl_socios extends BaseController {

  protected $socios;
  protected $socios_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->socios       = new Md_socios();
    $this->socios_traza = new Md_socios_traza();
    $this->sesión       = session();
    $this->db           = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_socios() {
    $this->validar_sesion();
    echo $this->socios->datatable_socios($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_socio() {
    $this->validar_sesion();
    define("CREAR_SOCIO", 1);
    define("MODIFICAR_SOCIO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_socio         = $this->request->getPost("id_socio");
    $rut              = $this->request->getPost("rut");
    $rol              = $this->request->getPost("rol");
    $nombres          = $this->request->getPost("nombres");
    $ape_pat          = $this->request->getPost("ape_pat");
    $ape_mat          = $this->request->getPost("ape_mat");
    $fecha_entrada    = $this->request->getPost("fecha_entrada");
    $fecha_nacimiento = $this->request->getPost("fecha_nacimiento");
    $id_sexo          = $this->request->getPost("id_sexo");
    $id_region        = $this->request->getPost("id_region");
    $id_provincia     = $this->request->getPost("id_provincia");
    $id_comuna        = $this->request->getPost("id_comuna");
    $calle            = $this->request->getPost("calle");
    $numero           = $this->request->getPost("numero");
    $resto_direccion  = $this->request->getPost("resto_direccion");
    $ruta             = $this->request->getPost("ruta");
    $email             = $this->request->getPost("email");

    if ($id_comuna == "") {
      $id_comuna = NULL;
    }
    if ($fecha_nacimiento == "") {
      $fecha_nacimiento = NULL;
    } else {
      $fecha_nacimiento = date_format(date_create($fecha_nacimiento), 'Y-m-d');
    }

    $rut_completo = explode("-", $rut);
    $rut          = $rut_completo[0];
    $dv           = $rut_completo[1];

    if ($this->socios->existe_socio($rut, $rol, $this->sesión->id_apr_ses) and $id_socio == "") {
      echo "Socio ya existe en el sistema";
      exit();
    }

    $datosSocio = [
     "rut"              => $rut,
     "dv"               => $dv,
     "rol"              => $rol,
     "nombres"          => $nombres,
     "ape_pat"          => $ape_pat,
     "ape_mat"          => $ape_mat,
     "fecha_entrada"    => date_format(date_create($fecha_entrada), 'Y-m-d'),
     "fecha_nacimiento" => $fecha_nacimiento,
     "id_sexo"          => $id_sexo,
     "calle"            => $calle,
     "numero"           => $numero,
     "resto_direccion"  => $resto_direccion,
     "ruta"             => $ruta,
     "id_comuna"        => $id_comuna,
     "id_usuario"       => $id_usuario,
     "fecha"            => $fecha,
     "email"            => $email,
     "id_apr"           => $id_apr
     
    ];

    if ($id_socio != "") {
      $estado_traza     = MODIFICAR_SOCIO;
      $datosSocio["id"] = $id_socio;
    } else {
      $estado_traza         = CREAR_SOCIO;
      $datosSocio["estado"] = $estado;
    }

    if ($this->socios->save($datosSocio)) {
      echo OK;

      if ($id_socio == "") {
        $obtener_id = $this->socios->select("max(id) as id_socio")
                                   ->first();
        $id_socio   = $obtener_id["id_socio"];
      }

      $this->guardar_traza($id_socio, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del socio";
    }
  }

  public function guardar_traza($id_socio, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_socio"    => $id_socio,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->socios_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_socio_traza() {
    $this->validar_sesion();
    echo view("Formularios/socio_traza");
  }

  public function datatable_socio_traza($id_socio) {
    $this->validar_sesion();
    echo $this->socios_traza->datatable_socio_traza($this->db, $id_socio);
  }

  public function cambiar_estado_socio() {
    $this->validar_sesion();
    $id_socio    = $this->request->getPost("id_socio");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    define("OK", 1);

    if ($estado == "eliminar") {
      $estado_traza = 3;
      $estado       = 0;
    } else {
      $estado_traza = 4;
      $estado       = 1;
    }

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosSocio = [
     "id"         => $id_socio,
     "estado"     => $estado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    if ($this->socios->save($datosSocio)) {
      echo OK;
      $this->guardar_traza($id_socio, $estado_traza, $observacion);
    } else {
      echo "Error al guardar los datos del socio";
    }
  }

  public function v_socio_reciclar() {
    $this->validar_sesion();
    echo view("Formularios/socio_reciclar");
  }

  public function datatable_socios_reciclar() {
    $this->validar_sesion();
    echo $this->socios->datatable_socios_reciclar($this->db, $this->sesión->id_apr_ses);
  }

  public function llenar_cmb_socio() {
    $this->validar_sesion();
    $datos_regiones = $this->socios->select("id")
                                   ->select("rut")
                                   ->select("dv")
                                   ->select("nombres")
                                   ->select("ape_pat")
                                   ->select("ape_mat")
                                   ->findAll();

    $data = [];

    foreach ($datos_regiones as $key) {
      $data[] = $key;
    }

    // $salida = array("data" => $data);
    echo json_encode($data);
  }


   public function imprime_certificado($id_socio){
      $this->validar_sesion();
      $apr=$this->sesión->apr_ses;
      $apr_rut=$this->sesión->rut_apr_ses;
      $apr_dv=$this->sesión->dv_apr_ses;
      // echo $id_socio;
      $mpdf = new \Mpdf\Mpdf([
                            'mode'          => 'utf-8',
                            'format'        => 'letter',
                            'margin_bottom' => 1
                           ]);

      $pagecount = $mpdf->SetSourceFile("certificadoAPRServicio.pdf");

     


       $dia      = date("d");
       $mes      = date("m");
       $ano      = date("Y");

      $tplId = $mpdf->ImportPage(1);
      $mpdf->AddPage();
      $mpdf->UseTemplate($tplId);

      $mpdf->SetXY(160, 5);

      $html = ' <img src="' . base_url().'/'.$this->sesión->id_apr_ses . '.png" width="150">'; 
      $mpdf->WriteHTML($html);

      $x = 47;
      $y = 43;
      $mpdf->SetXY($x, $y);

      $mpdf->Cell(0, 0, $dia, 0, 1, 'L');
      $mpdf->SetX(56);
      $mpdf->Cell(0, 0, $mes, 0, 1, 'L');
      $mpdf->SetX(68);
      $mpdf->Cell(0, 0, $ano, 0, 1, 'L');
      $mpdf->SetX(120);
      $mpdf->Cell(0, 0, 'Id Socio N° : '.$id_socio, 0, 1, 'L');
      $mpdf->SetXY(25,63);
      $mpdf->Cell(0, 0, $apr, 0, 1, 'L');
      $mpdf->SetX(150);
      $mpdf->Cell(0, 0, $apr_rut.'-'.$apr_dv, 0, 1, 'L');

      $consulta = "SELECT nombres ,ape_pat, ape_mat,rut,dv from socios s where s.id=$id_socio";

      $query = $this->db->query($consulta);
      $data  = $query->getResultArray();

      $nombres=$data[0]['nombres'];
      $ape_pat=$data[0]['ape_pat'];
      $ape_mat=$data[0]['ape_mat'];
      $rut=$data[0]['rut'];
      $dv=$data[0]['dv'];

      $mpdf->SetXY(63,70);
      $mpdf->Cell(0, 0, $nombres.' '.$ape_pat.' '.$ape_mat, 0, 1, 'L');
      $mpdf->SetXY(50,77);
      $mpdf->Cell(0, 0, $rut.'-'.$dv, 0, 1, 'L');



            // print_r($data);
            // exit();

      return $mpdf->Output("Certificado Dotacion " . $id_socio . ".pdf","D");

       // header("Content-type:application/pdf");
   
       //   return redirect()->to($mpdf->Output());


    }
}
