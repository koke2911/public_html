<?php

namespace App\Controllers\Configuracion;

use App\ThirdParty\Touchef;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Configuracion\Md_apr_traza;
use App\Models\Formularios\Md_socios;
use App\Models\Formularios\Md_socios_traza;
use App\Models\Formularios\Md_diametro;
use App\Models\Formularios\Md_medidores;
use App\Models\Formularios\Md_medidor_traza;
use App\Models\Formularios\Md_arranque_traza;
use App\Models\Formularios\Md_arranques;
use App\Models\Formularios\Md_sectores;
use App\Models\Consumo\Md_metros;
use App\Models\Consumo\Md_metros_traza;


class Ctrl_importar extends BaseController {

  protected $sesión;
  protected $db;
  protected $apr_traza;
  protected $socios;
  protected $string_error = "";
  protected $socios_traza;
  protected $medidores;
  protected $medidor_traza;
  protected $arranques;
  protected $arranque_traza;
  protected $sectores;
  protected $metros;
  protected $metros_traza;


  public function __construct() {
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
    $this->apr_traza = new Md_apr_traza();
    $this->apr       = new Md_apr();
    $this->socios    = new Md_socios();
    $this->socios_traza = new Md_socios_traza();
    $this->medidores     = new Md_medidores();
    $this->medidor_traza = new Md_medidor_traza();
    $this->arranques      = new Md_arranques();
    $this->arranque_traza = new Md_arranque_traza();
    $this->sectores     = new Md_sectores();
    $this->metros                = new Md_metros();
    $this->metros_traza          = new Md_metros_traza();



  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function importar_socios() {
    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;

    if ($this->request->getMethod() == "post") {
        $file = $this->request->getFile("socios");
        $ruta = "uploads/";
        $name_file = $file->getName();
        

        if (!$file->isValid()) {
          throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
        } else {
          // if(file_exists("uploads/".$name_file)){
          //   echo "ARCHIVO YA EXISTE";
          // }else{
           // $file->move($ruta);

            $reader  = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($file);
            $sheet       = $spreadsheet->getSheet(0);

                                                        

            foreach ($sheet->getRowIterator(2) as $row) {
              //$ultimo_socio++;

              //echo $ultimo_socio;

              $rut= trim($sheet->getCellByColumnAndRow(1, $row->getRowIndex()));
              $dv=trim($sheet->getCellByColumnAndRow(2, $row->getRowIndex()));
              $rol=trim($sheet->getCellByColumnAndRow(3, $row->getRowIndex()));
              $nombres=trim($sheet->getCellByColumnAndRow(4, $row->getRowIndex()));
              $ape_pat=trim($sheet->getCellByColumnAndRow(5, $row->getRowIndex()));
              $ape_mat=trim($sheet->getCellByColumnAndRow(6, $row->getRowIndex()));
              $email=trim($sheet->getCellByColumnAndRow(7, $row->getRowIndex()));
              $fecha_nacimiento=trim($sheet->getCellByColumnAndRow(8, $row->getRowIndex()));
              $id_sexo=trim($sheet->getCellByColumnAndRow(9, $row->getRowIndex()));
              $calle=trim($sheet->getCellByColumnAndRow(10, $row->getRowIndex()));
              $numero=trim($sheet->getCellByColumnAndRow(11, $row->getRowIndex()));
              $resto_direccion=trim($sheet->getCellByColumnAndRow(12, $row->getRowIndex()));
              $id_comuna=trim($sheet->getCellByColumnAndRow(13, $row->getRowIndex()));
              $ruta      =trim($sheet->getCellByColumnAndRow(14, $row->getRowIndex()));

              $datosSocioRut  = $this->socios->select("rut")
                                                ->where("id_apr", $id_apr)
                                                ->where("rut", $rut)
                                                ->first();
              $socio_rut=$datosSocioRut['rut']; 

              $fecha      = date("Y-m-d H:i:s");
              $id_usuario = $this->sesión->id_usuario_ses;

              if ($fecha_nacimiento == "") {
                $fecha_nacimiento = NULL;
              } else {
                $fecha_nacimiento = date_format(date_create($fecha_nacimiento), 'Y-m-d');
              }

              if($socio_rut!=$rut && $rut!="" ){
                  $datosSocio = [
                    
                     "rut"              => $rut,
                     "dv"               => $dv,
                     "rol"              => $rol,
                     "nombres"          => $nombres,
                     "ape_pat"          => $ape_pat,
                     "ape_mat"          => $ape_mat,
                     "fecha_entrada"    => date_format(date_create($fecha), 'Y-m-d'),
                     "fecha_nacimiento" => $fecha_nacimiento,
                     "id_sexo"          => $id_sexo,
                     "calle"            => $calle,
                     "numero"           => $numero,
                     "resto_direccion"  => $resto_direccion,
                     "ruta"             => $ruta,
                     "id_comuna"        => $id_comuna,
                     "id_usuario"       => $id_usuario,
                     "fecha"            => $fecha,
                     "id_apr"           => $id_apr,
                     "estado"           =>1,
                     "email"            =>$email
                    ];

                  if ($this->socios->save($datosSocio)) {

                      $datosSocios  = $this->socios->select("max(id) as id_socio")
                                                 ->first();

                      $id_socio=$datosSocios['id_socio'];
                      $datosTraza = [
                       "id_socio"    => $id_socio,
                       "estado"      => 1,
                       "observacion" => "CARGA MASIVA",
                       "id_usuario"  => $id_usuario,
                       "fecha"       => $fecha
                      ];

                      if (!$this->socios_traza->save($datosTraza)) {
                        echo "Falló al guardar la traza";
                        exit();
                      }
                  }
              } 
            }

            echo 0;
          //}
        }
    }    
    
  }

  public function importar_medidor(){

    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;

    if ($this->request->getMethod() == "post") {
        $file = $this->request->getFile("socios");
        $ruta = "uploads/";
        $name_file = $file->getName();
        

        if (!$file->isValid()) {
          throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
        } else {
           
            $reader  = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($file);
            $sheet       = $spreadsheet->getSheet(0);

                                                        

            foreach ($sheet->getRowIterator(2) as $row) {
             
              $numero= trim($sheet->getCellByColumnAndRow(1, $row->getRowIndex()));
              $id_diametro=trim($sheet->getCellByColumnAndRow(2, $row->getRowIndex()));
              $marca=trim($sheet->getCellByColumnAndRow(3, $row->getRowIndex()));
              $tipo=trim($sheet->getCellByColumnAndRow(4, $row->getRowIndex()));

              

              $datosMedidores  = $this->medidores->select("numero")
                                                ->where("id_apr", $id_apr)
                                                ->where("numero", $numero)
                                                ->first();
              $M_numero=$datosMedidores['numero']; 

              $fecha      = date("Y-m-d H:i:s");
              $id_usuario = $this->sesión->id_usuario_ses;

              if($M_numero!=$numero && $numero!="" ){
                    
                    $datosMedidor = [
                             "numero"      => $numero,
                             "id_diametro" => $id_diametro,
                             "marca"       => $marca,
                             "tipo"        => $tipo,
                             "id_usuario"  => $id_usuario,
                             "fecha"       => $fecha,
                             "id_apr"      => $id_apr
                            ];                  

                if ($this->medidores->save($datosMedidor)) {
                   
                  $obtener_id = $this->medidores->select("max(id) as id_medidor")
                                                ->first();
                  $id_medidor = $obtener_id["id_medidor"];   

                  $datosTraza = [
                     "id_medidor"  => $id_medidor,
                     "estado"      => 1,
                     "observacion" => 'CARGA MASIVA',
                     "id_usuario"  => $id_usuario,
                     "fecha"       => $fecha
                    ];


                    if (!$this->medidor_traza->save($datosTraza)) {
                      echo "Falló al guardar la traza";
                      exit();
                    }                 
                }
              }
            }

            echo 0;
          
        }
    }    
  }


  public function importar_arranque(){


    // echo 'ARRANQUE';
    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;

    $obtener_sector = $this->sectores->select("count(*) as existe_sector")
                                      ->where("id_apr", $id_apr)
                                     ->first();

    $existe_sector  = $obtener_sector["existe_sector"];

    if($existe_sector==0){
      echo "Debe al menos configurar un sector";
      exit();
    }

    if ($this->request->getMethod() == "post") {
        $file = $this->request->getFile("socios");
        $ruta = "uploads/";
        $name_file = $file->getName();
        

        if (!$file->isValid()) {
          throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
        } else {
           
            $reader  = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($file);
            $sheet       = $spreadsheet->getSheet(0);

                                                        

            foreach ($sheet->getRowIterator(2) as $row) {
             
              $id_socio= trim($sheet->getCellByColumnAndRow(1, $row->getRowIndex()));
              $id_medidor=trim($sheet->getCellByColumnAndRow(2, $row->getRowIndex()));
              $id_comuna=trim($sheet->getCellByColumnAndRow(3, $row->getRowIndex()));
              $id_sector=trim($sheet->getCellByColumnAndRow(4, $row->getRowIndex()));
              $tipo_documento=trim($sheet->getCellByColumnAndRow(5, $row->getRowIndex()));
              $descuento=trim($sheet->getCellByColumnAndRow(6, $row->getRowIndex()));
              $monto_alcantarillado=trim($sheet->getCellByColumnAndRow(7, $row->getRowIndex()));
              $monto_cuota_socio=trim($sheet->getCellByColumnAndRow(8, $row->getRowIndex()));
              $monto_otros=trim($sheet->getCellByColumnAndRow(9, $row->getRowIndex()));
              $alcantarillado=0;
              $cuota_socio=0;
              $otros=0;
              
              
              if($monto_alcantarillado>0){
                $alcantarillado=1;
              }
              if($monto_cuota_socio>0){
                $cuota_socio=1;
              }
              if($monto_otros>0){
                $otros=1;
              }

              $fecha      = date("Y-m-d H:i:s");
              $id_usuario = $this->sesión->id_usuario_ses;

              $datosArranques  = $this->arranques->select("count(*) as existe")
                                                ->where("id_socio", $id_socio)
                                                ->where("id_apr", $id_apr)
                                                ->first();

              $existeArranque=$datosArranques['existe']; 

             if($existeArranque==0){

                    $datosArranque = [
                   "id_socio"             => $id_socio,
                   "estado"               => 1,
                   "id_medidor"           => $id_medidor,
                   "id_sector"            => $id_sector,
                   "alcantarillado"       => $alcantarillado,
                   "cuota_socio"          => $cuota_socio,
                   "otros"                => $otros,
                   "id_comuna"            => $id_comuna,
                   "id_tipo_documento"    => $tipo_documento,
                   "id_usuario"           => $id_usuario,
                   "fecha"                => $fecha,
                   "id_apr"               => $id_apr,
                   "monto_alcantarillado" => $monto_alcantarillado,
                   "monto_cuota_socio"    => $monto_cuota_socio,
                   "monto_otros"          => $monto_otros,
                   "descuento"            => $descuento,
                  ];             

                  // print_r($datosArranque);
                  // exit();
                if ($this->arranques->save($datosArranque)) {
                   
                  $obtener_id  = $this->arranques->select("max(id) as id_arranque")
                                      ->first();
                  $id_arranque = $obtener_id["id_arranque"];

                  $datosTraza = [
                  "id_arranque" => $id_arranque,
                  "estado"      => 1,
                  "observacion" => 'CARGA MASIVA',
                  "id_usuario"  => $id_usuario,
                  "fecha"       => $fecha
                  ];

                  if (!$this->arranque_traza->save($datosTraza)) {
                    echo "Falló al guardar la traza";
                  }
                              
                }
              }
            }

            echo 0;
          
        }
    }    

  }

  public function importar_deuda(){

    $this->validar_sesion();
    $id_apr=$this->sesión->id_apr_ses;

    if ($this->request->getMethod() == "post") {
        $file = $this->request->getFile("socios");
        $ruta = "uploads/";
        $name_file = $file->getName();
        

        if (!$file->isValid()) {
          throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
        } else {
           $reader  = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($file);
            $sheet       = $spreadsheet->getSheet(0);
                                                     
            foreach ($sheet->getRowIterator(2) as $row) {

              $id_socio= trim($sheet->getCellByColumnAndRow(1, $row->getRowIndex()));
              $monto_subsidio=trim($sheet->getCellByColumnAndRow(2, $row->getRowIndex()));
              $fecha_ingreso=trim($sheet->getCellByColumnAndRow(3, $row->getRowIndex()));
              $fecha_vencimiento=trim($sheet->getCellByColumnAndRow(4, $row->getRowIndex()));
              $consumo_anterior=trim($sheet->getCellByColumnAndRow(5, $row->getRowIndex()));
              $consumo_actual=trim($sheet->getCellByColumnAndRow(6, $row->getRowIndex()));
              $metros=trim($sheet->getCellByColumnAndRow(7, $row->getRowIndex()));
              $subtotal=trim($sheet->getCellByColumnAndRow(8, $row->getRowIndex()));
              $multa=trim($sheet->getCellByColumnAndRow(9, $row->getRowIndex()));
              $total_servicios=trim($sheet->getCellByColumnAndRow(10, $row->getRowIndex()));
              $total_mes=trim($sheet->getCellByColumnAndRow(11, $row->getRowIndex()));
              $cargo_fijo=trim($sheet->getCellByColumnAndRow(12, $row->getRowIndex()));
              $monto_facturable=trim($sheet->getCellByColumnAndRow(13, $row->getRowIndex()));
              $cuota_repactacion      =trim($sheet->getCellByColumnAndRow(14, $row->getRowIndex()));
              $id_tipo_documento      =trim($sheet->getCellByColumnAndRow(15, $row->getRowIndex()));
              $tipo_facturacion      =trim($sheet->getCellByColumnAndRow(16, $row->getRowIndex()));
              $alcantarillado      =trim($sheet->getCellByColumnAndRow(17, $row->getRowIndex()));
              $cuota_socio      =trim($sheet->getCellByColumnAndRow(18, $row->getRowIndex()));
              $otros      =trim($sheet->getCellByColumnAndRow(19, $row->getRowIndex()));

              $datosSocioId  = $this->socios->select("count(*) as existe_socio")
                                                ->where("id", $id_socio)
                                                ->first();

              $existe_socio=$datosSocioId['existe_socio']; 

              $fecha      = date("Y-m-d H:i:s");
              $id_usuario = $this->sesión->id_usuario_ses;

              if($existe_socio>0){
                $datosMetros = [
                   "id_socio"          => $id_socio,
                   "monto_subsidio"    => $monto_subsidio,
                   "fecha_ingreso"     => date_format(date_create($fecha_ingreso), 'Y-m-d'),
                   "fecha_vencimiento" => date_format(date_create($fecha_vencimiento), 'Y-m-d'),
                   "consumo_anterior"  => $consumo_anterior,
                   "consumo_actual"    => $consumo_actual,
                   "metros"            => $metros,
                   "subtotal"          => $subtotal,
                   "multa"             => $multa,
                   "total_servicios"   => $total_servicios,
                   "cuota_repactacion" => $cuota_repactacion,
                   "total_mes"         => $total_mes,
                   "cargo_fijo"        => $cargo_fijo,
                   "monto_facturable"  => $monto_facturable,
                   "id_usuario"        => $id_usuario,
                   "fecha"             => $fecha,
                   "id_apr"            => $id_apr,
                   "alcantarillado"    => $alcantarillado,
                   "cuota_socio"       => $cuota_socio,
                   "otros"             => $otros,
                   "iva"               => 0
                  ];

                  
                  if($this->metros->save($datosMetros)){
                    $obtener_id = $this->metros->select("max(id) as id_metros")
                                               ->first();
                    $id_metros  = $obtener_id["id_metros"];
                   

                    $datosTraza = [
                     "id_metros"   => $id_metros,
                     "estado"      => 1,
                     "observacion" => 'CARGA MASIVA',
                     "id_usuario"  => $id_usuario,
                     "fecha"       => $fecha
                    ];

                    if(!$this->metros_traza->save($datosTraza)){
                      echo 'Error al guardar traza';
                      exit();
                    }
                  }

                    
                    

                    // $this->db->transComplete();

              }


            }

            echo 0;

        }  
    }
  }
}