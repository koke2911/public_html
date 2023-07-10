<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;

class Ctrl_informe_trimestral extends BaseController {

  protected $sesión;
  protected $db;

  public function __construct() {
    $this->sesión = session();
    $this->db     = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function reporte_trimestral($consulta){

    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;
    $nombre_apr=$this->sesión->apr_ses;
    $db=$this->db;
    $periodo='';
    $valores=explode('-', $consulta);
    $trimestre=$valores[1];
    $ano=$valores[0];

    // echo $trimestre;
    // exit();
    if($trimestre==1){
      $periodo='01/01/'.$ano.' al 31/03/'.$ano;
      $mes1='Enero';
      $mes2='Febrero';
      $mes3='Marzo';
    }

    if($trimestre==2){
      $periodo='01/04/'.$ano.' al 30/06/'.$ano;
      $mes1='Abril';
      $mes2='Mayo';
      $mes3='Junio';
    }

    if($trimestre==3){
      $periodo='01/07/'.$ano.' al 30/09/'.$ano;
      $mes1='Julio';
      $mes2='Agosto';
      $mes3='Septiembre';
    }

    if($trimestre==4){
      $periodo='01/10/'.$ano.' al 31/12/'.$ano;
      $mes1='Octubre';
      $mes2='Noviembre';
      $mes3='Diciembre';
    }
   

    $archivoExcel = 'FTI.xlsx';
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivoExcel);
    $hoja = $spreadsheet->getActiveSheet();

    // HOJA NISTRUCCIONES**********************************
    $HojaInstrucciones = 'Instrucciones';
    $spreadsheet->setActiveSheetIndexByName($HojaInstrucciones);
    $instrucciones = $spreadsheet->getActiveSheet();

    $consulta = "SELECT upper(c.nombre) as comuna,p.nombre as Provincia ,r.nombre as region from apr  a 
                inner join comunas c  on c.id=a.id_comuna
                inner join provincias p  on p.id=c.id_provincia
                inner join regiones r  on r.id=p.id_region
                where a.id=$id_apr";
    $query = $db->query($consulta);
    $DatosRegion  = $query->getResultArray();

    $comuna=$DatosRegion[0]['comuna'];
    $region=$DatosRegion[0]['region'];
    $provincia=$DatosRegion[0]['Provincia'];

    $instrucciones->setCellValue('F5', $nombre_apr);
    $instrucciones->setCellValue('F6', $region);
    $instrucciones->setCellValue('F7', $provincia);
    $instrucciones->setCellValue('F8', $comuna);
    $instrucciones->setCellValue('F9', $periodo);

    // HOJA INGRESOS C9 D9 E9 *************************************

    $HojaIngresos = '1. Ingresos';
    $spreadsheet->setActiveSheetIndexByName($HojaIngresos);
    $ingresos = $spreadsheet->getActiveSheet();

    $ingresos->setCellValue('C9', $mes1);
    $ingresos->setCellValue('D9', $mes2);
    $ingresos->setCellValue('E9', $mes3);
   

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte Trimestre '.$trimestre.' del '.$ano.'.xlsx"');
    header('Cache-Control: max-age=0');

    
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');


  }

  
}

?>