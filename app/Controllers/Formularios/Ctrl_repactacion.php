<?php

namespace App\Controllers\Formularios;

use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_repactaciones;
use App\Models\Formularios\Md_repactaciones_traza;
use App\Models\Formularios\Md_repactaciones_detalle;

class Ctrl_repactacion extends BaseController {

  protected $repactaciones;
  protected $repactaciones_traza;
  protected $repactaciones_detalle;
  protected $metros;
  protected $metros_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->repactaciones         = new Md_repactaciones();
    $this->repactaciones_traza   = new Md_repactaciones_traza();
    $this->repactaciones_detalle = new Md_repactaciones_detalle();
    $this->metros                = new Md_metros();
    $this->metros_traza          = new Md_metros_traza();
    $this->sesión                = session();
    $this->db                    = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_repactaciones() {
    $this->validar_sesion();
    define("ACTIVO", 1);

    $data = $this->repactaciones
     ->select("repactaciones.id as id_repactacion")
     ->select("repactaciones.id_socio")
     ->select("concat(socios.rut, '-', socios.dv) as rut_socio,")
     ->select("socios.rol as rol_socio,")
     ->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre_socio,")
     ->select("repactaciones.deuda_vigente as deuda_total,")
     ->select("repactaciones.n_cuotas,")
     ->select("date_format(repactaciones.fecha_pago, '%d-%m-%Y') as fecha_pago,")
     ->select("usuarios.usuario,")
     ->select("date_format(repactaciones.fecha, '%d-%m-%Y %H:%m:%s') as fecha")
     ->join("socios", "repactaciones.id_socio=socios.id")
     ->join("usuarios", "repactaciones.id_usuario=usuarios.id")
     ->where("repactaciones.id_apr", $this->sesión->id_apr_ses)
     ->where("repactaciones.estado", ACTIVO)
     ->findAll();

    $salida = ['data' => $data];

    return json_encode($salida);
  }

  public function guardar_repactacion() {
    $this->validar_sesion();
    define("INGRESAR_REPACTACION", 1);
    define("PAGADO", 2);
    define("REPACTACION_DEUDA", 9);
    define("PAGADO_REPACTACION", 10);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_repactacion = $this->request->getPost("id_repactacion");
    $id_socio       = $this->request->getPost("id_socio");
    $deuda_vigente  = $this->request->getPost("deuda_vigente");
    $numero_cuotas  = $this->request->getPost("numero_cuotas");
    $valor_cuota    = $this->request->getPost("valor_cuota");
    $fecha_pago     = $this->request->getPost("fecha_pago");
    $datos_deuda    = $this->request->getPost("datos_deuda");

    $datosMetros = $this->metros->select("estado")
                                ->select("id as id_metros")
                                ->select("total_mes")
                                ->select("cuota_repactacion")
                                ->where("date_format(fecha_vencimiento, '%m-%Y')", $fecha_pago)
                                ->whereNotIn("estado", [0])
                                ->where("id_socio", $id_socio)
                                ->first();

    $this->db->transStart();

    if ($datosMetros != NULL) {
      if ($datosMetros["estado"] == PAGADO) {
        echo "Deuda del mes de pago, ya se encuentra pagada, seleccione el mes siguiente";
        exit();
      } else {
        if ($datosMetros["estado"] == ACTIVO) {
          $nuevo_total_mes   = intval($datosMetros["total_mes"]) + intval(round($valor_cuota));
          $nuevo_valor_cuota = intval($datosMetros["cuota_repactacion"]) + intval(round($valor_cuota));

          $datosMetrosSave = [
           "total_mes"         => $nuevo_total_mes,
           "cuota_repactacion" => $nuevo_valor_cuota,
           "id"                => $datosMetros["id_metros"],
           "id_usuario"        => $id_usuario,
           "fecha"             => $fecha
          ];

          $this->metros->save($datosMetrosSave);

          $observacion = "";

          foreach ($datos_deuda as $key) {
            $observacion .= "Id. Metros: " . $key["id_metros"] . ", deuda: " . $key["deuda"] . ", fecha de vencimiento: " . $key["fecha_vencimiento"] . ". ";
          }

          $datosMetrosTraza = [
           "id_metros"   => $datosMetros["id_metros"],
           "estado"      => REPACTACION_DEUDA,
           "observacion" => $observacion,
           "id_usuario"  => $id_usuario,
           "fecha"       => $fecha
          ];

          $this->metros_traza->save($datosMetrosTraza);
        }
      }
    }

    $datosRepactacion = [
     "id_socio"      => $id_socio,
     "deuda_vigente" => $deuda_vigente,
     "n_cuotas"      => $numero_cuotas,
     "fecha_pago"    => date_format(date_create("01-" . $fecha_pago), 'Y-m-d'),
     "id_usuario"    => $id_usuario,
     "fecha"         => $fecha,
     "id_apr"        => $id_apr
    ];

    $this->repactaciones->save($datosRepactacion);

    $obtener_id     = $this->repactaciones->select("max(id) as id_repactacion")
                                          ->first();
    $id_repactacion = $obtener_id["id_repactacion"];

    $fecha_pagos = date_format(date_create("01-" . $fecha_pago), 'Y-m-d');

    for ($i = 1; $i <= $numero_cuotas; $i ++) {
      $datosDetalle = [
       "id_repactacion" => $id_repactacion,
       "fecha_pago"     => $fecha_pagos,
       "numero_cuota"   => $i,
       "valor_cuota"    => $valor_cuota
      ];

      $this->repactaciones_detalle->save($datosDetalle);
      $fecha_pagos = date("Y-m-d", strtotime($fecha_pagos . ' + 1 month'));
    }

    $datosRepactacionTraza = [
     "id_repactacion" => $id_repactacion,
     "estado"         => INGRESAR_REPACTACION,
     "id_usuario"     => $id_usuario,
     "fecha"          => $fecha
    ];

    $this->repactaciones_traza->save($datosRepactacionTraza);

    foreach ($datos_deuda as $key) {
      $datosMetrosSave2 = [
       "id"         => $key["id_metros"],
       "estado"     => PAGADO,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      $this->metros->save($datosMetrosSave2);

      $datosMetrosTraza2 = [
       "id_metros"   => $key["id_metros"],
       "estado"      => PAGADO_REPACTACION,
       "observacion" => "Pagado por repactación, Id repactación: " . $id_repactacion . ".",
       "id_usuario"  => $id_usuario,
       "fecha"       => $fecha
      ];

      $this->metros_traza->save($datosMetrosTraza2);
    }

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      echo OK;
    } else {
      echo "Error al ingresar la repactación";
    }
  }

  public function anular_repactacion() {
    define("ANULAR_REPACTACION", 2);
    define("ANULAR", 0);
    define("PENDIENTE", 0);
    define("OK", 1);
    define("REPACTACION_ANULADA", 11);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_repactacion = $this->request->getPost("id_repactacion");
    $observacion    = $this->request->getPost("observacion");

    $this->db->transStart();

    $datosRepactacion = [
     "id"         => $id_repactacion,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => ANULAR
    ];

    $this->repactaciones->save($datosRepactacion);

    $datosRepactacionTraza = [
     "id_repactacion" => $id_repactacion,
     "estado"         => ANULAR_REPACTACION,
     "id_usuario"     => $id_usuario,
     "fecha"          => $fecha
    ];

    $this->repactaciones_traza->save($datosRepactacionTraza);

    $datosRepactacionDetalle = $this->repactaciones_detalle
     ->select("date_format(repactaciones_detalle.fecha_pago, '%m-%Y') as fecha_pago")
     ->select("repactaciones_detalle.valor_cuota")
     ->select("repactaciones.id_socio")
     ->join("repactaciones", "repactaciones_detalle.id_repactacion=repactaciones.id")
     ->where("repactaciones_detalle.id_repactacion", $id_repactacion)
     ->where("repactaciones_detalle.pagado", PENDIENTE)
     ->findAll();

    foreach ($datosRepactacionDetalle as $key) {
      $datosMetros = $this->metros->select("id as id_metros")
                                  ->select("total_mes")
                                  ->select("total_servicios")
                                  ->where("id_socio", $key["id_socio"])
                                  ->where("date_format(fecha_vencimiento, '%m-%Y')", $key["fecha_pago"])
                                  ->where("estado", 1)
                                  ->first();

      if ($datosMetros != NULL) {
        $nuevo_total_servicios = intval($datosMetros["total_servicios"]) - intval($key["valor_cuota"]);
        $nuevo_total_mes       = intval($datosMetros["total_mes"]) - intval($key["valor_cuota"]);

        $datosMetrosSave = [
         "total_mes"       => $nuevo_total_mes,
         "total_servicios" => $nuevo_total_servicios,
         "id"              => $datosMetros["id_metros"],
         "id_usuario"      => $id_usuario,
         "fecha"           => $fecha
        ];

        $this->metros->save($datosMetrosSave);

        $datosMetrosTraza = [
         "id_metros"   => $datosMetros["id_metros"],
         "estado"      => REPACTACION_ANULADA,
         "observacion" => "Valor restado al total de servicios y al total del mes: $" . $key["valor_cuota"] . ".",
         "id_usuario"  => $id_usuario,
         "fecha"       => $fecha
        ];

        $this->metros_traza->save($datosMetrosTraza);
      }
    }

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      echo OK;
    } else {
      echo "Error al anular la repactación";
    }
  }

  public function v_repactar_traza() {
    $this->validar_sesion();
    echo view("Formularios/repactar_traza");
  }

  public function datatable_repactar_traza($id_repactacion) {
    $this->validar_sesion();
    $data = $this->repactaciones_traza
     ->select("repactaciones_traza_estados.glosa as estado")
     ->select("ifnull(repactaciones_traza.observacion, 'No registrado') as observacion")
     ->select("usuarios.usuario")
     ->select("date_format(repactaciones_traza.fecha, '%d-%m-%Y %H:%i:%s') as fecha")
     ->join("usuarios", "repactaciones_traza.id_usuario = usuarios.id")
     ->join("repactaciones_traza_estados", "repactaciones_traza.estado = repactaciones_traza_estados.id")
     ->where("repactaciones_traza.id_repactacion", $id_repactacion)
     ->findAll();

    $salida = ['data' => $data];

    return json_encode($salida);
  }

  public function datatable_repactacion_detalle($id_repactacion) {
    $this->validar_sesion();
    $data = $this->repactaciones_detalle
     ->select("repactaciones_detalle.id")
     ->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as socio")
     ->select("date_format(repactaciones_detalle.fecha_pago, '%m-%Y') as fecha_pago")
     ->select("repactaciones_detalle.numero_cuota")
     ->select("repactaciones_detalle.valor_cuota")
     ->join("repactaciones", "repactaciones_detalle.id_repactacion=repactaciones.id")
     ->join("socios", "repactaciones.id_socio=socios.id")
     ->where("id_repactacion", $id_repactacion)
     ->findAll();

    $salida = ['data' => $data];

    return json_encode($salida);
  }
}

?>