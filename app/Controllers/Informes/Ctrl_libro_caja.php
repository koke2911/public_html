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

  
}

?>