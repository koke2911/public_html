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

      $m1=$ano.'-01';
      $m2=$ano.'-02';
      $m3=$ano.'-03';

      $in="'01-".$ano."','02-".$ano."','03-".$ano."'";
    }

    if($trimestre==2){
      $periodo='01/04/'.$ano.' al 30/06/'.$ano;
      $mes1='Abril';
      $mes2='Mayo';
      $mes3='Junio';

      $m1=$ano.'-04';
      $m2=$ano.'-05';
      $m3=$ano.'-06';

      $in="'04-".$ano."','05-".$ano."','06-".$ano."'";
    }

    if($trimestre==3){
      $periodo='01/07/'.$ano.' al 30/09/'.$ano;
      $mes1='Julio';
      $mes2='Agosto';
      $mes3='Septiembre';

      $m1=$ano.'-07';
      $m2=$ano.'-08';
      $m3=$ano.'-09'; 

      $in="'07-".$ano."','08-".$ano."','09-".$ano."'";
    }

    if($trimestre==4){
      $periodo='01/10/'.$ano.' al 31/12/'.$ano;
      $mes1='Octubre';
      $mes2='Noviembre';
      $mes3='Diciembre';

      $m1=$ano.'-10';
      $m2=$ano.'-11';
      $m3=$ano.'-12';

      $in="'10-".$ano."','11-".$ano."','12-".$ano."'";
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
    
    $fecha = date('d-m-Y');
    
    $instrucciones->setCellValue('C3', $nombre_apr);
    $instrucciones->setCellValue('C4', $region);
    $instrucciones->setCellValue('C5', $provincia);
    $instrucciones->setCellValue('C6', $comuna);
    $instrucciones->setCellValue('C7', $periodo);
    $instrucciones->setCellValue('C8', $fecha);
    
    // ***************HOJA 1.- Financiero - Contable *************************************
    
    $HojaIngresos = '1.- Financiero - Contable';
    $spreadsheet->setActiveSheetIndexByName($HojaIngresos);
    $ingresos = $spreadsheet->getActiveSheet();
    
    $ingresos->setCellValue('B5', $mes1);
    $ingresos->setCellValue('C5', $mes2);
    $ingresos->setCellValue('D5', $mes3);
    

  // CONSUMO AP
    $consulta="SELECT 
              date_format(c.fecha,'%m-%Y') mes,
              sum(c.total_pagar-ifnull(m.cuota_socio,0)-ifnull(m.multa,0)-ifnull(m.monto_subsidio,0)-ifnull(m.cargo_fijo,0)) as total
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%m-%Y') in ($in) 
              and c.id_apr=$id_apr  and c.estado =1
               group by mes";

    $query = $db->query($consulta);
    $DatosConsumo  = $query->getResultArray();
    
    $comuna=$DatosConsumo[0]['total'];
 
    $ingresos->setCellValue('B7', $DatosConsumo[0]['total']);
    $ingresos->setCellValue('C7', $DatosConsumo[1]['total']);
    $ingresos->setCellValue('D7', $DatosConsumo[2]['total']);
   
    // CUOTA SOCIO
    $consulta="SELECT 
            date_format(c.fecha,'%m-%Y') as mes,
            sum(ifnull(m.cuota_socio,0))  as total
            from caja c 
            inner join caja_detalle d on d.id_caja=c.id 
            inner join metros m on m.id=d.id_metros 
            where date_format(c.fecha,'%m-%Y') in ($in) 
            and c.id_apr=$id_apr and c.estado =1 group by mes";

    $query = $db->query($consulta);
    $DatosCuota  = $query->getResultArray(); 

    $ingresos->setCellValue('B8', $DatosCuota[0]['total']);
    $ingresos->setCellValue('C8', $DatosCuota[1]['total']);
    $ingresos->setCellValue('D8', $DatosCuota[2]['total']);

    //INSTALACION ARRANQUE
      $ingresos->setCellValue('B9',0);
      $ingresos->setCellValue('C9',0);
      $ingresos->setCellValue('D9',0);

    //CORTE Y REPOSICION
      $ingresos->setCellValue('B10',0);
      $ingresos->setCellValue('C10',0);
      $ingresos->setCellValue('D10',0);

    //SUBSIDIOS

    $consulta="SELECT 
            date_format(c.fecha,'%m-%Y') as mes,
            sum(ifnull(m.monto_subsidio,0))  as total
            from caja c 
            inner join caja_detalle d on d.id_caja=c.id 
            inner join metros m on m.id=d.id_metros 
            where date_format(c.fecha,'%m-%Y') in ($in) 
            and c.id_apr=$id_apr and c.estado =1 group by mes";

    $query = $db->query($consulta);
    $DatosSubsidio  = $query->getResultArray(); 
     
    $ingresos->setCellValue('B11',$DatosSubsidio[0]['total']);
    $ingresos->setCellValue('C11',$DatosSubsidio[1]['total']);
    $ingresos->setCellValue('D11',$DatosSubsidio[2]['total']);

    // MULTAS

     //SUBSIDIOS

    $consulta="SELECT 
            date_format(c.fecha,'%m-%Y') as mes,
            sum(ifnull(m.multa,0))  as total
            from caja c 
            inner join caja_detalle d on d.id_caja=c.id 
            inner join metros m on m.id=d.id_metros 
            where date_format(c.fecha,'%m-%Y') in ($in) 
            and c.id_apr=$id_apr and c.estado =1 group by mes";

    $query = $db->query($consulta);
    $DatosMulta  = $query->getResultArray(); 
     
    $ingresos->setCellValue('B12',$DatosMulta[0]['total']);
    $ingresos->setCellValue('C12',$DatosMulta[1]['total']);
    $ingresos->setCellValue('D12',$DatosMulta[2]['total']);

    //CARGO FIJO

    $consulta="SELECT 
            date_format(c.fecha,'%m-%Y') as mes,
            sum(ifnull(m.cargo_fijo,0))  as total
            from caja c 
            inner join caja_detalle d on d.id_caja=c.id 
            inner join metros m on m.id=d.id_metros 
            where date_format(c.fecha,'%m-%Y') in ($in) 
            and c.id_apr=$id_apr and c.estado =1 group by mes";

    $query = $db->query($consulta);
    $DatosCargo  = $query->getResultArray(); 
     
    $ingresos->setCellValue('B27',$DatosCargo[0]['total']);
    $ingresos->setCellValue('C27',$DatosCargo[1]['total']);
    $ingresos->setCellValue('D27',$DatosCargo[2]['total']);

    //ALCANTARILLADO

    $consulta="SELECT 
            date_format(c.fecha,'%m-%Y') as mes,
            sum(ifnull(m.alcantarillado,0))  as total
            from caja c 
            inner join caja_detalle d on d.id_caja=c.id 
            inner join metros m on m.id=d.id_metros 
            where date_format(c.fecha,'%m-%Y') in ($in) 
            and c.id_apr=$id_apr and c.estado =1 group by mes";

    $query = $db->query($consulta);
    $DatosAlcantarillado  = $query->getResultArray(); 
     
    $ingresos->setCellValue('B28',$DatosAlcantarillado[0]['total']);
    $ingresos->setCellValue('C28',$DatosAlcantarillado[1]['total']);
    $ingresos->setCellValue('D28',$DatosAlcantarillado[2]['total']);

    // GASTOS MEJORAMIENTO

    $consulta="SELECT date_format(es.fecha,'%m-%Y') as mes,
				  sum(es.monto) as total             
				  from egresos_simples es
				  inner join egresos e on e.id=es.id_egreso
				  inner join tipos_egreso te on te.id=es.id_tipo_egreso
				  inner join tipo_gasto tg on tg.id=es.tipo_gasto
				  where es.tipo_gasto=4 and e.id_apr=$id_apr and 
				 date_format(es.fecha,'%m-%Y') in ($in) and e.estado=1
          group by mes";

    $query = $db->query($consulta);
    $DatosMejora  = $query->getResultArray(); 

    // print_r($DatosMejora);
    
    if(empty($DatosMejora)){
      $mejora1=0;
      $mejora2=0;
      $mejora3=0;     
    }else{
      $mejora1=$DatosMejora[0]['total'];
      $mejora2=$DatosMejora[1]['total'];
      $mejora3=$DatosMejora[2]['total']; 
    }
    // exit();
     
    $ingresos->setCellValue('H11',$mejora1);
    $ingresos->setCellValue('I11',$mejora2);
    $ingresos->setCellValue('J11',$mejora3);


     // GASTOS MANTENCION

    $consulta="SELECT date_format(es.fecha,'%m-%Y') as mes,
				sum(es.monto) as total             
				  from egresos_simples es
				  inner join egresos e on e.id=es.id_egreso
				  inner join tipos_egreso te on te.id=es.id_tipo_egreso
				  inner join tipo_gasto tg on tg.id=es.tipo_gasto
				  where es.tipo_gasto=3 and e.id_apr=$id_apr and 
				 date_format(es.fecha,'%m-%Y') in ($in) and e.estado=1
          group by mes";

    $query = $db->query($consulta);
    $DatosMantencion  = $query->getResultArray(); 

     if(empty($DatosMantencion)){
      $mantencion1=0;
      $mantencion2=0;
      $mantencion3=0;     
    }else{
      $mantencion1=$DatosMantencion[0]['total'];
      $mantencion2=$DatosMantencion[1]['total'];
      $mantencion3=$DatosMantencion[2]['total']; 
    }
     
    $ingresos->setCellValue('H12',$mantencion1);
    $ingresos->setCellValue('I12',$mantencion2);
    $ingresos->setCellValue('J12',$mantencion3);

     // GASTOS OPERACION

    $consulta="SELECT date_format(es.fecha,'%m-%Y') as mes,
				sum(es.monto) as total             
				  from egresos_simples es
				  inner join egresos e on e.id=es.id_egreso
				  inner join tipos_egreso te on te.id=es.id_tipo_egreso
				  inner join tipo_gasto tg on tg.id=es.tipo_gasto
				  where es.tipo_gasto=1 and e.id_apr=$id_apr and 
				 date_format(es.fecha,'%m-%Y') in ($in) and e.estado=1
          group by mes";

    $query = $db->query($consulta);
    $DatosOperacion  = $query->getResultArray(); 


    if(empty($DatosOperacion)){
      $operacion1=0;
      $operacion2=0;
      $operacion3=0;     
    }else{
      $operacion1=$DatosMantencion[0]['total'];
      $operacion2=$DatosMantencion[1]['total'];
      $operacion3=$DatosMantencion[2]['total']; 
    }
     
    $ingresos->setCellValue('H13',$operacion1);
    $ingresos->setCellValue('I13',$operacion2);
    $ingresos->setCellValue('J13',$operacion3);


    // BANCO

    $consulta="SELECT date_format(es.fecha,'%m-%Y') as mes,
				sum(es.monto) as total             
				  from egresos_simples es
				  inner join egresos e on e.id=es.id_egreso
				  inner join tipos_egreso te on te.id=es.id_tipo_egreso
				  inner join tipo_gasto tg on tg.id=es.tipo_gasto
				  where es.tipo_gasto=6 and e.id_apr=$id_apr and 
				 date_format(es.fecha,'%m-%Y') in ($in) and e.estado=1
          group by mes";

    $query = $db->query($consulta);
    $DatosBanco  = $query->getResultArray(); 


    if(empty($DatosBanco)){
      $banco1=0;
      $banco2=0;
      $banco3=0;     
    }else{
      $banco1=$DatosBanco[0]['total'];
      $banco2=$DatosBanco[1]['total'];
      $banco3=$DatosBanco[2]['total']; 
    }
     
    $ingresos->setCellValue('H16',$banco1);
    $ingresos->setCellValue('I16',$banco2);
    $ingresos->setCellValue('J16',$banco3);

    // OTROS

    $consulta="SELECT date_format(es.fecha,'%m-%Y') as mes,
				sum(es.monto) as total             
				  from egresos_simples es
				  inner join egresos e on e.id=es.id_egreso
				  inner join tipos_egreso te on te.id=es.id_tipo_egreso
				  inner join tipo_gasto tg on tg.id=es.tipo_gasto
				  where es.tipo_gasto=5 and e.id_apr=$id_apr and 
				 date_format(es.fecha,'%m-%Y') in ($in) and e.estado=1
          group by mes";

    $query = $db->query($consulta);
    $DatosOtros  = $query->getResultArray(); 

    if(empty($DatosOtros)){
      $otros1=0;
      $otros2=0;
      $otros3=0;     
    }else{
      $otros1=$DatosOtros[0]['total'];
      $otros2=$DatosOtros[1]['total'];
      $otros3=$DatosOtros[2]['total']; 
    }
     
    $ingresos->setCellValue('H18',$otros1);
    $ingresos->setCellValue('I18',$otros2);
    $ingresos->setCellValue('J18',$otros3);

    
    //**********INFORMACION COMPLEMENTARIA */

    $HojaInfo = '2.- Información complementaria';
    $spreadsheet->setActiveSheetIndexByName($HojaInfo);
    $info = $spreadsheet->getActiveSheet();
    
    $info->setCellValue('B6', $mes1);
    $info->setCellValue('C6', $mes2);
    $info->setCellValue('D6', $mes3);

    //TARIFAS VIGENTES


    // 1/2
    $consutla="SELECT  cf.cargo_fijo,
							concat(cm.desde,' a ',cm.hasta,' m3') as tramo,
							cm.costo
						from 
							costo_metros cm
						    inner join apr_cargo_fijo cf on cm.id_cargo_fijo = cf.id
							inner join apr on cf.id_apr = apr.id
						    inner join diametro d on cf.id_diametro = d.id
							inner join usuarios u on cm.id_usuario = u.id
						where
							cf.id_apr = $id_apr and
						   cf.id_diametro = 1 and
							cm.estado = 1 and cf.tarifa=1";
    
    $query = $db->query($consutla);
    $DatosTarifas  = $query->getResultArray();

    $info->setCellValue('B23', $DatosTarifas[0]['cargo_fijo']);

    $cantidad=count($DatosTarifas);

    for ($i=0; $i < $cantidad; $i++) { 
      $info->setCellValue('A'.(25+$i), $DatosTarifas[$i]['tramo']);
      $info->setCellValue('B'.(25+$i), $DatosTarifas[$i]['costo']);
      
    }

    // 3/4
    $consutla="SELECT  cf.cargo_fijo,
							concat(cm.desde,' a ',cm.hasta,' m3') as tramo,
							cm.costo
						from 
							costo_metros cm
						    inner join apr_cargo_fijo cf on cm.id_cargo_fijo = cf.id
							inner join apr on cf.id_apr = apr.id
						    inner join diametro d on cf.id_diametro = d.id
							inner join usuarios u on cm.id_usuario = u.id
						where
							cf.id_apr = $id_apr and
						   cf.id_diametro = 2 and
							cm.estado = 1 and cf.tarifa=1";
    
    $query = $db->query($consutla);
    $DatosTarifas  = $query->getResultArray();

    $info->setCellValue('C23', $DatosTarifas[0]['cargo_fijo']);

    $cantidad=count($DatosTarifas);

    for ($i=0; $i < $cantidad; $i++) { 
      // $info->setCellValue('A'.(25+$i), $DatosTarifas[$i]['tramo']);
      $info->setCellValue('C'.(25+$i), $DatosTarifas[$i]['costo']);
      
    }

    // 3/4
    $consutla="SELECT  cf.cargo_fijo,
							concat(cm.desde,' a ',cm.hasta,' m3') as tramo,
							cm.costo
						from 
							costo_metros cm
						    inner join apr_cargo_fijo cf on cm.id_cargo_fijo = cf.id
							inner join apr on cf.id_apr = apr.id
						    inner join diametro d on cf.id_diametro = d.id
							inner join usuarios u on cm.id_usuario = u.id
						where
							cf.id_apr = $id_apr and
						   cf.id_diametro = 3 and
							cm.estado = 1 and cf.tarifa=1";
    
    $query = $db->query($consutla);
    $DatosTarifas  = $query->getResultArray();

    $info->setCellValue('D23', $DatosTarifas[0]['cargo_fijo']);

    $cantidad=count($DatosTarifas);

    for ($i=0; $i < $cantidad; $i++) { 
      // $info->setCellValue('A'.(25+$i), $DatosTarifas[$i]['tramo']);
      $info->setCellValue('D'.(25+$i), $DatosTarifas[$i]['costo']);
      
    }

    // ARRANQUES */

    // $fecha = DateTime::createFromFormat('F-Y', $mes1.'-'.$ano);
    // echo $fecha->format('Y-m');
    // exit();

    $sql="SELECT count(a.id) as total ,m.id_diametro
          FROM arranques a
          inner join medidores m on a.id_medidor=m.id 
          WHERE  a.id_apr =  $id_apr and
          m.estado=1 and m.id_apr
           and m.id_diametro in (1,2,3)
           AND DATE_FORMAT(a.fecha, '%Y-%m') <= '$m1' 
           group by id_diametro";
    
    $query = $db->query($sql);
    $datosArranque  = $query->getResultArray();

    $info->setCellValue('B8', $datosArranque[0]['total']);
    $info->setCellValue('B9', $datosArranque[1]['total']);
    $info->setCellValue('B10',$datosArranque[2]['total']);

    $sql="SELECT count(a.id) as total ,m.id_diametro
          FROM arranques a
          inner join medidores m on a.id_medidor=m.id 
          WHERE  a.id_apr =  $id_apr  and
          m.estado=1 and m.id_apr
           and m.id_diametro in (1,2,3)
           AND DATE_FORMAT(a.fecha, '%Y-%m') <= '$m2' 
           group by id_diametro";
    
    $query = $db->query($sql);
    $datosArranque  = $query->getResultArray();

    $info->setCellValue('C8', $datosArranque[0]['total']);
    $info->setCellValue('C9', $datosArranque[1]['total']);
    $info->setCellValue('C10',$datosArranque[2]['total']);

     $sql="SELECT count(a.id) as total ,m.id_diametro
          FROM arranques a
          inner join medidores m on a.id_medidor=m.id 
          WHERE  a.id_apr =  $id_apr and
          m.estado=1 and m.id_apr
           and m.id_diametro in (1,2,3)
           AND DATE_FORMAT(a.fecha, '%Y-%m') <= '$m3' 
           group by id_diametro";
    
    $query = $db->query($sql);
    $datosArranque  = $query->getResultArray();

    $info->setCellValue('D8', $datosArranque[0]['total']);
    $info->setCellValue('D9', $datosArranque[1]['total']);
    $info->setCellValue('D10',$datosArranque[2]['total']);


    // METROS FACTURADOS

    $sql="SELECT 
              sum(metros) as cantidad
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%Y-%m') ='$m1'
              and c.id_apr=$id_apr  and c.estado =1";
    
    $query = $db->query($sql);
    $datosMetros  = $query->getResultArray();

    $info->setCellValue('B19', $datosMetros[0]['cantidad']);

    $sql="SELECT 
              sum(metros) as cantidad
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%Y-%m') ='$m2'
              and c.id_apr=$id_apr  and c.estado =1";
    
    $query = $db->query($sql);
    $datosMetros  = $query->getResultArray();

    $info->setCellValue('C19', $datosMetros[0]['cantidad']);

    $sql="SELECT 
              sum(metros) as cantidad
              from caja c
              inner join caja_detalle d on d.id_caja=c.id
              inner join metros m on m.id=d.id_metros
              where date_format(c.fecha,'%Y-%m') ='$m3'
              and c.id_apr=$id_apr  and c.estado =1";
    
    $query = $db->query($sql);
    $datosMetros  = $query->getResultArray();

    $info->setCellValue('D19', $datosMetros[0]['cantidad']);

    //SUBSIDIOS

    $sql="SELECT count(*) as cantidad,id_porcentaje 
            from subsidios 
            where id_apr=$id_apr and estado=1 
            group by id_porcentaje order by 2 desc";

    $query = $db->query($sql);
    $datosSub  = $query->getResultArray();

    $info->setCellValue('H28', $datosSub[0]['cantidad']);
    $info->setCellValue('H29', $datosSub[1]['cantidad']);
    
    // echo $consulta;
    // exit;
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte_Trimestre_'.$trimestre.'_del_'.$ano.'.xlsx"');
    header('Cache-Control: max-age=0');

    
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');


  }

  
}

?>