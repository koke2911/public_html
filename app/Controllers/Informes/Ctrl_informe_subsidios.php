<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_subsidios;
use App\Models\Formularios\Md_socios;
use App\Models\Configuracion\Md_comunas;
use App\Models\Formularios\Md_arranques;


class Ctrl_informe_subsidios extends BaseController {

  protected $subsidios;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->subsidios = new Md_subsidios();
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
    $this->socios            = new Md_socios();
    $this->comunas            = new Md_comunas();
    $this->arranques             = new Md_arranques();


  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_subsidios() {
    $this->validar_sesion();
    echo $this->subsidios->datatable_informe_subsidios($this->db, $this->sesión->id_apr_ses);
  }

  public function consolidado_municipal($fecha){
    // echo $fecha;
    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);


        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CODCOM');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'ORIGEN');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'RUT');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'DV-RU');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'AP.PATERNO');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'AP.MATERNO');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'NOMBRES');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'DIRECCION');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'NUM-DEC');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'FEC-DEC');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'TRAMO  RSH');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'FEC-ENC');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'NUMUNICO');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'DV-NUMUNICO');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'NUMVIVTOT');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'CONSUMO');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'MONSUBS');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', '2710');
        $objPHPExcel->getActiveSheet()->setCellValue('S1', '2710');
        $objPHPExcel->getActiveSheet()->setCellValue('T1', 'MONDEUD');
        $objPHPExcel->getActiveSheet()->setCellValue('U1', 'OBSERVACIÓN');

       $sheet = $objPHPExcel->getActiveSheet();

       foreach ($sheet->getColumnIterator() as $column) {
         $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
      }

      $this->validar_sesion();
      $id_apr=$this->sesión->id_apr_ses;

      $datosSocios = $this->subsidios
             ->select("c.id as comcod")
             ->select("c.nombre as origen")
             ->select("s.rut as RUT")
             ->select("s.dv as DV")
             ->select("s.ape_pat as ape_pat")
             ->select("s.ape_mat as ape_mat")
             ->select("s.nombres as nombres")
             ->select("s.calle as direccion")//numero resto_direccion
             ->select("subsidios.numero_decreto as n_decreto")
             ->select("date_format(subsidios.fecha_decreto,'%d-%m-%Y') as fecha_decreto")
             ->select("subsidios.puntaje as tramo")
             ->select("date_format(subsidios.fecha_encuesta,'%d-%m-%Y') as fecha_encuesta")
             ->select("subsidios.numero_unico as numero_unico")
             ->select("subsidios.digito_unico as digito_unico")
             ->select("subsidios.n_viviendas as viviendas")
             ->select("m.metros as metros")
             ->select("m.monto_subsidio as monto_subsidio")
             ->select("m.total_mes as total_mes")
             ->select("ifnull(afecto_corte(s.id, s.id_apr),0) as meses_pendientes")
             ->select("total_deuda(s.id, s.id_apr) as total_deuda")
             ->select(" ' ' as OBS")
            
             ->join("socios s", "s.id = subsidios.id_socio")
             ->join("metros m", "m.id_socio = subsidios.id_socio")
             ->join("comunas c "," c.id = s.id_comuna", "left")
             ->where("subsidios.id_apr", $id_apr)
             ->where("s.id_apr", $id_apr)
             ->where("m.id_apr", $id_apr)
             ->where("m.estado <> 0")
             ->where("date_format(m.fecha_ingreso, '%m-%Y')",$fecha)             
             ->findAll();

      $sheet->fromArray($datosSocios, NULL, 'A2'); 

      // print_r($datosSocios);
      // exit();

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
      header('Content-Disposition: attachment;filename="Subsidios Consolidado municipal '.$fecha.'.xlsx"'); 

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
      $writer->save('php://output');


  }
}

?>