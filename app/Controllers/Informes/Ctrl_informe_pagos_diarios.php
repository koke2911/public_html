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


        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANTIDAD PAGOS');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'TOTAL');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'TIPO');
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
}

?>