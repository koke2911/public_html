<?php

namespace App\Controllers\Informes;

use App\Controllers\BaseController;
use App\Models\Informes\Md_arqueo_caja;

class Ctrl_libro_caja extends BaseController {

  protected $sesión;
  protected $db;
  protected $arqueo_caja;


  public function __construct() {
    $this->sesión = session();
    $this->db     = \Config\Database::connect();
    $this->arqueo_caja = new Md_arqueo_caja();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function reporte_trimestral($mes){

    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;
    $nombre_apr=$this->sesión->apr_ses;
    $db=$this->db;
   
    // echo $mes;
    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setTitle('Libro de Caja');


     
      //A5 I5
      $objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
      $objPHPExcel->getActiveSheet()->setCellValue('A2', 'INGRESOS '.$mes);
      $estiloCelda = $objPHPExcel->getActiveSheet()->getStyle('A2');
      $estiloCelda->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $estiloCelda->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'FECHA');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'DETALLE');
      $objPHPExcel->getActiveSheet()->setCellValue('C4', 'CONSUMO AGUA NETO TODAS LAS TRANSACC. DIARIAS');
      $objPHPExcel->getActiveSheet()->setCellValue('D4', 'DERECHO INCORPOR. MENSUAL');
      $objPHPExcel->getActiveSheet()->setCellValue('E4', 'IVA');
      $objPHPExcel->getActiveSheet()->setCellValue('F4', 'BANCO GIROS MENSUAL');
      $objPHPExcel->getActiveSheet()->setCellValue('G4', 'OTROS INGRESOS MENSUAL');
      $objPHPExcel->getActiveSheet()->setCellValue('H4', 'TOTAL');
      $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getAlignment()->setWrapText(true);

      $columnas = range('A4', 'H4');

       $borderStyle=[
              'borders' => [
                'bottom' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'top' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'right' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'left' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
              ],
      ];

      $objPHPExcel->getActiveSheet()->getStyle('A4:H4')->applyFromArray($borderStyle,false);

      foreach ($columnas as $columna) {
          $objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
      } 

        $consulta = "SELECT date_format(c.fecha,'%d-%m-%Y') as dia , 
        'PAGO CONSUMO MES AGUA POTABLE' as glosa,
        sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0)) 
        as total ,'' as total2 , '' as total3, '' as total4,'' as total5,sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as total6
        from caja c
        inner join caja_detalle d on d.id_caja=c.id
        inner join metros m on m.id=d.id_metros
        where date_format(c.fecha,'%m-%Y')='$mes' 
        and c.id_apr=$id_apr  and c.estado =1
        group by dia,glosa 
        UNION
              select date_format(c.fecha,'%m-Y') as dia , 
              'CUOTA SOCIO $mes ' as glosa,
              ' ' as total ,sum(ifnull(m.alcantarillado,0)) , ''as total3 , ''as total4,''as total5 ,sum(ifnull(m.alcantarillado,0)) as total6
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%m-%Y')='$mes' 
              and c.id_apr=$id_apr  and c.estado =1 and m.cuota_socio!=0
              group by dia,glosa 
        UNION
              select date_format(c.fecha,'%m-%Y') as dia , 
              'ALCANTARILLADO $mes' as glosa,
              '' as total ,'' as total2 , ''as total3 , '' as total4,sum(ifnull(m.alcantarillado,0))as total5,sum(ifnull(m.alcantarillado,0)) as total6
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%m-%Y')='$mes' 
              and c.id_apr=$id_apr  and c.estado =1 and m.alcantarillado !=0
              group by dia,glosa 
        UNION
            select date_format(c.fecha,'%m-%Y') as dia , 
            'MULTAS $mes' as glosa,
            '' as total ,'' as total2 , ''as total3 , '' as total4,sum(ifnull(m.multa,0))as total5,sum(ifnull(m.multa,0))as total6
            from caja c
            inner join caja_detalle d on d.id_caja=c.id
            inner join metros m on m.id=d.id_metros
            where date_format(c.fecha,'%m-%Y')='$mes' 
            and c.id_apr=$id_apr  and c.estado =1 and m.multa !=0
            group by dia,glosa 
        UNION
          select date_format(es.fecha,'%d-%m') as dia,
                    concat(te.tipo_egreso,' (Por Egresos Simple)') as glosa,
                    '' as total,
                    '' as total2,
                    '' as total3,
                    sum(es.monto) as total4,
                    '' as total5,
                     sum(es.monto) as total6                    
                     from  
              egresos_simples es
                left join cuentas c on es.id_cuenta = c.id
                left join bancos b on c.id_banco = b.id
                left join banco_tipo_cuenta btc on c.id_tipo_cuenta = btc.id
                inner join motivos m on es.id_motivo = m.id
                            inner join tipos_egreso te on es.id_tipo_egreso = te.id
                            inner join egresos e on es.id_egreso = e.id
                             where e.id_apr=$id_apr 
              and date_format(es.fecha,'%m-%Y')='$mes' 
              and e.estado=1  and btc.id not in (5,6)
              group by dia,te.tipo_egreso   
              order by dia,glosa asc";

      $query = $db->query($consulta);
      $result  = $query->getResultArray();

      // print_r($result);
      // exit();

      $sheet = $objPHPExcel->getActiveSheet();

      $sheet->fromArray($result, NULL, 'A5');

      $Inicio = 5;
      $inicioFila=count($result)+$Inicio;
      $A = 'A';
      $B = 'B';
      $C = 'C';
      $D = 'D';
      $E = 'E';
      $F = 'F';
      $G = 'G';
      $H = 'H';
      $I = 'I';
      // $posicionInicio = $inicioColumna . $inicioFila;
      $menor=$inicioFila-1;
      $formulaC = "=SUM(C5:C".$menor.")";
      $formulaD = "=SUM(D5:D".$menor.")";
      $formulaE = "=SUM(E5:E".$menor.")";
      $formulaF = "=SUM(F5:F".$menor.")";
      $formulaG = "=SUM(G5:G".$menor.")";
      $formulaH = "=SUM(H5:H".$menor.")";
      
      $objPHPExcel->getActiveSheet()->setCellValue($C.$inicioFila,$formulaC );
      $objPHPExcel->getActiveSheet()->setCellValue($D.$inicioFila,$formulaD );
      $objPHPExcel->getActiveSheet()->setCellValue($E.$inicioFila,$formulaE );
      $objPHPExcel->getActiveSheet()->setCellValue($F.$inicioFila,$formulaF );
      $objPHPExcel->getActiveSheet()->setCellValue($G.$inicioFila,$formulaG );
      $objPHPExcel->getActiveSheet()->setCellValue($H.$inicioFila,$formulaH );


       $objPHPExcel->getActiveSheet()->getStyle('A4:H'.$inicioFila)->applyFromArray($borderStyle,false);

      $objPHPExcel->getActiveSheet()->mergeCells('L2:U2');
      $objPHPExcel->getActiveSheet()->setCellValue('L2', 'EGRESOS '.$mes);
      $estiloCelda = $objPHPExcel->getActiveSheet()->getStyle('L2');
      $estiloCelda->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $estiloCelda->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->setCellValue('L4', 'FECHA');
      $objPHPExcel->getActiveSheet()->setCellValue('M4', 'DETALLE');
      $objPHPExcel->getActiveSheet()->setCellValue('N4', 'GASTO OPERACION');
      $objPHPExcel->getActiveSheet()->setCellValue('O4', 'GASTO ADMINISTRATIVO');
      $objPHPExcel->getActiveSheet()->setCellValue('P4', 'GASTO MANTENCION');
      $objPHPExcel->getActiveSheet()->setCellValue('Q4', 'GASTO MEJORAMIENTO');
      $objPHPExcel->getActiveSheet()->setCellValue('R4', 'OTROS');
      $objPHPExcel->getActiveSheet()->setCellValue('S4', 'BANCO DEPOSITO');
      $objPHPExcel->getActiveSheet()->setCellValue('T4', 'IVA');
      $objPHPExcel->getActiveSheet()->setCellValue('U4', 'TOTAL');


      $consulta2 = "SELECT date_format(es.fecha,'%d-%m') as dia,
              te.tipo_egreso, sum(es.monto) as total1,
              '' as total2,
              '' as total3,
              '' as total4,
              '' as total5,
              '' as total6,
              '' as total7,
              sum(es.monto) as total8
              from egresos_simples es
              inner join egresos e on e.id=es.id_egreso
              inner join tipos_egreso te on te.id=es.id_tipo_egreso
              inner join tipo_gasto tg on tg.id=es.tipo_gasto
              where es.tipo_gasto=1 and e.id_apr=$id_apr and 
              date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1
              group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    sum(es.monto) as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=2 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    sum(es.monto) as total3,
                    '' as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=3 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    sum(es.monto) as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=4 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    sum(es.monto) as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=5 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    sum(es.monto) as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=6 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(c.fecha,'%d-%m-%Y') as dia , 
                    'PAGO AGUA POTABLE WEBPAY/TRANSFERENCIAS' as glosa,
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as total6,
                    '' as total7,
                    sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as  total8

                    from caja c 
                    inner join caja_detalle d on d.id_caja=c.id
                    inner join metros m on m.id=d.id_metros where c.id_forma_pago in (3,4) 
                    and date_format(c.fecha,'%m-%Y')='$mes'
                    and c.id_apr=$id_apr 
                    and c.estado =1
                    group by dia,glosa";

                    // echo $consulta2;
                    // exit();

      $query2 = $db->query($consulta2);
      $result2  = $query2->getResultArray();

      $sheet2 = $objPHPExcel->getActiveSheet();

      $sheet2->fromArray($result2, NULL, 'L5');

      $Inicio2 = 5;
      $inicioFila2=count($result2)+$Inicio2;
     
      $L='L';
      $M='M';
      $N='N';
      $O='O';
      $P='P';
      $Q='Q';
      $R='R';
      $S='S';
      $T='T';
      $U='U';
      // $posicionInicio = $inicioColumna . $inicioFila;
      $menor2=$inicioFila2-1;
      $formulaL = "=SUM(L5:L".$menor2.")";
      $formulaM = "=SUM(M5:M".$menor2.")";
      $formulaN = "=SUM(N5:N".$menor2.")";
      $formulaO = "=SUM(O5:O".$menor2.")";
      $formulaP = "=SUM(P5:P".$menor2.")";
      $formulaQ = "=SUM(Q5:Q".$menor2.")";
      $formulaR = "=SUM(R5:R".$menor2.")";
      $formulaS = "=SUM(S5:S".$menor2.")";
      $formulaT = "=SUM(T5:T".$menor2.")";
      $formulaU = "=SUM(U5:U".$menor2.")";
      
      // $objPHPExcel->getActiveSheet()->setCellValue($L.$inicioFila2,$formulaL );
      // $objPHPExcel->getActiveSheet()->setCellValue($M.$inicioFila2,$formulaM );
      $objPHPExcel->getActiveSheet()->setCellValue($N.$inicioFila2,$formulaN );
      $objPHPExcel->getActiveSheet()->setCellValue($O.$inicioFila2,$formulaO );
      $objPHPExcel->getActiveSheet()->setCellValue($P.$inicioFila2,$formulaP );
      $objPHPExcel->getActiveSheet()->setCellValue($Q.$inicioFila2,$formulaQ );
      $objPHPExcel->getActiveSheet()->setCellValue($R.$inicioFila2,$formulaR );
      $objPHPExcel->getActiveSheet()->setCellValue($S.$inicioFila2,$formulaS );
      $objPHPExcel->getActiveSheet()->setCellValue($T.$inicioFila2,$formulaT );
      $objPHPExcel->getActiveSheet()->setCellValue($U.$inicioFila2,$formulaU );


       $objPHPExcel->getActiveSheet()->getStyle('L4:U'.$inicioFila2)->applyFromArray($borderStyle,false);

       $objPHPExcel->getActiveSheet()->getStyle('L4:U4')->getAlignment()->setWrapText(true);
       $columnas = range('L4', 'U4');

        foreach ($columnas as $columna) {
              $objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
        } 

       header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
       header('Content-Disposition: attachment;filename="Libro de caja.xlsx"'); 

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
      $writer->save('php://output');


  }

  public function buscar_datos_arqueo($mes){
    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;

    $ingresos="SELECT sum(total)  as ingresos from (SELECT date_format(c.fecha,'%d-%m-%Y') as dia , 
        'PAGO CONSUMO MES AGUA POTABLE' as glosa,
        sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0)) 
        as total ,'' as total2 , '' as total3, '' as total4,'' as total5,sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as total6
        from caja c
        inner join caja_detalle d on d.id_caja=c.id
        inner join metros m on m.id=d.id_metros
        where date_format(c.fecha,'%m-%Y')='$mes' 
        and c.id_apr=$id_apr and c.estado =1
        group by dia,glosa 
        UNION
              select date_format(c.fecha,'%m-Y') as dia , 
              'CUOTA SOCIO $mes ' as glosa,
              ' ' as total ,sum(ifnull(m.alcantarillado,0)) , ''as total3 , ''as total4,''as total5 ,sum(ifnull(m.alcantarillado,0)) as total6
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%m-%Y')='$mes' 
              and c.id_apr=$id_apr and c.estado =1 and m.cuota_socio!=0
              group by dia,glosa 
        UNION
              select date_format(c.fecha,'%m-%Y') as dia , 
              'ALCANTARILLADO $mes' as glosa,
              '' as total ,'' as total2 , ''as total3 , '' as total4,sum(ifnull(m.alcantarillado,0))as total5,sum(ifnull(m.alcantarillado,0)) as total6
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%m-%Y')='$mes' 
              and c.id_apr=$id_apr and c.estado =1 and m.alcantarillado !=0
              group by dia,glosa 
        UNION
            select date_format(c.fecha,'%m-%Y') as dia , 
            'MULTAS $mes' as glosa,
            '' as total ,'' as total2 , ''as total3 , '' as total4,sum(ifnull(m.multa,0))as total5,sum(ifnull(m.multa,0))as total6
            from caja c
            inner join caja_detalle d on d.id_caja=c.id
            inner join metros m on m.id=d.id_metros
            where date_format(c.fecha,'%m-%Y')='$mes' 
            and c.id_apr=$id_apr and c.estado =1 and m.multa !=0
            group by dia,glosa 
        UNION
          select date_format(es.fecha,'%d-%m') as dia,
                    concat(te.tipo_egreso,' (Por Egresos Simple)') as glosa,
                    '' as total,
                    '' as total2,
                    '' as total3,
                    sum(es.monto) as total4,
                    '' as total5,
                     sum(es.monto) as total6                    
                     from  
              egresos_simples es
                left join cuentas c on es.id_cuenta = c.id
                left join bancos b on c.id_banco = b.id
                left join banco_tipo_cuenta btc on c.id_tipo_cuenta = btc.id
                inner join motivos m on es.id_motivo = m.id
                            inner join tipos_egreso te on es.id_tipo_egreso = te.id
                            inner join egresos e on es.id_egreso = e.id
                             where e.id_apr=$id_apr
              and date_format(es.fecha,'%m-%Y')='$mes' 
              and e.estado=1  and btc.id not in (5,6)
              group by dia,te.tipo_egreso   
              order by dia,glosa asc) algo";

              $egresos="SELECT sum(total8) as egresos from(
                SELECT date_format(es.fecha,'%d-%m') as dia,
              te.tipo_egreso, sum(es.monto) as total1,
              '' as total2,
              '' as total3,
              '' as total4,
              '' as total5,
              '' as total6,
              '' as total7,
              sum(es.monto) as total8
              from egresos_simples es
              inner join egresos e on e.id=es.id_egreso
              inner join tipos_egreso te on te.id=es.id_tipo_egreso
              inner join tipo_gasto tg on tg.id=es.tipo_gasto
              where es.tipo_gasto=1 and e.id_apr=$id_apr and 
              date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1
              group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    sum(es.monto) as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=2 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    sum(es.monto) as total3,
                    '' as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=3 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    sum(es.monto) as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=4 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    sum(es.monto) as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=5 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(es.fecha,'%d-%m') as dia,
                    te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    sum(es.monto) as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=6 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%m-%Y')='$mes' and e.estado=1 
                    group by dia,tipo_egreso
              UNION
                    select date_format(c.fecha,'%d-%m-%Y') as dia , 
                    'PAGO AGUA POTABLE WEBPAY/TRANSFERENCIAS' as glosa,
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as total6,
                    '' as total7,
                    sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as  total8

                    from caja c 
                    inner join caja_detalle d on d.id_caja=c.id
                    inner join metros m on m.id=d.id_metros where c.id_forma_pago in (3,4) 
                    and date_format(c.fecha,'%m-%Y')='$mes'
                    and c.id_apr=$id_apr 
                    and c.estado =1
                    group by dia,glosa
              ) egresos";

              $query2 = $this->db->query($egresos);
              $result2  = $query2->getResultArray();

              // echo $ingresos;

              $query = $this->db->query($ingresos);
              $result  = $query->getResultArray();

              // echo($result[0]['ingresos']);

               $meses=explode('-',$mes);
               $ano=$meses[1];
               $mes2=$meses[0]-1;

               if($mes2<10){
                $mes2='0'.$mes2;
               }

              $mes_anterior=$mes2.'-'.$ano;

              $anterior="SELECT saldo_periodo as anterior FROM arqueo_caja where date_format(mes, '%m-%Y') = '$mes_anterior' ";

              // echo $anterior;

              $query3 = $this->db->query($anterior);
              $result3  = $query3->getResultArray();



              $banco="SELECT saldo_banco as anterior FROM arqueo_caja where date_format(mes, '%m-%Y') = '$mes_anterior' ";

              // echo $anterior;
              $query4 = $this->db->query($banco);
              $result4  = $query4->getResultArray();

              if($result4[0]['anterior']==""){
                //$result4[0]['anterior']=0;
                $saldoBanco=0;
              }else{

                  $sqlegre="SELECT sum(es.monto) as total4                                        
                    from  
                    egresos_simples es
                    left join cuentas c on es.id_cuenta = c.id
                    left join bancos b on c.id_banco = b.id
                    left join banco_tipo_cuenta btc on c.id_tipo_cuenta = btc.id
                    inner join motivos m on es.id_motivo = m.id
                    inner join tipos_egreso te on es.id_tipo_egreso = te.id
                    inner join egresos e on es.id_egreso = e.id
                    where  e.id_apr=$id_apr
                    and  date_format(es.fecha,'%m-%Y')='$mes' 
                    and e.estado=1  and btc.id not in (5,6)";

                    $query5 = $this->db->query($sqlegre);
                    $result5  = $query5->getResultArray();

                    if($result5[0]['total4']==""){
                      $egresoBanco=0;
                    }else{
                      $egresoBanco=$result5[0]['total4'];
                    }


                    $sqlingre="SELECT 
                    sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as total6
                    from caja c 
                    inner join caja_detalle d on d.id_caja=c.id
                    inner join metros m on m.id=d.id_metros where c.id_forma_pago in (3,4) 
                    and date_format(c.fecha,'%m-%Y')='$mes'
                    and c.id_apr=$id_apr
                    and c.estado =1";

                    $query6 = $this->db->query($sqlingre);
                    $result6  = $query6->getResultArray();

                    if($result6[0]['total6']==""){
                      $ingresoBanco=0;
                    }else{
                      $ingresoBanco=$result6[0]['total6'];
                    }

                    $saldoBanco= $result4[0]['anterior'] + $ingresoBanco - $egresoBanco;
                       //else{
                      //   $saldoBanco= $result4[0]['anterior'] - $result5[0]['total4'];
                      // }

              }




              if($result[0]['ingresos']==""){
                $result[0]['ingresos']=0;
              }

              if($result[0]['egresos']==""){
                $result[0]['egresos']=0;
              }

              if($result[0]['anterior']==""){
                $result[0]['anterior']=0;
              }

               $row = [
                 "ingresos"  => $result[0]['ingresos'],
                 "egresos"    => $result2[0]['egresos'],
                 "saldo_anterior"    => $result3[0]['anterior'],
                 "saldo_banco"        =>$saldoBanco
                ];

              $data[] = $row;

               if (isset($data)) {
                $salida = ["data" => $data];

                return json_encode($salida);
              } else {
                $salida = ["data" => ""];

                return json_encode($salida);
              }
  }

  public function guarda_arqueo(){
    $this->validar_sesion();
    $fecha_genera      = date("Y-m-d H:i:s");
    $usuario_genera = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $mes = $this->request->getPost("dt_mes");
    $ingreos= $this->request->getPost("txt_ingresos");
    $egresos= $this->request->getPost("txt_egresos");
    $saldo_periodo= $this->request->getPost("txt_saldo_periodo");
    $saldo_anterior= $this->request->getPost("txt_saldo_anterior");
    $saldo_siguiente= $this->request->getPost("txt_saldo_siguiente");
    $saldo_banco= $this->request->getPost("txt_saldo_banco");
    $saldo_deposito= $this->request->getPost("txt_saldo_deposito");
    $total_fondos= $this->request->getPost("txt_total_fondos");

    $datosAqueo=[
        "mes"=>date_format(date_create('01-'.$mes), 'Y-m-d'),
        "ingreos"=>$ingreos,
        "egresos"=>$egresos,
        "saldo_periodo"=>$saldo_periodo,
        "saldo_anterior"=>$saldo_anterior,
        "saldo_siguiente"=>$saldo_siguiente,
        "saldo_banco"=>$saldo_banco,
        "saldo_deposito"=>$saldo_deposito,
        "total_fondos"=>$total_fondos,
        "fecha_reg"=>date_format(date_create($fecha_genera), 'Y-m-d'),
        "usu_reg"=>$usuario_genera,
        'id_apr'=>$id_apr,
        "estado"=>1
    ];

    if ($this->arqueo_caja->existe_arqueo($mes, $id_apr)) {
      echo "YA POSEE UN ARQUEO PARA ESTE MES";
      exit();
    }

    if ($this->arqueo_caja->save($datosAqueo)) {
        echo 1;
     }else{
        echo "Error al generar la liquidacion";
     }    
  }

  public function datatable_arqueo() {
    $this->validar_sesion();
    echo $this->arqueo_caja->datatable_arqueo($this->db, $this->sesión->id_apr_ses);
  }


  public function anula_arqueo($id){
    
      $datosArqueo=[
         "id" => $id,
         "estado"=>0
      ];

      if ($this->arqueo_caja->save($datosArqueo)) {
        echo 1;
     }else{
        echo "Error al anular arqueo";
     }


  }



   public function reporte_anual($ano){

    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;
    $nombre_apr=$this->sesión->apr_ses;
    $db=$this->db;
   
    // echo $mes;
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setTitle('Libro de Caja');


     
      //A5 I5
      $objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
      $objPHPExcel->getActiveSheet()->setCellValue('A2', 'INGRESOS '.$mes);
      $estiloCelda = $objPHPExcel->getActiveSheet()->getStyle('A2');
      $estiloCelda->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $estiloCelda->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'FECHA');
      // $objPHPExcel->getActiveSheet()->setCellValue('B4', 'DETALLE');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'CONSUMO AGUA NETO TODAS LAS TRANSACC. DIARIAS');
      $objPHPExcel->getActiveSheet()->setCellValue('C4', 'DERECHO INCORPOR. MENSUAL');
      $objPHPExcel->getActiveSheet()->setCellValue('D4', 'IVA');
      $objPHPExcel->getActiveSheet()->setCellValue('E4', 'BANCO GIROS MENSUAL');
      $objPHPExcel->getActiveSheet()->setCellValue('F4', 'OTROS INGRESOS MENSUAL');
      $objPHPExcel->getActiveSheet()->setCellValue('G4', 'TOTAL');
      $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setWrapText(true);

      $columnas = range('A4', 'G4');

       $borderStyle=[
              'borders' => [
                'bottom' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'top' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'right' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
                'left' => ['borderStyle' => 'medium', 'color' => ['argb' => 'FF000000']],
              ],
      ];

      $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($borderStyle,false);

      foreach ($columnas as $columna) {
          $objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
      } 

        $consulta = "SELECT 
    dia,
    SUM(`CONSUMO AGUA NETO TODAS LAS TRANSACC. DIARIAS`) AS `CONSUMO AGUA NETO TODAS LAS TRANSACC. DIARIAS`,
    SUM(`DERECHO INCORPOR. MENSUAL`) AS `DERECHO INCORPOR. MENSUAL`,
    SUM(`IVA`) AS `IVA`,
    SUM(`BANCO GIROS MENSUAL`) AS `BANCO GIROS MENSUAL`,
    SUM(`OTROS INGRESOS MENSUAL`) AS `OTROS INGRESOS MENSUAL`,
    SUM(`TOTAL`) AS `TOTAL`
FROM (
    SELECT 
        DATE_FORMAT(c.fecha, '%m-%Y') AS dia,
        SUM(c.total_pagar - IFNULL(m.alcantarillado, 0) - IFNULL(m.cuota_socio, 0) - IFNULL(m.multa, 0)) AS 'CONSUMO AGUA NETO TODAS LAS TRANSACC. DIARIAS',
        0 AS 'DERECHO INCORPOR. MENSUAL',
        0 AS 'IVA',
        0 AS 'BANCO GIROS MENSUAL',
        0 AS 'OTROS INGRESOS MENSUAL',
        SUM(c.total_pagar - IFNULL(m.alcantarillado, 0) - IFNULL(m.cuota_socio, 0) - IFNULL(m.multa, 0)) AS 'TOTAL'
    FROM
        caja c
        INNER JOIN caja_detalle d ON d.id_caja = c.id
        INNER JOIN metros m ON m.id = d.id_metros
    WHERE
        DATE_FORMAT(c.fecha, '%Y') = '$ano'
        AND c.id_apr = $id_apr
        AND c.estado = 1
    GROUP BY dia  
    UNION ALL
    SELECT 
        DATE_FORMAT(c.fecha, '%m-%Y') AS dia,
        0 AS 'CONSUMO AGUA NETO TODAS LAS TRANSACC. DIARIAS',
        SUM(IFNULL(m.alcantarillado, 0)),
        0 AS 'IVA',
        0 AS 'BANCO GIROS MENSUAL',
        0 AS 'OTROS INGRESOS MENSUAL',
        SUM(IFNULL(m.alcantarillado, 0)) AS 'TOTAL'
    FROM
        caja c
        INNER JOIN caja_detalle d ON d.id_caja = c.id
        INNER JOIN metros m ON m.id = d.id_metros
    WHERE
        DATE_FORMAT(c.fecha, '%Y') = '$ano'
        AND c.id_apr = $id_apr
        AND c.estado = 1
        AND m.cuota_socio != 0
    GROUP BY dia  
UNION 
        SELECT 
            DATE_FORMAT(c.fecha, '%m-Y') AS dia,
            ' ' AS total,
            SUM(IFNULL(m.alcantarillado, 0)),
            '' AS total3,
            '' AS total4,
            '' AS total5,
            SUM(IFNULL(m.alcantarillado, 0)) AS total6
        FROM
            caja c
                INNER JOIN
            caja_detalle d ON d.id_caja = c.id
                INNER JOIN
            metros m ON m.id = d.id_metros
        WHERE
            DATE_FORMAT(c.fecha, '%Y') = '$ano'
                AND c.id_apr = $id_apr
                AND c.estado = 1
                AND m.cuota_socio != 0
        GROUP BY dia  
        UNION 
        SELECT 
            DATE_FORMAT(c.fecha, '%m-%Y') AS dia,
            '' AS total,
            '' AS total2,
            '' AS total3,
            '' AS total4,
            SUM(IFNULL(m.alcantarillado, 0)) AS total5,
            SUM(IFNULL(m.alcantarillado, 0)) AS total6
        FROM
            caja c
                INNER JOIN
            caja_detalle d ON d.id_caja = c.id
                INNER JOIN
            metros m ON m.id = d.id_metros
        WHERE
            DATE_FORMAT(c.fecha, '%Y') = '$ano'
                AND c.id_apr = $id_apr
                AND c.estado = 1
                AND m.alcantarillado != 0
        GROUP BY dia 
        UNION 
        SELECT 
            DATE_FORMAT(c.fecha, '%m-%Y') AS dia,
            '' AS total,
            '' AS total2,
            '' AS total3,
            '' AS total4,
            SUM(IFNULL(m.multa, 0)) AS total5,
            SUM(IFNULL(m.multa, 0)) AS total6
        FROM
            caja c
                INNER JOIN
            caja_detalle d ON d.id_caja = c.id
                INNER JOIN
            metros m ON m.id = d.id_metros
        WHERE
            DATE_FORMAT(c.fecha, '%Y') = '$ano'
                AND c.id_apr = $id_apr
                AND c.estado = 1
                AND m.multa != 0
        GROUP BY dia  
        UNION 
        SELECT 
            DATE_FORMAT(es.fecha, '%m-%Y') AS dia,
            '' AS total,
            '' AS total2,
            '' AS total3,
            SUM(es.monto) AS total4,
            '' AS total5,
            SUM(es.monto) AS total6
        FROM
            egresos_simples es
                LEFT JOIN
            cuentas c ON es.id_cuenta = c.id
                LEFT JOIN
            bancos b ON c.id_banco = b.id
                LEFT JOIN
            banco_tipo_cuenta btc ON c.id_tipo_cuenta = btc.id
                INNER JOIN
            motivos m ON es.id_motivo = m.id
                INNER JOIN
            tipos_egreso te ON es.id_tipo_egreso = te.id
                INNER JOIN
            egresos e ON es.id_egreso = e.id
        WHERE
            e.id_apr = $id_apr
                AND DATE_FORMAT(es.fecha, '%Y') = '$ano'
                AND e.estado = 1
                AND btc.id NOT IN (5 , 6)
        GROUP BY dia , te.tipo_egreso
) AS resultado
GROUP BY dia
ORDER BY dia ASC";

      $query = $db->query($consulta);
      $result  = $query->getResultArray();


      // foreach ($result as $row) {
      //     $dia = $row['dia'];
      //     unset($row['dia']); // Elimina la columna de los días

      //     // Organiza los datos por día
      //     $result[$dia] = $row;
      // }

// Imprime los resultados organizados
// echo "<pre>";
// print_r($results);
// exit();

      $sheet = $objPHPExcel->getActiveSheet();

      $sheet->fromArray($result, NULL, 'A5');

      $Inicio = 5;
      $inicioFila=count($result)+$Inicio;
      $A = 'A';
      $B = 'B';
      $C = 'C';
      $D = 'D';
      $E = 'E';
      $F = 'F';
      $G = 'G';
      // $H = 'H';
      // $I = 'I';
      // $posicionInicio = $inicioColumna . $inicioFila;
      $menor=$inicioFila-1;
      $formulaB = "=SUM(B5:B".$menor.")";
      $formulaC = "=SUM(C5:C".$menor.")";
      $formulaD = "=SUM(D5:D".$menor.")";
      $formulaE = "=SUM(E5:E".$menor.")";
      $formulaF = "=SUM(F5:F".$menor.")";
      $formulaG = "=SUM(G5:G".$menor.")";
      // $formulaH = "=SUM(H5:H".$menor.")";
      
      $objPHPExcel->getActiveSheet()->setCellValue($B.$inicioFila,$formulaB );
      $objPHPExcel->getActiveSheet()->setCellValue($C.$inicioFila,$formulaC );
      $objPHPExcel->getActiveSheet()->setCellValue($D.$inicioFila,$formulaD );
      $objPHPExcel->getActiveSheet()->setCellValue($E.$inicioFila,$formulaE );
      $objPHPExcel->getActiveSheet()->setCellValue($F.$inicioFila,$formulaF );
      $objPHPExcel->getActiveSheet()->setCellValue($G.$inicioFila,$formulaG );
      // $objPHPExcel->getActiveSheet()->setCellValue($H.$inicioFila,$formulaH );

       $fila_resumenB=20;

       // $objPHPExcel->getActiveSheet()->mergeCells('L2:T2');

           
      $objPHPExcel->getActiveSheet()->getStyle('A20:B25')->applyFromArray($borderStyle,false);


       $objPHPExcel->getActiveSheet()->setCellValue('A20','CONSUMO AGUA NETO' );
       $objPHPExcel->getActiveSheet()->setCellValue('A21','DERECHO INCORPOR.' );
       $objPHPExcel->getActiveSheet()->setCellValue('A22','IVA' );
       $objPHPExcel->getActiveSheet()->setCellValue('A23','BANCO GIROS' );
       $objPHPExcel->getActiveSheet()->setCellValue('A24','OTROS INGRESOS' );
       $objPHPExcel->getActiveSheet()->setCellValue('A25','TOTAL' );
      
       $objPHPExcel->getActiveSheet()->setCellValue('B20',$formulaB );
       $objPHPExcel->getActiveSheet()->setCellValue('B21',$formulaC );
       $objPHPExcel->getActiveSheet()->setCellValue('B22',$formulaD );
       $objPHPExcel->getActiveSheet()->setCellValue('B23',$formulaE );
       $objPHPExcel->getActiveSheet()->setCellValue('B24',$formulaF );
       $objPHPExcel->getActiveSheet()->setCellValue('B25',$formulaG );


       $objPHPExcel->getActiveSheet()->getStyle('A4:G'.$inicioFila)->applyFromArray($borderStyle,false);

      $objPHPExcel->getActiveSheet()->mergeCells('L2:T2');
      $objPHPExcel->getActiveSheet()->setCellValue('L2', 'EGRESOS '.$mes);
      $estiloCelda = $objPHPExcel->getActiveSheet()->getStyle('L2');
      $estiloCelda->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $estiloCelda->getAlignment()->setWrapText(true); 

      $objPHPExcel->getActiveSheet()->setCellValue('L4', 'FECHA');
      // $objPHPExcel->getActiveSheet()->setCellValue('M4', 'DETALLE');
      $objPHPExcel->getActiveSheet()->setCellValue('M4', 'GASTO OPERACION');
      $objPHPExcel->getActiveSheet()->setCellValue('N4', 'GASTO ADMINISTRATIVO');
      $objPHPExcel->getActiveSheet()->setCellValue('O4', 'GASTO MANTENCION');
      $objPHPExcel->getActiveSheet()->setCellValue('P4', 'GASTO MEJORAMIENTO');
      $objPHPExcel->getActiveSheet()->setCellValue('Q4', 'OTROS');
      $objPHPExcel->getActiveSheet()->setCellValue('R4', 'BANCO DEPOSITO');
      $objPHPExcel->getActiveSheet()->setCellValue('S4', 'IVA');
      $objPHPExcel->getActiveSheet()->setCellValue('T4', 'TOTAL');


      $consulta2 = "SELECT 
    dia,
    SUM(`GASTO OPERACION`) AS `GASTO OPERACION`,
    SUM(`GASTO ADMINISTRATIVO`) AS `GASTO ADMINISTRATIVO`,
    SUM(`GASTO MANTENCION`) AS `GASTO MANTENCION`,
    SUM(`GASTO MEJORAMIENTO`) AS `GASTO MEJORAMIENTO`,
    SUM(`OTROS`) AS `OTROS`,
    SUM(`BANCO DEPOSITO`) AS `BANCO DEPOSITO`,
    SUM(`IVA`) AS `IVA`,
    SUM(`TOTAL`) AS `TOTAL`

FROM (
SELECT date_format(es.fecha,'%m-%Y') as dia,
              -- te.tipo_egreso,
              sum(es.monto) as 'GASTO OPERACION',
              '' as 'GASTO ADMINISTRATIVO',
              '' as 'GASTO MANTENCION',
              '' as 'GASTO MEJORAMIENTO',
              '' as 'OTROS',
              '' as 'BANCO DEPOSITO',
              '' as 'IVA',
              sum(es.monto) as 'TOTAL'
              from egresos_simples es
              inner join egresos e on e.id=es.id_egreso
              inner join tipos_egreso te on te.id=es.id_tipo_egreso
              inner join tipo_gasto tg on tg.id=es.tipo_gasto
              where es.tipo_gasto=1 and e.id_apr=$id_apr and 
              date_format(es.fecha,'%Y')='$ano' and e.estado=1
              group by dia
              UNION
                    select date_format(es.fecha,'%m-%Y') as dia,
                   -- te.tipo_egreso, 
                    '' as total1,
                    sum(es.monto) as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=2 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%Y')='$ano' and e.estado=1 
                    group by dia
              UNION
                    select date_format(es.fecha,'%m-%Y') as dia,
                   -- te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    sum(es.monto) as total3,
                    '' as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=3 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%Y')='$ano' and e.estado=1 
                    group by dia
              UNION
                    select date_format(es.fecha,'%m-%Y') as dia,
                  --  te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    sum(es.monto) as total4,
                    '' as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=4 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%Y')='$ano' and e.estado=1 
                    group by dia
              UNION
                    select date_format(es.fecha,'%m-%Y') as dia,
                   -- te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    sum(es.monto) as total5,
                    '' as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=5 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%Y')='$ano' and e.estado=1 
                    group by dia
              UNION
                    select date_format(es.fecha,'%m-%Y') as dia,
                   -- te.tipo_egreso, 
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    sum(es.monto) as total6,
                    '' as total7,
                    sum(es.monto) as total8
                    from egresos_simples es
                    inner join egresos e on e.id=es.id_egreso
                    inner join tipos_egreso te on te.id=es.id_tipo_egreso
                    inner join tipo_gasto tg on tg.id=es.tipo_gasto
                    where es.tipo_gasto=6 and e.id_apr=$id_apr and 
                    date_format(es.fecha,'%Y')='$ano' and e.estado=1 
                    group by dia
              UNION
                    select date_format(c.fecha,'%m-%Y') as dia , 
                   -- 'PAGO AGUA POTABLE WEBPAY/TRANSFERENCIAS' as glosa,
                    '' as total1,
                    '' as total2,
                    '' as total3,
                    '' as total4,
                    '' as total5,
                    sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as total6,
                    '' as total7,
                    sum(c.total_pagar-ifnull(m.alcantarillado,0)-ifnull(m.cuota_socio,0)-ifnull(m.multa,0))  as  total8

                    from caja c 
                    inner join caja_detalle d on d.id_caja=c.id
                    inner join metros m on m.id=d.id_metros where c.id_forma_pago in (3,4) 
                    and date_format(c.fecha,'%Y')='$ano'
                    and c.id_apr=$id_apr 
                    and c.estado =1
                    group by dia) as resultado
                    GROUP BY dia
                ORDER BY dia ASC";

                    // echo $consulta2;
                    // exit();

      $query2 = $db->query($consulta2);
      $result2  = $query2->getResultArray();

      $sheet2 = $objPHPExcel->getActiveSheet();

      $sheet2->fromArray($result2, NULL, 'L5');

      $Inicio2 = 5;
      $inicioFila2=count($result2)+$Inicio2;
     
      $L='L';
      $M='M';
      $N='N';
      $O='O';
      $P='P';
      $Q='Q';
      $R='R';
      $S='S';
      $T='T';
      // $U='U';
      // $posicionInicio = $inicioColumna . $inicioFila;
      $menor2=$inicioFila2-1;
      $formulaL = "=SUM(L5:L".$menor2.")";
      $formulaM = "=SUM(M5:M".$menor2.")";
      $formulaN = "=SUM(N5:N".$menor2.")";
      $formulaO = "=SUM(O5:O".$menor2.")";
      $formulaP = "=SUM(P5:P".$menor2.")";
      $formulaQ = "=SUM(Q5:Q".$menor2.")";
      $formulaR = "=SUM(R5:R".$menor2.")";
      $formulaS = "=SUM(S5:S".$menor2.")";
      $formulaT = "=SUM(T5:T".$menor2.")";
      // $formulaU = "=SUM(U5:U".$menor2.")"; 

      $objPHPExcel->getActiveSheet()->getStyle('L20:M27')->applyFromArray($borderStyle,false);

       $objPHPExcel->getActiveSheet()->setCellValue('L20','GASTO OPERACION' );
       $objPHPExcel->getActiveSheet()->setCellValue('L21','GASTO ADMINISTRATIVO' );
       $objPHPExcel->getActiveSheet()->setCellValue('L22','GASTO MANTENCION' );
       $objPHPExcel->getActiveSheet()->setCellValue('L23','GASTO MEJORAMIENTO' );
       $objPHPExcel->getActiveSheet()->setCellValue('L24','OTROS' );
       $objPHPExcel->getActiveSheet()->setCellValue('L25','BANCO DEPOSITO' );
       $objPHPExcel->getActiveSheet()->setCellValue('L26','IVA' );
       $objPHPExcel->getActiveSheet()->setCellValue('L27','TOTAL' );

       $objPHPExcel->getActiveSheet()->setCellValue('M20',$formulaM );
       $objPHPExcel->getActiveSheet()->setCellValue('M21',$formulaN );
       $objPHPExcel->getActiveSheet()->setCellValue('M22',$formulaO );
       $objPHPExcel->getActiveSheet()->setCellValue('M23',$formulaP );
       $objPHPExcel->getActiveSheet()->setCellValue('M24',$formulaQ );
       $objPHPExcel->getActiveSheet()->setCellValue('M25',$formulaR );
       $objPHPExcel->getActiveSheet()->setCellValue('M26',$formulaS );
       $objPHPExcel->getActiveSheet()->setCellValue('M27',$formulaT );
       

      
      // $objPHPExcel->getActiveSheet()->setCellValue($L.$inicioFila2,$formulaL );
      $objPHPExcel->getActiveSheet()->setCellValue($M.$inicioFila2,$formulaM );
      $objPHPExcel->getActiveSheet()->setCellValue($N.$inicioFila2,$formulaN );
      $objPHPExcel->getActiveSheet()->setCellValue($O.$inicioFila2,$formulaO );
      $objPHPExcel->getActiveSheet()->setCellValue($P.$inicioFila2,$formulaP );
      $objPHPExcel->getActiveSheet()->setCellValue($Q.$inicioFila2,$formulaQ );
      $objPHPExcel->getActiveSheet()->setCellValue($R.$inicioFila2,$formulaR );
      $objPHPExcel->getActiveSheet()->setCellValue($S.$inicioFila2,$formulaS );
      $objPHPExcel->getActiveSheet()->setCellValue($T.$inicioFila2,$formulaT );
      // $objPHPExcel->getActiveSheet()->setCellValue($U.$inicioFila2,$formulaU );


       $objPHPExcel->getActiveSheet()->getStyle('L4:T'.$inicioFila2)->applyFromArray($borderStyle,false);

       $objPHPExcel->getActiveSheet()->getStyle('L4:T4')->getAlignment()->setWrapText(true);
       $columnas = range('L4', 'T4');

        foreach ($columnas as $columna) {
              $objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
        } 

       header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
       header('Content-Disposition: attachment;filename="Libro de caja.xlsx"'); 

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
      $writer->save('php://output');


  }

  
}

?>