<?php

namespace App\Controllers\Informes;

use App\Models\Pagos\Md_caja;
use App\Controllers\BaseController;

class Ctrl_informe_pagos_diarios extends BaseController {

  protected $caja;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->caja   = new Md_caja();
    $this->sesión = session();
    $this->db     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_informe_pagos_diarios($fecha) {
    $this->validar_sesion();
    echo $this->caja->datatable_informe_pagos_diarios($this->db, $this->sesión->id_apr_ses,$fecha);
  }

  public function reporte_pagos_resumen($fecha){
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);


        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'N° PAGOS');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'TOTAL PAGADO');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'METODO DE PAGO');
        $sheet = $objPHPExcel->getActiveSheet();

       foreach ($sheet->getColumnIterator() as $column) {
         $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
      }

      $this->validar_sesion();
      $id_apr=$this->sesión->id_apr_ses;

      $datosCaja = $this->caja
             ->select("count(caja.id) as  cantidad")
             ->select("sum(caja.total_pagar) as TOTAL")
             ->select("fp.glosa as forma_pago")
             ->join("forma_pago fp", "caja.id_forma_pago = fp.id")
             ->join("caja_detalle cd","cd.id_caja = caja.id")
             ->where("caja.id_apr", $id_apr)
             ->where("date_format(caja.fecha, '%d-%m-%Y')",$fecha) 
             ->where("caja.estado",1) 
             ->groupBy("forma_pago")
             ->findAll();

      $sheet->fromArray($datosCaja, NULL, 'A2'); 

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
      header('Content-Disposition: attachment;filename="Resumen informe Pagos Diarios '.$fecha.'.xlsx"'); 

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
      $writer->save('php://output');

  }

   public function reporte_pagos_resumen_mes($fecha){
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);


        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'N° PAGOS');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'TOTAL PAGADO');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'METODO DE PAGO');
        $sheet = $objPHPExcel->getActiveSheet();

       foreach ($sheet->getColumnIterator() as $column) {
         $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
      }

      $this->validar_sesion();
      $id_apr=$this->sesión->id_apr_ses;

      $datosCaja = $this->caja
             ->select("count(caja.id) as  cantidad")
             ->select("sum(caja.total_pagar) as TOTAL")
             ->select("fp.glosa as forma_pago")
             ->join("forma_pago fp", "caja.id_forma_pago = fp.id")
             ->join("caja_detalle cd","cd.id_caja = caja.id")
             ->where("caja.id_apr", $id_apr)
             ->where("date_format(caja.fecha, '%m-%Y')",$fecha) 
             ->where("caja.estado",1) 
             ->groupBy("forma_pago")
             ->findAll();

      $sheet->fromArray($datosCaja, NULL, 'A2'); 

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
      header('Content-Disposition: attachment;filename="Resumen informe Pagos Mensual '.$fecha.'.xlsx"'); 

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
      $writer->save('php://output');

  }

   public function reporte_desinfeccion($mes){
        $this->validar_sesion();
        $id_apr=$this->sesión->id_apr_ses;
        $nombre_apr=$this->sesión->apr_ses;
        $db=$this->db;

        $dato=explode('-',$mes);
        $año=$dato[1];;
        $mes_solo=$dato[0];
        // $nombre_apr=
        // exit();

        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setTitle('Desinfeccion');


      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'INFORME MENSUAL CONTROL REQUISITOS DE DESINFECCION');

      $objPHPExcel->getActiveSheet()->setCellValue('A2', 'SERVICIO SANITARIO RURAL :'.$nombre_apr);
      $objPHPExcel->getActiveSheet()->setCellValue('A3', 'MES:'.$mes_solo.'    AÑO:'.$año);

      //A5 I5
      $objPHPExcel->getActiveSheet()->mergeCells('A5:I5');
      $objPHPExcel->getActiveSheet()->setCellValue('A5', 'MEDICION DIARIA DE CLORO RESIDUAL');

      $objPHPExcel->getActiveSheet()->mergeCells('J5:N5');
      $objPHPExcel->getActiveSheet()->setCellValue('J5', 'REGULACION');

      $objPHPExcel->getActiveSheet()->mergeCells('A6:C8');
      $objPHPExcel->getActiveSheet()->setCellValue('A6', 'PLANTA DE AGUA POTABLE');

      $objPHPExcel->getActiveSheet()->mergeCells('D6:F8');
      $objPHPExcel->getActiveSheet()->setCellValue('D6', 'RED DISTRIBUCION MUESTRA N°1'); 
      $objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->setWrapText(true); 


      $objPHPExcel->getActiveSheet()->mergeCells('G6:I8');
      $objPHPExcel->getActiveSheet()->setCellValue('G6', 'RED DISTRIBUCION MUESTRA N°2');
      $objPHPExcel->getActiveSheet()->getStyle('G6')->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->mergeCells('J6:K8');
      $objPHPExcel->getActiveSheet()->setCellValue('J6', 'DOSIFICADOR CLORADOR');
      $objPHPExcel->getActiveSheet()->getStyle('J6')->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->mergeCells('L6:L8');
      $objPHPExcel->getActiveSheet()->setCellValue('L6', 'MEDIDOR CAUDAL');
      $objPHPExcel->getActiveSheet()->getStyle('L6')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('M6:M8');
      $objPHPExcel->getActiveSheet()->setCellValue('M6', 'MEDIDOR ELECTRICID');
      $objPHPExcel->getActiveSheet()->getStyle('M6')->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->mergeCells('N6:N8');
      $objPHPExcel->getActiveSheet()->setCellValue('N6', 'HOROMETRO');
      $objPHPExcel->getActiveSheet()->getStyle('N6')->getAlignment()->setWrapText(true);


      $objPHPExcel->getActiveSheet()->mergeCells('A9:A11');
      $objPHPExcel->getActiveSheet()->setCellValue('A9', 'DIA');
      $objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->mergeCells('B9:B11');
      $objPHPExcel->getActiveSheet()->setCellValue('B9', 'HORA');
      $objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('C9:C11');
      $objPHPExcel->getActiveSheet()->setCellValue('C9', 'CLORO RESIDUAL MG/L');
      $objPHPExcel->getActiveSheet()->getStyle('C9')->getAlignment()->setWrapText(true);  
      $objPHPExcel->getActiveSheet()->getColumnDimension('C9')->setAutoSize(true); 


      $objPHPExcel->getActiveSheet()->mergeCells('D9:D11');
      $objPHPExcel->getActiveSheet()->setCellValue('D9', 'NOMBRE SOCIO');
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

      $objPHPExcel->getActiveSheet()->mergeCells('E9:E11');
      $objPHPExcel->getActiveSheet()->setCellValue('E9', 'HORA');
      $objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('F9:F11');
      $objPHPExcel->getActiveSheet()->setCellValue('F9', 'CLORO RESIDUAL MG/L');
      $objPHPExcel->getActiveSheet()->getStyle('F9')->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->mergeCells('G9:G11');
      $objPHPExcel->getActiveSheet()->setCellValue('G9', 'NOMBRE SOCIO');
      $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);  

      $objPHPExcel->getActiveSheet()->mergeCells('H9:H11');
      $objPHPExcel->getActiveSheet()->setCellValue('H9', 'HORA');
      $objPHPExcel->getActiveSheet()->getStyle('H9')->getAlignment()->setWrapText(true);  
      // $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

      $objPHPExcel->getActiveSheet()->mergeCells('I9:I11');
      $objPHPExcel->getActiveSheet()->setCellValue('I9', 'CLORO RESIDUAL MG/L');
      $objPHPExcel->getActiveSheet()->getStyle('I9')->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->mergeCells('J9:J11');
      $objPHPExcel->getActiveSheet()->setCellValue('J9', 'FREC.');
      $objPHPExcel->getActiveSheet()->getStyle('J9')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('K9:K11');
      $objPHPExcel->getActiveSheet()->setCellValue('K9', 'DESP.');
      $objPHPExcel->getActiveSheet()->getStyle('K9')->getAlignment()->setWrapText(true);    

      $objPHPExcel->getActiveSheet()->mergeCells('L9:L11');
      $objPHPExcel->getActiveSheet()->setCellValue('L9', 'M3');
      $objPHPExcel->getActiveSheet()->getStyle('L9')->getAlignment()->setWrapText(true);    

      $objPHPExcel->getActiveSheet()->mergeCells('M9:M11');
      $objPHPExcel->getActiveSheet()->setCellValue('M9', 'KW');
      $objPHPExcel->getActiveSheet()->getStyle('M9')->getAlignment()->setWrapText(true);  

      $objPHPExcel->getActiveSheet()->mergeCells('N9:N11');
      $objPHPExcel->getActiveSheet()->setCellValue('N9', '--');
      $objPHPExcel->getActiveSheet()->getStyle('N9')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('A45:D45');
      $objPHPExcel->getActiveSheet()->setCellValue('A45', 'DETALLE DE CONSUMOS');
      $objPHPExcel->getActiveSheet()->getStyle('A45')->getAlignment()->setWrapText(true);    

      $objPHPExcel->getActiveSheet()->mergeCells('A46:D46');
      $objPHPExcel->getActiveSheet()->setCellValue('A46', 'AGUA PRODUCIDA');
      $objPHPExcel->getActiveSheet()->getStyle('A46')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('A47:D47');
      $objPHPExcel->getActiveSheet()->setCellValue('A47', 'AGUA FACTURADA');
      $objPHPExcel->getActiveSheet()->getStyle('A47')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('A48:D48');
      $objPHPExcel->getActiveSheet()->setCellValue('A48', 'CONSUMO ENERGIA ELECTRICA');
      $objPHPExcel->getActiveSheet()->getStyle('A48')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('A49:D49');
      $objPHPExcel->getActiveSheet()->setCellValue('A49', 'NUMERO TOTAL DE ARRANQUES');
      $objPHPExcel->getActiveSheet()->getStyle('A49')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('A50:D50');
      $objPHPExcel->getActiveSheet()->setCellValue('A50', 'GASTO MENSUAL DE CLORO');
      $objPHPExcel->getActiveSheet()->getStyle('A50')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('A51:D51');
      $objPHPExcel->getActiveSheet()->setCellValue('A51', 'HOROMETRO');
      $objPHPExcel->getActiveSheet()->getStyle('A51')->getAlignment()->setWrapText(true);


      $objPHPExcel->getActiveSheet()->setCellValue('F45', 'M3');

      $objPHPExcel->getActiveSheet()->setCellValue('F46', 'M3');

      $objPHPExcel->getActiveSheet()->setCellValue('F47', 'M3');

      $objPHPExcel->getActiveSheet()->setCellValue('F48', 'KW.');

      $objPHPExcel->getActiveSheet()->setCellValue('F49', 'N°');

      $objPHPExcel->getActiveSheet()->setCellValue('F50', 'KGS.');

      $objPHPExcel->getActiveSheet()->setCellValue('F51', 'HRS.');

      $objPHPExcel->getActiveSheet()->mergeCells('J45:K45');
      $objPHPExcel->getActiveSheet()->setCellValue('J45', 'NIVEL DE POZOS');
      $objPHPExcel->getActiveSheet()->getStyle('J45')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('J46:K46');
      $objPHPExcel->getActiveSheet()->setCellValue('J46', 'NIVEL DINAMICO (1)');
      $objPHPExcel->getActiveSheet()->getStyle('J46')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('J47:K47');
      $objPHPExcel->getActiveSheet()->setCellValue('J47', 'NIVEL ESTATICO (1)');
      $objPHPExcel->getActiveSheet()->getStyle('J47')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('J48:K48');
      $objPHPExcel->getActiveSheet()->setCellValue('J48', 'NIVEL DINAMICO (2)');
      $objPHPExcel->getActiveSheet()->getStyle('J48')->getAlignment()->setWrapText(true);

      $objPHPExcel->getActiveSheet()->mergeCells('J49:K49');
      $objPHPExcel->getActiveSheet()->setCellValue('J49', 'NIVEL ESTATICO (2)');
      $objPHPExcel->getActiveSheet()->getStyle('J49')->getAlignment()->setWrapText(true);


      $borderStyle=[
              'borders' => [
                'bottom' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'top' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'right' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'left' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
              ],
      ];

      $objPHPExcel->getActiveSheet()->getStyle('A5:N43')->applyFromArray($borderStyle,false);

      $objPHPExcel->getActiveSheet()->getStyle('A45:F51')->applyFromArray($borderStyle,false);

      $objPHPExcel->getActiveSheet()->getStyle('J45:L49')->applyFromArray($borderStyle,false);


      $backgroundStyle = [
          'fill' => [
              'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
              'startColor' => [
                  'rgb' => 'B7DEE8' 
              ]
          ]
      ];

      $objPHPExcel->getActiveSheet()->getStyle('A5:N11')->applyFromArray($backgroundStyle);




      $sheet = $objPHPExcel->getActiveSheet();
      // $sheet->freezePane('A12');

        list($month, $year) = explode('-', $mes);

        $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $dataArray = array(); 

          for ($day = 1; $day <= $numDays; $day++) {

              $formattedDay = ($day < 10) ? '0' . $day : $day;
              $consulta = "SELECT date_format(dia,'%d') as dia,
                          hora_ap,
                          cloro_ap,
                          concat(s1.nombres, ' ', s1.ape_pat, ' ', s1.ape_mat) as nombre_socio1,
                          hora_socio1,
                          cloro_socio1,
                          concat(s2.nombres, ' ', s2.ape_pat, ' ', s2.ape_mat) as nombre_socio2,
                          hora_socio2,
                          cloro_socio2,
                          frecuencia,desp,medidor_caudal,electricidad,horometro
                          from desinfecciones d
                          inner join socios s1 on s1.id=d.id_socio1
                          inner join socios s2 on s2.id=d.id_socio2 
                          where d.id_apr=$id_apr and d.estado=1 and date_format(d.dia,'%d-%m-%Y')='$formattedDay-"."$mes'";

              $query = $db->query($consulta);
              $result  = $query->getResultArray();
              // print_r($result);

              if (empty($result)) {
                  $blankData = array(
                      'dia' => $formattedDay,
                      'hora_ap' => '',
                      'cloro_ap' => '',
                      'nombre_socio1' => '',
                      'hora_socio1' => '',
                      'cloro_socio1' => '',
                      'nombre_socio2' => '',
                      'hora_socio2' => '',
                      'cloro_socio2' => '',
                      'frecuencia' => '',
                      'desp' => '',
                      'medidor_caudal' => '',
                      'electricidad' => '',
                      'horometro' => ''
                  );

                  array_push($dataArray, $blankData);
              } else {
                  $dataArray = array_merge($dataArray, $result);
              }
          }

          $data = $dataArray; 

      $sheet->fromArray($data, NULL, 'A12'); 



      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
      header('Content-Disposition: attachment;filename="Resumen Desinfeccion Mensual '.$mes.'.xlsx"'); 

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
      $writer->save('php://output');

  }
}

?>