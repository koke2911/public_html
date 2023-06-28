<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_subsidios_sol;

class Ctrl_solicitud_subsidio extends BaseController {
 
  protected $subsidios_sol;  
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->subsidios_sol = new Md_subsidios_sol();
    $this->sesión         = session();
    $this->db             = \Config\Database::connect();

  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_socios(){
     $this->validar_sesion();
     echo $this->subsidios_sol->datatable_socios($this->db, $this->sesión->id_apr_ses);
  }

  public function generar_solicitud(){
    $this->validar_sesion();
    $fecha      = date("Y-m-d");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_socio= $this->request->getPost("id_socio");

     $datosSol = [
          'id_socio'=>$id_socio ,
          'id_apr' => $id_apr,
          'estado'=> 1 ,
          'fecha_sol'=> date_format(date_create($fecha), 'Y-m-d'),
          'usu_sol' => $id_usuario,
        ];

    // print_r($datosSol);

    if ($this->subsidios_sol->save($datosSol)) {
      echo 1;
    }else{
      echo 'No se pudo guardar el formulario';
    }

  }

  public function imprime_solicitud($id_sol){
    $this->validar_sesion();
    // echo $id_sol;
      $mpdf = new \Mpdf\Mpdf([
                            'mode'          => 'utf-8',
                            'format'        => 'letter',
                            'margin_bottom' => 1
                           ]);

      $pagecount = $mpdf->SetSourceFile("fichaPos2.pdf");
      
      for ($i = 1; $i <= $pagecount; $i++) {
            $tplId = $mpdf->ImportPage($i);
            $mpdf->AddPage();
            $mpdf->UseTemplate($tplId);

          if ($i === 1) {
             

            $consulta = "SELECT date_format(so.fecha_sol,'%d') as dia,
            date_format(so.fecha_sol,'%m') as mes,
            date_format(so.fecha_sol,'%y') as ano,
            s.nombres, s.ape_pat,s.ape_mat,
            concat(s.calle ,' N°:',s.numero,' - ',s.resto_direccion) as direccion,
            s.rut,s.dv,
            concat(u.nombres,' ',u.ape_paterno) as usuarios,
            u.ape_materno as u_materno
            from subsidios_sol  so 
            inner join socios s on s.id=so.id_socio
            inner join usuarios u on u.id=so.usu_sol
            where so.id=$id_sol";

            $query = $this->db->query($consulta);
            $data  = $query->getResultArray();

            $x = 72;
            $y = 65;
            $mpdf->SetXY($x, $y);

            $mpdf->Cell(0, 10, $data[0]['dia'][0], 0, 1, 'L');

            $x = 78;
            $y = 65;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['dia'][1], 0, 1, 'L');


            $x = 84;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['mes'][0], 0, 1, 'L');

            $x = 90;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['mes'][1], 0, 1, 'L');

            $x = 96;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ano'][0], 0, 1, 'L');

            $x = 102;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ano'][1], 0, 1, 'L');

            $x=30;

            $y=100;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ape_pat'], 0, 1, 'L');


            $x=70;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ape_mat'], 0, 1, 'L');


            $x=108;
            $nombres=explode(" ", $data[0]['nombres']);
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $nombres[0], 0, 1, 'L');

            $x=148;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $nombres[1], 0, 1, 'L');

            $x=75;
            $y=115;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['rut'], 0, 1, 'L');

            $x=103;
            $y=115;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['dv'], 0, 1, 'L');


            $x=30;
            $y=134;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['direccion'], 0, 1, 'L');



            $x=30;

            $y=175;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ape_pat'], 0, 1, 'L');


            $x=80;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ape_mat'], 0, 1, 'L');


            $x=132;
            // $nombres=explode(" ", $data[0]['nombres']);
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['nombres'], 0, 1, 'L');

            $y=203;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['usuarios'].' '. $data[0]['u_materno'][0], 0, 1, 'L');



            
            // $fecha_sol=$data['fecha'];

            // print_r($data);
            // exit();


          }else{
            $x=30;

            $y=30;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ape_pat'], 0, 1, 'L');


            $x=70;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ape_mat'], 0, 1, 'L');


            $x=108;
            $nombres=explode(" ", $data[0]['nombres']);
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $nombres[0], 0, 1, 'L');

            $x=148;
            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $nombres[1], 0, 1, 'L');


            $x=127;

            $y=40;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['dia'], 0, 1, 'L');

            $x=137;

            $y=40;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['mes'], 0, 1, 'L');

            $x=150;

            $y=40;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['ano'], 0, 1, 'L');


            $x=59;

            $y=46;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['rut'], 0, 1, 'L');

            $x=88;

            $y=46;

            $mpdf->SetXY($x, $y);
            $mpdf->Cell(0, 10, $data[0]['dv'], 0, 1, 'L');
          }

            
        }

            
    

    // $this->mpdf->writeHtml($html);



    return redirect()->to($mpdf->Output("Solicitud Subsidio " . $id_sol . ".pdf", "I"));

  }

}

?>

