<?php

namespace App\Controllers\Consumo;

use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Formularios\Md_socios;
use App\Models\Formularios\Md_diametro;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_arranques;
use App\Models\Formularios\Md_medidores;
use App\Models\Configuracion\Md_costo_metros;
use App\Models\Formularios\Md_convenio_detalle;

class Ctrl_importar_planilla extends BaseController {

  protected $sesión;
  protected $db;
  protected $socios;
  protected $metros;
  protected $convenio_detalle;
  protected $costo_metros;
  protected $arranques;
  protected $medidores;
  protected $diametro;
  protected $apr;
  protected $metros_traza;
  protected $string_error = "";

  public function __construct() {
    $this->sesión           = session();
    $this->db               = \Config\Database::connect();
    $this->socios           = new Md_socios();
    $this->metros           = new Md_metros();
    $this->convenio_detalle = new Md_convenio_detalle();
    $this->costo_metros     = new Md_costo_metros();
    $this->arranques        = new Md_arranques();
    $this->medidores        = new Md_medidores();
    $this->diametro         = new Md_diametro();
    $this->apr              = new Md_apr();
    $this->metros_traza     = new Md_metros_traza();
  }

  public function importar_planilla() {
    if ($this->request->getMethod() == "post") {
      $ruta = "uploads/";

      if (!is_dir($ruta)) {
        mkdir($ruta, 0755);
      }

      $file = $this->request->getFile("archivos");

      if (!$file->isValid()) {
        throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
      } else {
        $name_file = $file->getName();
        $file->move($ruta);

        if ($file->hasMoved()) {
          $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
          $spreadsheet = $reader->load($ruta . $name_file);
          $sheet       = $spreadsheet->getSheet(0);

          $builder = $this->db->table("metros");

          $arr_errors = [];

          foreach ($sheet->getRowIterator(2) as $row) {
            $rol            = trim($sheet->getCellByColumnAndRow(2, $row->getRowIndex()));
            $consumo_actual = trim($sheet->getCellByColumnAndRow(8, $row->getRowIndex()));

            $datosSocio = $this->socios->select("id as id_socio")
                                       ->select("concat(rut, '-', dv) as rut_socio")
                                       ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre_socio")
                                       ->where("rol", $rol)
                                       ->where("id_apr", $this->sesión->id_apr_ses)
                                       ->first();
            $id_socio   = $datosSocio["id_socio"];

            if ($id_socio != "") {
              $fecha_ingreso     = $this->request->getPost("fecha_ingreso");
              $fecha_vencimiento = $this->request->getPost("fecha_vencimiento");

              $existe_consumo_mes = $this->metros->select("count(*) as filas")
                                                 ->where("id_socio", $id_socio)
                                                 ->where("date_format(fecha_vencimiento, '%m-%Y')", date_format(date_create($fecha_vencimiento), 'm-Y'))
                                                 ->where("estado", 1)
                                                 ->first();
              $filas              = $existe_consumo_mes["filas"];

              if ($filas > 0) {
                $this->string_error .= "Id Socio: " . $id_socio . ",<br>";
                $this->string_error .= "RUT Socio: " . $datosSocio["rut_socio"] . ",<br>";
                $this->string_error .= "ROL Socio: " . $rol . ",<br>";
                $this->string_error .= "Nombre Socio: " . $datosSocio["nombre_socio"] . ",<br>";
                $this->string_error .= "Error: " . "Ya existe registro de consumo este mes. <br><br>";
              } else {
                $total_servicios = $this->convenio_detalle->calcular_total_servicios($this->db, $fecha_vencimiento, $id_socio);
                $datosMetros     = $this->metros->select("max(id) as id_metros")
                                                ->where("id_socio", $id_socio)
                                                ->where("estado", 1)
                                                ->first();
                $datosMetros     = $this->metros->select("ifnull(consumo_actual, 0) as consumo_anterior")
                                                ->where("id", $datosMetros["id_metros"])
                                                ->first();

                if ($datosMetros != "") {
                  $consumo_anterior = $datosMetros["consumo_anterior"];
                } else {
                  $consumo_anterior = 0;
                }

                $metros_consumidos = intval($consumo_actual) - intval($consumo_anterior);
                $datosArranque     = $this->arranques->select("id_medidor")
                                                     ->where("id_socio", $id_socio)
                                                     ->first();

                if ($datosArranque != "" and $datosArranque["id_medidor"] != "") {
                  $datosMedidor = $this->medidores->select("id_diametro")
                                                  ->where("id", $datosArranque["id_medidor"])
                                                  ->first();
                  $id_diametro  = $datosMedidor["id_diametro"];

                  $datosCostoMetros = json_decode($this->costo_metros->datatable_costo_metros_consumo($this->db, $this->sesión->id_apr_ses, $id_diametro, 0));

                  $datosApr      = $this->apr->select("tope_subsidio")
                                             ->where("id", $this->sesión->id_apr_ses)
                                             ->first();
                  $tope_subsidio = $datosApr["tope_subsidio"];

                  $subtotal       = 0;
                  $total_subsidio = 0;

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
                            $total_subsidio = $subtotal;
                          }
                        }
                      }
                    }
                  }

                  $total_mes  = intval($subtotal) + intval($total_servicios) - intval($total_subsidio);
                  $fecha      = date("Y-m-d H:i:s");
                  $id_usuario = $this->sesión->id_usuario_ses;
                  $id_apr     = $this->sesión->id_apr_ses;

                  $datosMetrosSave = [
                   "id_socio"          => $id_socio,
                   "monto_subsidio"    => $total_subsidio,
                   "fecha_ingreso"     => date_format(date_create($fecha_ingreso), 'Y-m-d'),
                   "fecha_vencimiento" => date_format(date_create($fecha_vencimiento), 'Y-m-d'),
                   "consumo_anterior"  => $consumo_anterior,
                   "consumo_actual"    => $consumo_actual,
                   "metros"            => $metros_consumidos,
                   "subtotal"          => $subtotal,
                   "multa"             => 0,
                   "total_servicios"   => $total_servicios,
                   "total_mes"         => $total_mes,
                   "id_usuario"        => $id_usuario,
                   "fecha"             => $fecha,
                   "id_apr"            => $id_apr
                  ];

                  if ($this->metros->save($datosMetrosSave)) {
                    $obtener_id = $this->metros->select("max(id) as id_metros")
                                               ->first();
                    $id_metros  = $obtener_id["id_metros"];

                    $datosTraza = [
                     "id_metros"   => $id_metros,
                     "estado"      => 1,
                     "observacion" => "Ingresado por planilla",
                     "id_usuario"  => $id_usuario,
                     "fecha"       => $fecha
                    ];

                    if (!$this->metros_traza->save($datosTraza)) {
                      $this->string_error .= "Id Socio: " . $id_socio . ",<br>";
                      $this->string_error .= "RUT Socio: " . $datosSocio["rut_socio"] . ",<br>";
                      $this->string_error .= "ROL Socio: " . $rol . ",<br>";
                      $this->string_error .= "Nombre Socio: " . $datosSocio["nombre_socio"] . ",<br>";
                      $this->string_error .= "Error: " . "Error al guardar la traza. <br><br>";
                    }
                  } else {
                    $this->string_error .= "Id Socio: " . $id_socio . ",<br>";
                    $this->string_error .= "RUT Socio: " . $datosSocio["rut_socio"] . ",<br>";
                    $this->string_error .= "ROL Socio: " . $rol . ",<br>";
                    $this->string_error .= "Nombre Socio: " . $datosSocio["nombre_socio"] . ",<br>";
                    $this->string_error .= "Error: " . "No se pudo guardar el consumo de metros. <br><br>";
                  }
                } else {
                  $this->string_error .= "Id Socio: " . $id_socio . ",<br>";
                  $this->string_error .= "RUT Socio: " . $datosSocio["rut_socio"] . ",<br>";
                  $this->string_error .= "ROL Socio: " . $rol . ",<br>";
                  $this->string_error .= "Nombre Socio: " . $datosSocio["nombre_socio"] . ",<br>";
                  $this->string_error .= "Error: " . "Socio no tiene configurado el arranque en el sistema. <br><br>";
                }
              }
            } else {
              $this->string_error .= "Id Socio: Sin identificador,<br>";
              $this->string_error .= "RUT Socio: Sin RUT,<br>";
              $this->string_error .= "ROL Socio: " . $rol . ",<br>";
              $this->string_error .= "Nombre Socio: Sin Nombre,<br>";
              $this->string_error .= "Error: " . "No existe usuario en base de datos. <br><br>";
            }
          }

          if ($this->string_error != "") {
            $error = ['error' => $this->string_error];
            echo json_encode($error);
          } else {
            echo "[]";
          }
        } else {
          echo "Error al subir archivo excel";
        }

        if (!unlink($ruta . $name_file)) {
          echo "Error al eliminar archivo excel";
        }
      }
    } else {
      echo "No se han recibido los datos de la planilla, favor actualice el sitio he intente nuevamente";
    }
  }
}

?>