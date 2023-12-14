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

     // echo $rut;

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
              s.jornada,
              s.vacaciones,
              ifnull(s.vacaciones_disponibles,s.vacaciones) as vacaciones_disponibles,
              a.horas_extras
            from 
              funcionarios s   
              inner join apr a on s.id_apr=a.id           
            where
              s.rut = $rut and
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
        "jornada"        =>$key["jornada"],
        "vacaciones"     =>$key["vacaciones"],
        "vacaciones_disponibles" =>$key["vacaciones_disponibles"],
        "horas_extras" =>$key["horas_extras"]
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

  public function anula_liquidacion($id){
    
      $datosLiquidacion=[
         "id" => $id,
         "estado"=>0
      ];

      if ($this->liquidaciones->save($datosLiquidacion)) {
        echo 1;
     }else{
        echo "Error al anular liquidacion";
     }


  }

  public function guardar_liquidacion(){
    $this->validar_sesion();
    $fecha_genera      = date("Y-m-d H:i:s");
    $usuario_genera = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
        
    $rut  =$this->request->getPost("rut");
    $id_funcionario  =$this->request->getPost("id_funcionario");
    $mes  =$this->request->getPost("mes");
    $valor_uf  =$this->request->getPost("valor_uf");
    $dias_trabajados  =$this->request->getPost("dias_trabajados");
    $sueldo_base  =$this->request->getPost("sueldo_base");
    $h_extras_c  =$this->request->getPost("h_extras_c");
    $h_extras_v  =$this->request->getPost("h_extras_v");
    $gratif_por  =$this->request->getPost("gratif_por");
    $gratif_val  =$this->request->getPost("gratif_val");
    $bono_respon  =$this->request->getPost("bono_respon");
    $total_imponible  =$this->request->getPost("total_imponible");
    $bono_colacion  =$this->request->getPost("bono_colacion");
    $asig_familiar  =$this->request->getPost("asig_familiar");
    $bono_movil  =$this->request->getPost("bono_movil");
    $viatico  =$this->request->getPost("viatico");
    $feriado_proporcional  =$this->request->getPost("feriado_proporcional");
    $total_otros_haberes  =$this->request->getPost("total_otros_haberes");
    $afp  =$this->request->getPost("afp");
    $obligatorio  =$this->request->getPost("obligatorio");
    $pactada  =$this->request->getPost("pactada");
    $diferencia_isa  =$this->request->getPost("diferencia_isa");
    $afc_tra_por  =$this->request->getPost("afc_tra_por");
    $afc_tra_v  =$this->request->getPost("afc_tra_v");
    $afc_empl_por  =$this->request->getPost("afc_empl_por");
    $afc_empl_v  =$this->request->getPost("afc_empl_v");
    $sis_por  =$this->request->getPost("sis_por");
    $sis_v  =$this->request->getPost("sis_v");
    $acc_trab_por  =$this->request->getPost("acc_trab_por");
    $acc_trab_v  =$this->request->getPost("acc_trab_v");
    $aporte_empl  =$this->request->getPost("aporte_empl");
    $otros  =$this->request->getPost("otros");
    $total_prevision  =$this->request->getPost("total_prevision");
    $base_tributable  =$this->request->getPost("base_tributable");
    $apagar  =$this->request->getPost("apagar");
    $fecha_genera  =$this->request->getPost("fecha_genera");

    if ($this->liquidaciones->existe_liquidacion($rut,$mes, $id_apr)) {
      echo "El funcionario ya posee una liquidacion para el mes seleciconado";
      exit();
    }

   

    $datosLiquidacion=[
    "rut" => $rut,
    "id_funcionario"  => $id_funcionario,
    "mes" =>date_format(date_create('01-'.$mes), 'Y-m-d'),
    "valor_uf"  => $valor_uf,
    "dias_trabajados" => $dias_trabajados,
    "sueldo_base" => $sueldo_base,
    "h_extras_c"  => $h_extras_c,
    "h_extras_v"  => $h_extras_v,
    "gratif_por"  => $gratif_por,
    "gratif_val"  => $gratif_val,
    "bono_respon" => $bono_respon,
    "total_imponible" => $total_imponible,
    "bono_colacion" => $bono_colacion,
    "asig_familiar" => $asig_familiar,
    "bono_movil"  => $bono_movil,
    "viatico" => $viatico,
    "feriado_proporcional"  => $feriado_proporcional,
    "total_otros_haberes" => $total_otros_haberes,
    "afp" => $afp,
    "obligatorio" => $obligatorio,
    "pactada" => $pactada,
    "diferencia_isa"  => $diferencia_isa,
    "afc_tra_por" => $afc_tra_por,
    "afc_tra_v" => $afc_tra_v,
    "afc_empl_por"  => $afc_empl_por,
    "afc_empl_v"  => $afc_empl_v,
    "sis_por" => $sis_por,
    "sis_v" => $sis_v,
    "acc_trab_por"  => $acc_trab_por,
    "acc_trab_v"  => $acc_trab_v,
    "aporte_empl" => $aporte_empl,
    "otros" => $otros,
    "total_prevision" => $total_prevision,
    "base_tributable" => $base_tributable,
    "apagar"  => $apagar,
    "id_apr"  => $id_apr,
    "fecha_genera"  => date_format(date_create($fecha_genera), 'Y-m-d'),
    "usuario_genera"  => $usuario_genera,
    "estado"          =>1
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

      $pagecount = $mpdf->SetSourceFile("base_liquidacion3.pdf");

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
            l.rut,
            date_format(f.fecha_contrato ,'%d-%m-%Y') as fecha_contrato,
                  concat(l.rut,'-',f.dv) as rut,
                  concat(f.nombres,' ',f.APE_PAT,' ',F.APE_MAT) as funcionario,
                   CASE
                  WHEN MONTH(mes) = 1 THEN 'Enero'
                  WHEN MONTH(mes) = 2 THEN 'Febrero'
                  WHEN MONTH(mes) = 3 THEN 'Marzo'
                  WHEN MONTH(mes) = 4 THEN 'Abril'
                  WHEN MONTH(mes) = 5 THEN 'Mayo'
                  WHEN MONTH(mes) = 6 THEN 'Junio'
                  WHEN MONTH(mes) = 7 THEN 'Julio'
                  WHEN MONTH(mes) = 8 THEN 'Agosto'
                  WHEN MONTH(mes) = 9 THEN 'Septiembre'
                  WHEN MONTH(mes) = 10 THEN 'Octubre'
                  WHEN MONTH(mes) = 11 THEN 'Noviembre'
                  WHEN MONTH(mes) = 12 THEN 'Diciembre'
              END as mes,
                  date_format(mes,'%Y') as ano,
            l.id_funcionario,
            
            l.valor_uf,
            l.dias_trabajados,
            l.sueldo_base,
            l.h_extras_c,
            l.h_extras_v,
            l.gratif_por,
            l.gratif_val,
            l.bono_respon,
            l.total_imponible,
            l.bono_colacion,
            l.asig_familiar,
            l.bono_movil,
            l.viatico,
            l.feriado_proporcional,
            l.total_otros_haberes,
            l.afp,
            l.obligatorio,
            l.pactada,
            l.diferencia_isa,
            l.afc_tra_por,
            l.afc_tra_v,
            l.afc_empl_por,
            l.afc_empl_v,
            l.sis_por,
            l.sis_v,
            l.acc_trab_por,
            l.acc_trab_v,
            l.aporte_empl,
            l.otros,
            l.total_prevision,
            l.base_tributable,
            l.apagar,
            l.id_apr,
            l.fecha_genera,
            l.usuario_genera,
            f.sueldo_bruto,
            f.prevision,
            F.prev_porcentaje,
            f.afp as afp_fun,
            f.afp_porcentaje
            FROM liquidaciones l
            inner join funcionarios f on l.id_funcionario=f.id
            inner join usuarios u on u.id=l.usuario_genera
            and l.id_apr=$id_apr  and f.estado=1 and l.id=$id";
      $query = $this->db->query($sql);
      $data  = $query->getResultArray();

      // print_r($data);
      // exit();
     
      $id = $data[0]["id"];
      $fecha_contrato = $data[0]["fecha_contrato"];
      $rut = $data[0]["rut"];
      $funcionario = $data[0]["funcionario"];
      $mes = $data[0]["mes"];
      $ano = $data[0]["ano"];
      $id_funcionario = $data[0]["id_funcionario"];
      $mes_liquidacion = $data[0]["mes"];
      $valor_uf = $data[0]["valor_uf"];
      $dias_trabajados = $data[0]["dias_trabajados"];
      $sueldo_base = $data[0]["sueldo_base"];
      $h_extras_c = $data[0]["h_extras_c"];
      $h_extras_v = $data[0]["h_extras_v"];
      $gratif_por = $data[0]["gratif_por"];
      $gratif_val = $data[0]["gratif_val"];
      $bono_respon = $data[0]["bono_respon"];
      $total_imponible = $data[0]["total_imponible"];
      $bono_colacion = $data[0]["bono_colacion"];
      $asig_familiar = $data[0]["asig_familiar"];
      $bono_movil = $data[0]["bono_movil"];
      $viatico = $data[0]["viatico"];
      $feriado_proporcional = $data[0]["feriado_proporcional"];
      $total_otros_haberes = $data[0]["total_otros_haberes"];
      $afp = $data[0]["afp"];
      $obligatorio = $data[0]["obligatorio"];
      $pactada = $data[0]["pactada"];
      $diferencia_isa = $data[0]["diferencia_isa"];
      $afc_tra_por = $data[0]["afc_tra_por"];
      $afc_tra_v = $data[0]["afc_tra_v"];
      $afc_empl_por = $data[0]["afc_empl_por"];
      $afc_empl_v = $data[0]["afc_empl_v"];
      $sis_por = $data[0]["sis_por"];
      $sis_v = $data[0]["sis_v"];
      $acc_trab_por = $data[0]["acc_trab_por"];
      $acc_trab_v = $data[0]["acc_trab_v"];
      $aporte_empl = $data[0]["aporte_empl"];
      $otros = $data[0]["otros"];
      $total_prevision = $data[0]["total_prevision"];
      $base_tributable = $data[0]["base_tributable"];
      $apagar = $data[0]["apagar"];
      $id_apr = $data[0]["id_apr"];
      $fecha_genera = $data[0]["fecha_genera"];
      $usuario_genera = $data[0]["usuario_genera"];
      $sueldo_bruto = $data[0]["sueldo_bruto"];

      $prevision=$data[0]['prevision'];
      $prev_porcentaje=$data[0]['prev_porcentaje'];
      $afp_fun=$data[0]['afp_fun'];
      $afp_porcentaje=$data[0]['afp_porcentaje'];

      if($prevision=='ISAPRE'){
        $dife_isapre=$prev_porcentaje.' UF';
      }else{
        $dife_isapre='--';
      }
      $mpdf->SetXY(40, 47);
      $mpdf->SetFont('Arial', '', 9);
      $mpdf->Cell(0, 0, $rut, 0, 1, 'L');
      $mpdf->SetXY(85, 47);
      $mpdf->Cell(0, 0, $funcionario, 0, 1, 'L');
      $mpdf->SetXY(180, 47);
      $mpdf->Cell(0, 0, $fecha_contrato, 0, 1, 'L');

     
      $mpdf->SetXY(40, 53);
      $mpdf->Cell(0, 0, $mes.' '.$ano, 0, 1, 'L');

      $mpdf->SetXY(85, 53);
      $mpdf->Cell(0, 0, '$'.$sueldo_bruto, 0, 1, 'L');

      $mpdf->SetXY(140, 53);
      $mpdf->Cell(0, 0, '$'.$valor_uf, 0, 1, 'L');

      $mpdf->SetXY(145, 58);
      $mpdf->Cell(0, 0, $dias_trabajados, 0, 1, 'L');


      $mpdf->SetXY(90, 72);
      $mpdf->Cell(0, 0, '$'.$sueldo_base, 0, 1, 'L');

      $mpdf->SetXY(64, 78);
      $mpdf->Cell(0, 0, 'N° Hras '.$h_extras_c.'    Valor $'.$h_extras_v, 0, 1, 'L');

      $mpdf->SetXY(67, 83);
      $mpdf->Cell(0, 0, '  '.$gratif_por.'%    Valor $'.$gratif_val, 0, 1, 'L');

      $mpdf->SetXY(90, 88.5);
      $mpdf->Cell(0, 0, '$'.$bono_respon, 0, 1, 'L');

      $mpdf->SetXY(90, 93);
      $mpdf->Cell(0, 0, '$'.$total_imponible, 0, 1, 'L');


      $mpdf->SetXY(158, 72);
      $mpdf->Cell(0, 0, $afp_fun.' '.$afp_porcentaje.'%         $'.$afp, 0, 1, 'L');

      $mpdf->SetXY(195, 78);
      $mpdf->Cell(0, 0, '$'.$obligatorio, 0, 1, 'L');

      $mpdf->SetXY(178, 83);
      $mpdf->Cell(0, 0, $dife_isapre.'     $'.$diferencia_isa, 0, 1, 'L');

      $mpdf->SetXY(181, 88.5);
      $mpdf->Cell(0, 0, ''.$afc_tra_por.'%     $'.$afc_tra_v, 0, 1, 'L');

      $mpdf->SetXY(195, 93);
      $mpdf->Cell(0, 0, '$'.$total_prevision, 0, 1, 'L');


      $mpdf->SetXY(90, 103);
      $mpdf->Cell(0, 0, '$'.$bono_colacion, 0, 1, 'L');

      $mpdf->SetXY(90, 108);
      $mpdf->Cell(0, 0, '$'.$asig_familiar, 0, 1, 'L');

      $mpdf->SetXY(90, 113);
      $mpdf->Cell(0, 0, '$'.$bono_movil, 0, 1, 'L');

      $mpdf->SetXY(90, 118);
      $mpdf->Cell(0, 0, '$'.$viatico, 0, 1, 'L');

      $mpdf->SetXY(90, 123);
      $mpdf->Cell(0, 0, '$'.$feriado_proporcional, 0, 1, 'L');

      $mpdf->SetXY(90, 128);
      $mpdf->Cell(0, 0, '$'.$total_otros_haberes, 0, 1, 'L');


      $mpdf->SetXY(90, 138);
      $mpdf->Cell(0, 0, '$'.($total_otros_haberes+$total_imponible), 0, 1, 'L');




      $mpdf->SetXY(181, 103);
      $mpdf->Cell(0, 0, ''.$afc_empl_por.'%     $'.$afc_empl_v, 0, 1, 'L');

      $mpdf->SetXY(181, 108);
      $mpdf->Cell(0, 0, ''.$sis_por.'%     $'.$sis_v, 0, 1, 'L');

      $mpdf->SetXY(181, 113);
      $mpdf->Cell(0, 0, ''.$acc_trab_por.'%     $'.$acc_trab_v, 0, 1, 'L');

      $mpdf->SetXY(195, 128);
      $mpdf->Cell(0, 0, '$'.$aporte_empl, 0, 1, 'L');

      $mpdf->SetXY(195, 138);
      $mpdf->Cell(0, 0, '$'.$apagar, 0, 1, 'L');
      
   

      // return $mpdf->Output("liquidacion sueldo " . $funcionario ." ".$mes."-".$ano. ".pdf","D");
         header("Content-type:application/pdf");
   
         return redirect()->to($mpdf->Output());

    }


}

?>
