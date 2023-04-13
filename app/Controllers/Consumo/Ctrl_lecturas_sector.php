<?php

namespace App\Controllers\Consumo;

use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Formularios\Md_socios;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_subsidios;
use App\Models\Formularios\Md_arranques;
use App\Models\Configuracion\Md_costo_metros;
use App\Models\Formularios\Md_convenio_detalle;
use App\Models\Formularios\Md_repactaciones_detalle;

class Ctrl_lecturas_sector extends BaseController {

  protected $metros;
  protected $metros_traza;
  protected $costo_metros;
  protected $convenio_detalle;
  protected $repactaciones_detalle;
  protected $socios;
  protected $apr;
  protected $subsidios;
  protected $arranques;
  protected $sesión;
  protected $db;

  protected $validation;
  protected $validacion_datos;
  protected $validacion_id_socio;

  public function __construct() {
    $this->metros                = new Md_metros();
    $this->metros_traza          = new Md_metros_traza();
    $this->costo_metros          = new Md_costo_metros();
    $this->convenio_detalle      = new Md_convenio_detalle();
    $this->repactaciones_detalle = new Md_repactaciones_detalle();
    $this->socios                = new Md_socios();
    $this->apr                   = new Md_apr();
    $this->subsidios             = new Md_subsidios();
    $this->arranques             = new Md_arranques();
    $this->sesión                = session();
    $this->db                    = \Config\Database::connect();
    $this->validation            = \Config\Services::validation();

    $this->validacion_datos = [
     "id_metros"         => [
      "label"  => "ID. Metros",
      "rules"  => "integer",
      "errors" => [
       "integer" => "{field} debe ser un campo numérico"
      ]
     ],
     "id_socio"          => [
      "label"  => "ID. Socio",
      "rules"  => "required|integer",
      "errors" => [
       "required" => "{field} no se está enviando, reincie el formulario",
       "integer"  => "Id. Socio debe ser un campo numérico"
      ]
     ],
     "mes_consumo"       => [
      "label"  => "Mes de Consumo",
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "fecha_vencimiento" => [
      "label"  => "Fecha Vencimiento del Pago",
      "rules"  => "required",
      "errors" => [
       "required" => "El campo {field} es obligatorio"
      ]
     ],
     "lectura_actual"    => [
      "label"  => "Lectura Actual",
      "rules"  => "required|numeric",
      "errors" => [
       "required" => "El campo {field} es obligatorio",
       "numeric"  => "El campo {field} debe ser numérico"
      ]
     ],
     "lectura_anterior"  => [
      "label"  => "Lectura Anterior",
      "rules"  => "required|numeric",
      "errors" => [
       "required" => "El campo {field} es obligatorio",
       "numeric"  => "El campo {field} debe ser numérico"
      ]
     ]
    ];

    $this->validacion_id_socio = [
     "id_socio" => [
      "label"  => "ID. Socio",
      "rules"  => "required|integer",
      "errors" => [
       "required" => "{field} no se está enviando, reincie el formulario",
       "integer"  => "Id. Socio debe ser un campo numérico"
      ]
     ]
    ];
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_lecturas_sector($datosBusqueda) {
    $this->validar_sesion();
    define("ELIMINADO", 0);

    $datosBusqueda = json_decode($datosBusqueda, TRUE);

    $id_sector   = $datosBusqueda["id_sector"];
    $mes_consumo = $datosBusqueda["mes_consumo"];

    $datatable_lecturas_sector = $this->socios
     ->select("socios.id as id_socio")
     ->select("mt.id as id_metros")
     ->select("ifnull(socios.ruta, 'No registrada') as ruta")
     ->select("socios.rol as rol_socio")
     ->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as socio")
     ->select("m.numero as n_medidor")
     ->select("case when mt.consumo_anterior is null then
                    (select 
                        max(mt2.consumo_actual)
                    from 
                        metros mt2 
                    where 
                        mt2.id_socio = socios.id)
                else mt.consumo_anterior end as lectura_anterior")
     ->select("ifnull(mt.consumo_actual, '') as lectura_actual")
     ->join("arranques a", "a.id_socio = socios.id")
     ->join("medidores m", "a.id_medidor = m.id")
     ->join("metros mt", "mt.id_socio = socios.id and date_format(mt.fecha_ingreso, '%m-%Y') = '$mes_consumo'", "left")
     ->where("a.id_sector", $id_sector)
     ->findAll();

    $salida = ["data" => $datatable_lecturas_sector];

    return json_encode($salida);
  }

  public function ingresar_lectura_sector() {
    $this->validar_sesion();
    if ($this->request->getMethod() == "post" && $this->validate($this->validacion_datos)) {
      define("INGRESO_METROS", 1);
      define("MODIFICAR_METROS", 2);

      define("OK", 1);
      define("ACTIVO", 1);

      define("T_MEDIO", 2);

      $fecha      = date("Y-m-d H:i:s");
      $id_usuario = $this->sesión->id_usuario_ses;
      $id_apr     = $this->sesión->id_apr_ses;

      $id_metros         = $this->request->getPost("id_metros");
      $id_socio          = $this->request->getPost("id_socio");
      $mes_consumo       = "01-" . $this->request->getPost("mes_consumo");
      $fecha_vencimiento = $this->request->getPost("fecha_vencimiento");
      $lectura_actual    = $this->request->getPost("lectura_actual");
      $lectura_anterior  = $this->request->getPost("lectura_anterior");
      $tipo_facturacion  = $this->request->getPost("tipo_facturacion");

      if (intval($lectura_anterior) > intval($lectura_actual)) {
        $respuesta = [
         "estado"  => "Error",
         "mensaje" => "Lectura anterior no puede ser mayor a lectura actual"
        ];

        return json_encode($respuesta);
      }

      $datosApr                  = $this->apr->select("tope_subsidio")
                                             ->where("id", $id_apr)
                                             ->first();
      $datosSubsidio             = $this->subsidios
       ->select("p.glosa as porcentaje")
       ->join("porcentajes p", "subsidios.id_porcentaje = p.id")
       ->where("id_socio", $id_socio)
       ->first();
      $datosCargoFijo            = $this->arranques
       ->select("cf.cargo_fijo")
       ->select("m.id_diametro")
       ->select("arranques.monto_alcantarillado as alcantarillado")
       ->select("arranques.monto_cuota_socio as cuota_socio")
       ->select("arranques.monto_otros as otros")
       ->join("medidores m", "arranques.id_medidor = m.id")
       ->join("apr_cargo_fijo cf", "m.id_diametro = cf.id_diametro")
       ->where("arranques.id_socio", $id_socio)
       ->where("cf.id_apr", $id_apr)
       ->first();
      $datosCostoMetros          = $this->costo_metros
       ->select("costo_metros.id as id_costo_metros")
       ->select("costo_metros.desde")
       ->select("costo_metros.hasta")
       ->select("costo_metros.costo")
       ->join("apr_cargo_fijo cf", "costo_metros.id_cargo_fijo = cf.id")
       ->join("diametro d", "cf.id_diametro = d.id")
       ->where("cf.id_apr", $id_apr)
       ->where("cf.id_diametro", $datosCargoFijo["id_diametro"])
       ->where("costo_metros.estado", ACTIVO)
       ->findAll();
      $datosConvenioDetalle      = $this->convenio_detalle
       ->select("ifnull(sum(convenio_detalle.valor_cuota), 0) as total_servicios")
       ->join("convenios", "convenio_detalle.id_convenio=convenios.id")
       ->where("date_format(convenio_detalle.fecha_pago, '%m-%Y')", $fecha_vencimiento)
       ->where("convenios.id_socio", $id_socio)
       ->where("convenios.estado", ACTIVO)
       ->first();
      $datosRepactacionesDetalle = $this->repactaciones_detalle
       ->select("ifnull(sum(repactaciones_detalle.valor_cuota), 0) as total_servicios")
       ->join("repactaciones", "repactaciones_detalle.id_repactacion=repactaciones.id")
       ->where("date_format(repactaciones_detalle.fecha_pago, '%m-%Y')", $fecha_vencimiento)
       ->where("repactaciones.id_socio", $id_socio)
       ->where("repactaciones.estado", ACTIVO)
       ->first();

      $tope_subsidio  = $datosApr["tope_subsidio"];
      $porcentaje     = $datosSubsidio ? substr($datosSubsidio["porcentaje"], 0, strlen($datosSubsidio["porcentaje"]) - 1) : 0;
      $cargo_fijo     = $datosCargoFijo["cargo_fijo"];
      $alcantarillado = $datosCargoFijo["alcantarillado"];
      $cuota_socio    = $datosCargoFijo["cuota_socio"];
      $otros          = $datosCargoFijo["otros"];

      $total_servicios   = $datosConvenioDetalle != NULL ? intval($datosConvenioDetalle["total_servicios"]) : 0;
      $cuota_repactacion = $datosRepactacionesDetalle != NULL ? intval($datosRepactacionesDetalle["total_servicios"]) : 0;

      helper('calcular_montos');
      $montos = calcular_montos(
       $lectura_anterior,
       $lectura_actual,
       $tope_subsidio,
       $datosCostoMetros,
       $cargo_fijo,
       $porcentaje,
       $total_servicios,
       $cuota_repactacion,
       $alcantarillado,
       $cuota_socio,
       $otros
      );

      $monto_subsidio   = $montos["monto_subsidio"];
      $metros           = $montos["metros_consumidos"];
      $subtotal         = $montos["subtotal"];
      $monto_facturable = $montos["monto_facturable"];
      $total_mes        = $montos["total_mes"];

      $datosMetros = [
       "id_socio"          => $id_socio,
       "monto_subsidio"    => $monto_subsidio,
       "fecha_ingreso"     => date_format(date_create($mes_consumo), 'Y-m-d'),
       "fecha_vencimiento" => date_format(date_create($fecha_vencimiento), 'Y-m-d'),
       "consumo_anterior"  => $lectura_anterior,
       "consumo_actual"    => $lectura_actual,
       "metros"            => $metros,
       "subtotal"          => $subtotal,
       "total_servicios"   => $total_servicios,
       "cuota_repactacion" => $cuota_repactacion,
       "total_mes"         => $total_mes,
       "cargo_fijo"        => $cargo_fijo,
       "monto_facturable"  => $monto_facturable,
       "alcantarillado"    => $alcantarillado,
       "cuota_socio"       => $cuota_socio,
       "otros"             => $otros,
       "id_usuario"        => $id_usuario,
       "fecha"             => $fecha,
       "id_apr"            => $id_apr
      ];

      if ($tipo_facturacion == T_MEDIO) {
        $datosMetros["tipo_facturacion"] = T_MEDIO;
      }

      if ($id_metros != 0) {
        $estado_traza      = MODIFICAR_METROS;
        $datosMetros["id"] = $id_metros;
      } else {
        $estado_traza = INGRESO_METROS;
      }

      $this->db->transStart();
      $this->metros->save($datosMetros);

      if ($id_metros == "") {
        $obtener_id = $this->metros->select("max(id) as id_metros")
                                   ->first();
        $id_metros  = $obtener_id["id_metros"];
      }

      $datosTraza = [
       "id_metros"  => $id_metros,
       "estado"     => $estado_traza,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      $this->metros_traza->save($datosTraza);
      $this->db->transComplete();

      if ($this->db->transStatus()) {
        $respuesta = [
         "estado"  => "OK",
         "mensaje" => "Lectura ingresada correctamente"
        ];

        return json_encode($respuesta);
      } else {
        $respuesta = [
         "estado"  => "Error",
         "mensaje" => "Error al ingresar la lectura, inténtelo nuevamente, si el problema persiste, comuníquese con soporte"
        ];

        return json_encode($respuesta);
      }
    } else {
      $errors    = implode(",", $this->validation->getErrors());
      $respuesta = [
       "estado"  => "Error",
       "mensaje" => $errors
      ];

      return json_encode($respuesta);
    }
  }

  public function obtener_promedio() {
    $this->validar_sesion();
    if ($this->request->getMethod() == "post" && $this->validate($this->validacion_id_socio)) {
      $id_socio    = $this->request->getPost("id_socio");
      $datosMetros = $this->metros->select("CAST(AVG(metros) as SIGNED) as promedio")
                                  ->where("id_socio", $id_socio)
                                  ->first();
      $respuesta   = [
       "estado"  => "OK",
       "mensaje" => $datosMetros["promedio"]
      ];

      return json_encode($respuesta);
    } else {
      $errors    = implode(",", $this->validation->getErrors());
      $respuesta = [
       "estado"  => "Error",
       "mensaje" => $errors
      ];

      return json_encode($respuesta);
    }
  }

  public function importar_planilla(){
      $this->validar_sesion();
      $id_apr=$this->sesión->id_apr_ses;
      define("ACTIVO", 1);

      if ($this->request->getMethod() == "post") {
        $mes = $this->request->getPost("dt_mes_consumo");
        $Vencimiento = $this->request->getPost("dt_fecha_vencimiento");
        $file = $this->request->getFile("lecturas");

        $name_file = $file->getName();

     
        $extension = pathinfo($name_file);
        echo $extension['extension'];


       if($name_file!="" && $mes!="" && $Vencimiento!=""){
        if (!$file->isValid()) {
          throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
        } else {

          if($extension['extension']=='xlsx'){
            $reader  = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
          }elseif($extension['extension']=='xls'){
            $reader  = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
          }else{
            echo 'Archivo no valido';
            exit();
          }
            $spreadsheet = $reader->load($file);
            $sheet       = $spreadsheet->getSheet(0);
                                                        
        $filas_total=0;   
        $filas_ok=0; 
        foreach ($sheet->getRowIterator(2) as $row) {
                $filas_total++;
                $medidor= trim($sheet->getCellByColumnAndRow(1, $row->getRowIndex()));
                $lectura=trim($sheet->getCellByColumnAndRow(8, $row->getRowIndex()));

                define("ACTIVO", 1);

          $datosSocios = $this->arranques
             ->select("s.id as id_socio")
             ->select("concat(s.rut, '-', s.dv) as rut")
             ->select("s.rol")
             ->select("concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio")
             ->select("date_format(s.fecha_entrada, '%d-%m-%Y') as fecha_entrada")
             ->select("arranques.id as id_arranque")
             ->select("m.id_diametro")
             ->select("d.glosa as diametro")
             ->select("sec.nombre as sector")
             ->select("case when sub.estado = 1 then p.glosa else '0%' end as subsidio")
             ->select("(select tope_subsidio from apr where id = s.id_apr) as tope_subsidio")
             ->select("ifnull((select consumo_actual from metros m where m.id = (select max(m2.id) from metros m2 where m2.id_socio = arranques.id_socio and estado <> 0)), 0) as consumo_anterior")
             ->select("cf.cargo_fijo")
             ->select("s.abono")
             ->select("ifnull(arranques.monto_alcantarillado, 0) as alcantarillado")
             ->select("ifnull(arranques.monto_cuota_socio, 0) as cuota_socio")
             ->select("ifnull(arranques.monto_otros, 0) as otros")
             ->select("arranques.id_tipo_documento")
             ->select("m.numero as numero_med")
             ->select("cf.id as id_cargo_fijo")
             ->join("medidores m", "arranques.id_medidor = m.id")
             ->join("diametro d", "m.id_diametro = d.id")
             ->join("socios s", "arranques.id_socio = s.id")
             ->join("sectores sec", "arranques.id_sector = sec.id")
             ->join("subsidios sub", "sub.id_socio = s.id", "left")
             ->join("porcentajes p", "sub.id_porcentaje = p.id", "left")
             ->join("apr_cargo_fijo cf", "cf.id_apr = s.id_apr and cf.id_diametro = m.id_diametro")
             ->where("s.id_apr", $id_apr)
             ->where("m.id_apr", $id_apr)
             ->where("m.numero", $medidor)
             ->first();

                  $id_socio = $datosSocios['id_socio'];
                  $rut = $datosSocios['rut'];
                  $rol = $datosSocios['rol'];
                  $nombre_socio = $datosSocios['nombre_socio'];
                  $fecha_entrada = $datosSocios['fecha_entrada'];
                  $id_arranque = $datosSocios['id_arranque'];
                  $id_diametro = $datosSocios['id_diametro'];
                  $diametro = $datosSocios['diametro'];
                  $sector = $datosSocios['sector'];
                  $subsidio = $datosSocios['subsidio'];
                  $tope_subsidio = $datosSocios['tope_subsidio'];
                  $consumo_anterior = $datosSocios['consumo_anterior'];
                  $cargo_fijo = $datosSocios['cargo_fijo'];
                  $abono = $datosSocios['abono'];
                  $alcantarillado = $datosSocios['alcantarillado'];
                  $cuota_socio = $datosSocios['cuota_socio'];
                  $otros = $datosSocios['otros'];
                  $id_tipo_documento = $datosSocios['id_tipo_documento'];
                  $numero_med = $datosSocios['numero_med'];
                  $id_cargo_fijo = $datosSocios['id_cargo_fijo'];


                  $existe_consumo_mes = $this->metros->select("count(*) as filas")
                                       ->where("id_socio", $id_socio)
                                       ->where("date_format(fecha_ingreso, '%m-%Y')", $mes)
                                       ->where("estado", 1)
                                       ->first();
                  $filasexiste = $existe_consumo_mes["filas"];

                if($lectura>$consumo_anterior && $id_socio!="" && $filasexiste==0){

                    $metros_consumidos=$lectura-$consumo_anterior;

                    $datosCostoMetros = json_decode($this->costo_metros->datatable_costo_metros_consumo($this->db, $this->sesión->id_apr_ses, $id_diametro, 0));

                        for ($i = 0; $i <= $metros_consumidos; $i ++) {
                        foreach ($datosCostoMetros as $key => $value) {
                          foreach ($value as $k => $v) {
                            if ($i >= intval($v->desde) and $i <= intval($v->hasta)) {
                              if (intval($v->id_costo_metros) == 0) {
                                $subtotal = intval($v->costo);
                              } else {
                                $subtotal = $subtotal + intval($v->costo);
                              }

                              if ($i <= intval($tope_subsidio)) {
                                $total_subsidio = $subtotal-$cargo_fijo;
                              }
                            }
                          }
                        }
                      }

                      $subsidio = explode("%",$subsidio);
                      $subsidio = intval($subsidio[0]);
                      $monto_subsidio=0;

                      if($subsidio>0){
                         $monto_subsidio = $total_subsidio * $subsidio / 100;
                         $alcantarillado= $alcantarillado * $subsidio / 100;
                      }

                      $fecha_vencimiento = date_format(date_create($this->request->getPost("dt_fecha_vencimiento")), 'm-Y');

                      $datosConvenioDetalle = $this->convenio_detalle
                     ->select("ifnull(sum(convenio_detalle.valor_cuota), 0) as total_servicios")
                     ->join("convenios", "convenio_detalle.id_convenio=convenios.id")
                     ->where("date_format(convenio_detalle.fecha_pago, '%m-%Y')", $fecha_vencimiento)
                     ->where("convenios.id_socio", $id_socio)
                     ->where("convenios.estado", ACTIVO)
                     ->first();

                    if ($datosConvenioDetalle != NULL) {
                      $total_servicios = intval($datosConvenioDetalle["total_servicios"]);
                    } else {
                      $total_servicios = 0;
                    }

                    $datosRepactacionesDetalle = $this->repactaciones_detalle
                     ->select("ifnull(sum(repactaciones_detalle.valor_cuota), 0) as total_servicios")
                     ->join("repactaciones", "repactaciones_detalle.id_repactacion=repactaciones.id")
                     ->where("date_format(repactaciones_detalle.fecha_pago, '%m-%Y')", $fecha_vencimiento)
                     ->where("repactaciones.id_socio", $id_socio)
                     ->where("repactaciones.estado", ACTIVO)
                     ->first();

                    if ($datosRepactacionesDetalle != NULL) {
                      $cuota_repactacion = intval($datosRepactacionesDetalle["total_servicios"]);
                    } else {
                      $cuota_repactacion = 0;
                    }

                    $datosServicios = [
                     "total_servicios"   => $total_servicios,
                     "cuota_repactacion" => $cuota_repactacion
                    ];

                    $monto_facturable=$subtotal-$monto_subsidio;
                    $total_mes=$monto_facturable+$total_servicios+$cuota_repactacion+$alcantarillado+$cuota_socio+$otros;

                    $fecha      = date("Y-m-d H:i:s");
                    $id_usuario = $this->sesión->id_usuario_ses;

                    $datosMetros = [
                   "id_socio"          => $id_socio,
                   "monto_subsidio"    => $monto_subsidio,
                   "fecha_ingreso"     => date_format(date_create('01-'.$mes), 'Y-m-d'),
                   "fecha_vencimiento" => date_format(date_create($Vencimiento), 'Y-m-d'),
                   "consumo_anterior"  => $consumo_anterior,
                   "consumo_actual"    => $lectura,
                   "metros"            => $metros_consumidos,
                   "subtotal"          => $subtotal,
                   "multa"             => 0,
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
                   "otros"             => $otros
                  ];

                  if($this->metros->save($datosMetros)){
                      $filas_ok++;

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

                      $this->metros_traza->save($datosTraza);
                  }
                }
              }

        } 


       }else{
        echo "Debe llenar todos los datos";
       }
    }

    echo 'TSe ingresaron '.$filas_ok.' de un total de '.$filas_total;
  }

  function tomaLectura(){

        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'N° Medidor');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Id socio');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Rol');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Socio');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Sector');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Arranque');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Lectura Anterior');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Lectura Actual');

       $sheet = $objPHPExcel->getActiveSheet();

       foreach ($sheet->getColumnIterator() as $column) {
         $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
      }

      $this->validar_sesion();
      $id_apr=$this->sesión->id_apr_ses;

      // $header = [array("Customer Number", "Customer Name", "Address", "City", "State", "Zip")];
       $datosSocios = $this->arranques
             ->select("m.numero as numero_med")
             ->select("s.id as id_socio")
             ->select("s.rol")
             ->select("concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio")
              ->select("sec.nombre as sector")
             ->select("arranques.id as id_arranque") 
             ->select("ifnull((select consumo_actual from metros m where m.id = (select max(m2.id) from metros m2 where m2.id_socio = arranques.id_socio and estado <> 0)), 0) as consumo_anterior")
             ->join("medidores m", "arranques.id_medidor = m.id")
             ->join("diametro d", "m.id_diametro = d.id")
             ->join("socios s", "arranques.id_socio = s.id")
             ->join("sectores sec", "arranques.id_sector = sec.id")
             ->join("subsidios sub", "sub.id_socio = s.id", "left")
             ->join("porcentajes p", "sub.id_porcentaje = p.id", "left")
             ->join("apr_cargo_fijo cf", "cf.id_apr = s.id_apr and cf.id_diametro = m.id_diametro")
             ->where("s.id_apr", $id_apr)
             ->where("m.id_apr", $id_apr)             
             ->findAll();

      $sheet->fromArray($datosSocios, NULL, 'A2'); 

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
      header('Content-Disposition: attachment;filename="Toma_lectura.xlsx"'); 

      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
      $writer->save('php://output');

  }
}

?>