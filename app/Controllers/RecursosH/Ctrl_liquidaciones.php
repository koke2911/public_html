<?php

namespace App\Controllers\RecursosH;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_funcionarios;
use App\Models\RecursosH\Md_liquidaciones;

class Ctrl_liquidaciones extends BaseController {

  protected $funcionarios;
  protected $sesión;
  protected $db;
  protected $liquidaciones;

  public function __construct() {
    $this->funcionarios       = new Md_funcionarios();
    $this->sesión             = session();
    $this->db                 = \Config\Database::connect();
    $this->liquidaciones      = new Md_liquidaciones();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function buscar_datos_funcionario($rut){
     $this->validar_sesion();

     $consulta = "SELECT 
              s.id as id_funcionario,
              concat(s.rut, '-', s.dv) as rut,
              s.nombres,
              s.ape_pat,
              s.ape_mat,
                concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_completo,
              s.id_sexo,              
              s.id_comuna,
              s.calle,
              s.numero,
              s.resto_direccion,
              date_format(s.fecha, '%d-%m-%Y %H:%i:%s') as fecha,
              s.prevision,
              s.prev_porcentaje,
              s.afp,
              s.afp_porcentaje,
              s.sueldo_bruto,
              date_format(s.fecha_contrato, '%d-%m-%Y') as fecha_contrato,
              s.jornada
            from 
              funcionarios s              
            where
              s.rut = 17525457 and
              s.estado = 1";

    $query        = $this->db->query($consulta);
    $funcionarios = $query->getResultArray();

    // print_r($funcionarios);
    // exit();

    foreach ($funcionarios as $key) {
      $row = [
       "id_funcionario"  => $key["id_funcionario"],
       "rut"             => $key["rut"],
       "nombres"         => $key["nombres"],
       "ape_pat"         => $key["ape_pat"],
       "ape_mat"         => $key["ape_mat"],
       "nombre_completo" => $key["nombre_completo"],
       "id_sexo"         => $key["id_sexo"],
       "id_region"       => $key["id_region"],
       "id_provincia"    => $key["id_provincia"],
       "id_comuna"       => $key["id_comuna"],
       "comuna"          => $key["comuna"],
       "calle"           => $key["calle"],
       "numero"          => $key["numero"],
       "resto_direccion" => $key["resto_direccion"],
       "usuario"         => $key["usuario"],
       "fecha"           => $key["fecha"],
        "prevision"      =>$key["prevision"],
        "prev_porcentaje" =>$key["prev_porcentaje"],
        "afp"            =>$key["afp"],
        "afp_porcentaje" =>$key["afp_porcentaje"],
        "sueldo_bruto"   =>$key["sueldo_bruto"],
        "fecha_contrato" =>$key["fecha_contrato"],
        "jornada"        =>$key["jornada"]
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

  public function guardar_liquidacion(){
    $this->validar_sesion();
    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
        
    $rut=$this->request->getPost("rut");
    $mes=$this->request->getPost("mes");
    $valor_uf=$this->request->getPost("valor_uf");
    $dias_trabajados=$this->request->getPost("dias_trabajados");
    $sueldo_bruto=$this->request->getPost("sueldo_bruto");
    $afp=$this->request->getPost("afp");
    $obligatorio=$this->request->getPost("obligatorio");
    $pactada=$this->request->getPost("pactada");
    $diferencia_isapre=$this->request->getPost("diferencia_isapre");
    $afc=$this->request->getPost("afc");
    $otros=$this->request->getPost("otros");
    $total_prevision=$this->request->getPost("total_prevision");
    $base_tributable=$this->request->getPost("base_tributable");
    $cargas=$this->request->getPost("cargas");
    $a_pagar=$this->request->getPost("a_pagar");

    if ($this->liquidaciones->existe_liquidacion($rut,$mes, $id_apr)) {
      echo "El funcionario ya posee una liquidacion para el mes seleciconado";
      exit();
    }

     $datosLiquidacion = [
      "rut" =>  $rut,
      "mes" =>date_format(date_create('01-'.$mes), 'Y-m-d'),
      "valor_uf" => $valor_uf,
      "dias_trabajados" =>$dias_trabajados,
      "sueldo_bruto" => $sueldo_bruto,
      "afp" =>$afp,
      "obligatorio" =>$obligatorio,
      "pactada" =>$pactada,
      "diferencia_isapre" =>$diferencia_isapre,
      "afc" =>$afc,
      "otros" =>$otros,
      "total_prevision" =>$total_prevision,
      "base_tributable" =>$base_tributable,
      "cargas" => $cargas,
      "a_pagar" =>$a_pagar,
      "id_apr" =>$id_apr,
      "fecha_genera" =>date_format(date_create($fecha), 'Y-m-d'),
      "usuario_genera" =>$id_usuario
    ];

     if ($this->liquidaciones->save($datosLiquidacion)) {
        echo 1;
     }else{
        echo "Error al generar la liquidacion";
     }

  }

  public function datatable_liquidaciones() {
    $this->validar_sesion();
    echo $this->liquidaciones->datatable_liquidaciones($this->db, $this->sesión->id_apr_ses);
  }

  public function imprime_liquidacion($id){
      $this->validar_sesion();
      $apr=$this->sesión->apr_ses;
      $id_apr=$this->sesión->id_apr_ses;
      $apr_rut=$this->sesión->rut_apr_ses;
      $apr_dv=$this->sesión->dv_apr_ses;
      // echo $id_socio;
      $mpdf = new \Mpdf\Mpdf([
                            'mode'          => 'utf-8',
                            'format'        => 'letter',
                            'margin_bottom' => 1
                           ]);

      $pagecount = $mpdf->SetSourceFile("base_liquidacion.pdf");

      $tplId = $mpdf->ImportPage(1);
      $mpdf->AddPage();
      $mpdf->UseTemplate($tplId);

      $mpdf->SetXY(160, 5);

      $html = ' <img src="' . base_url().'/'.$this->sesión->id_apr_ses . '.png" width="150">'; 
      $mpdf->WriteHTML($html);

      $mpdf->SetXY(5, 20);

      $mpdf->Cell(0, 0, $apr, 0, 1, 'C');
      $mpdf->SetXY(5, 25);
      $mpdf->Cell(0, 0, 'RUT: '.$apr_rut.'-'.$apr_dv, 0, 1, 'C');

        $consulta = "SELECT concat(a.calle,' ',a.numero,' ',a.resto_direccion) as direccion,
                       c.nombre as comuna,
                       a.fono from apr a
                       inner join comunas c on c.id=a.id_comuna 
                       where a.id=$id_apr";

      $query = $this->db->query($consulta);
      $data  = $query->getResultArray();

      $direccion=$data[0]['direccion'];
      $comuna=$data[0]['comuna'];
      $fono=$data[0]['fono'];

      $mpdf->SetXY(5, 30);
      $mpdf->Cell(0, 0, $direccion, 0, 1, 'C');
      $mpdf->SetXY(5, 35);
      $mpdf->Cell(0, 0, $fono, 0, 1, 'C');

      $sql="SELECT
                l.id,
                date_format(f.fecha_contrato ,'%d-%m-%Y') as fecha_contrato,
                concat(l.rut,'-',f.dv) as rut,
                concat(f.nombres,' ',f.APE_PAT,' ',F.APE_MAT) as funcionario,
                 CASE
                WHEN MONTH(mes) = 1 THEN 'enero'
                WHEN MONTH(mes) = 2 THEN 'febrero'
                WHEN MONTH(mes) = 3 THEN 'marzo'
                WHEN MONTH(mes) = 4 THEN 'abril'
                WHEN MONTH(mes) = 5 THEN 'mayo'
                WHEN MONTH(mes) = 6 THEN 'junio'
                WHEN MONTH(mes) = 7 THEN 'julio'
                WHEN MONTH(mes) = 8 THEN 'agosto'
                WHEN MONTH(mes) = 9 THEN 'septiembre'
                WHEN MONTH(mes) = 10 THEN 'octubre'
                WHEN MONTH(mes) = 11 THEN 'noviembre'
                WHEN MONTH(mes) = 12 THEN 'diciembre'
            END as mes,
                date_format(mes,'%Y') as ano,
                valor_uf,
                dias_trabajados,
                f.sueldo_bruto,
                l.afp,
                obligatorio,
                pactada,
                diferencia_isapre,
                afc,
                otros,
                total_prevision,
                base_tributable,
                cargas,
                a_pagar,
                l.sueldo_bruto as sueldo,
                l.id_apr,
                fecha_genera,
                concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usuario_registra,
                f.afp as afp_funcionario,
                f.prevision
                from liquidaciones l
                inner join funcionarios f on l.rut=f.rut
                inner join usuarios u on u.id=l.usuario_genera
                and l.id_apr=$id_apr  and f.estado=1 and l.id=$id";
      $query = $this->db->query($sql);
      $data  = $query->getResultArray();

      // print_r($data);
      // exit();
      $id=$data[0]["id"];
      $fecha_contrato=$data[0]["fecha_contrato"];
      $rut=$data[0]["rut"];
      $funcionario=$data[0]["funcionario"];
      $mes=$data[0]["mes"];
      $ano=$data[0]["ano"];
      $valor_uf=$data[0]["valor_uf"];
      $dias_trabajados=$data[0]["dias_trabajados"];
      $sueldo_bruto=$data[0]["sueldo_bruto"];
      $afp=$data[0]["afp"];
      $obligatorio=$data[0]["obligatorio"];
      $pactada=$data[0]["pactada"];
      $diferencia_isapre=$data[0]["diferencia_isapre"];
      $afc=$data[0]["afc"];
      $otros=$data[0]["otros"];
      $total_prevision=$data[0]["total_prevision"];
      $base_tributable=$data[0]["base_tributable"];
      $cargas=$data[0]["cargas"];
      $a_pagar=$data[0]["a_pagar"];
      $sueldo=$data[0]["sueldo"];
      $afp_funcionario=$data[0]["afp_funcionario"];
      $prevision=$data[0]["prevision"];


      $mpdf->SetXY(40, 61);
      $mpdf->SetFont('Arial', '', 9);
      $mpdf->Cell(0, 0, $rut, 0, 1, 'L');
      $mpdf->SetXY(79, 61);
      $mpdf->Cell(0, 0, $funcionario, 0, 1, 'L');
      $mpdf->SetXY(180, 61);
      $mpdf->Cell(0, 0, $fecha_contrato, 0, 1, 'L');

      $mpdf->SetXY(42, 66.5);
      $mpdf->Cell(0, 0, $ano, 0, 1, 'L');
      $mpdf->SetXY(75, 66.5);
      $mpdf->Cell(0, 0, $mes, 0, 1, 'L');

      $mpdf->SetXY(130, 66.5);
      $mpdf->Cell(0, 0, $sueldo_bruto, 0, 1, 'L');

      $mpdf->SetXY(170, 66.5);
      $mpdf->Cell(0, 0, $valor_uf, 0, 1, 'L');

      $mpdf->SetXY(60, 83);
      $mpdf->Cell(0, 0, $dias_trabajados, 0, 1, 'L');
      $mpdf->SetXY(60, 88.5);
      $mpdf->Cell(0, 0, $sueldo, 0, 1, 'L');


      $mpdf->SetXY(180, 71.5);
      $mpdf->Cell(0, 0, $afp_funcionario, 0, 1, 'L');

      $mpdf->SetXY(180, 77.5);
      $mpdf->Cell(0, 0, $sueldo, 0, 1, 'L');

      $mpdf->SetXY(180, 82.5);
      $mpdf->Cell(0, 0, $afp, 0, 1, 'L');

       $mpdf->SetXY(180, 88);
      $mpdf->Cell(0, 0, $prevision, 0, 1, 'L');

       $mpdf->SetXY(180, 93);
      $mpdf->Cell(0, 0, $obligatorio, 0, 1, 'L');

       $mpdf->SetXY(180, 98);
      $mpdf->Cell(0, 0, $pactada, 0, 1, 'L');

       $mpdf->SetXY(180, 103);
      $mpdf->Cell(0, 0, $diferencia_isapre, 0, 1, 'L');


      $mpdf->SetXY(180, 110);
      $mpdf->Cell(0, 0, $sueldo, 0, 1, 'L');

      $mpdf->SetXY(180, 115);
      $mpdf->Cell(0, 0, $afc, 0, 1, 'L');

      $mpdf->SetXY(180, 120);
      $mpdf->Cell(0, 0, $total_prevision, 0, 1, 'L');

      $mpdf->SetXY(180, 145);
      $mpdf->Cell(0, 0, $otros, 0, 1, 'L');

      $mpdf->SetXY(180, 150);
      $mpdf->Cell(0, 0, $base_tributable, 0, 1, 'L');

      $mpdf->SetXY(80, 145);
      $mpdf->Cell(0, 0, $sueldo, 0, 1, 'L');

      $mpdf->SetXY(80, 150);
      $mpdf->Cell(0, 0, $cargas, 0, 1, 'L');

      $mpdf->SetXY(80, 155.5);
      $mpdf->Cell(0, 0, ($sueldo+$cargas), 0, 1, 'L');


      $mpdf->SetXY(180, 181);
      $mpdf->Cell(0, 0, ($total_prevision+$otros), 0, 1, 'L');
      $mpdf->SetXY(180, 186);
      $mpdf->Cell(0, 0, ($a_pagar), 0, 1, 'L');

      $mpdf->SetXY(180, 192);
      $mpdf->Cell(0, 0, ($a_pagar), 0, 1, 'L');

      // return $mpdf->Output("Certificado Dotacion " . $id_socio . ".pdf","D");
         header("Content-type:application/pdf");
   
         return redirect()->to($mpdf->Output());

    }


}

?>
