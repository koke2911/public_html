<?php

namespace App\Controllers\Formularios;

use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_convenios;
use App\Models\Formularios\Md_servicios;
use App\Models\Formularios\Md_convenio_traza;
use App\Models\Formularios\Md_convenio_detalle;

class Ctrl_convenios extends BaseController {

  protected $convenios;
  protected $convenio_traza;
  protected $convenio_detalle;
  protected $servicios;
  protected $metros;
  protected $metros_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->convenios        = new Md_convenios();
    $this->convenio_traza   = new Md_convenio_traza();
    $this->convenio_detalle = new Md_convenio_detalle();
    $this->servicios        = new Md_servicios();
    $this->metros           = new Md_metros();
    $this->metros_traza     = new Md_metros_traza();
    $this->sesión           = session();
    $this->db               = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_convenios() {
    $this->validar_sesion();
    echo $this->convenios->datatable_convenios($this->db, $this->sesión->id_apr_ses);
  }

  public function llenar_cmb_servicios() {
    $this->validar_sesion();
    $datos_servicios = $this->servicios->select("id")
                                       ->select("glosa as servicio")
                                       ->where("estado", 1)
                                       ->where("id_apr", $this->sesión->id_apr_ses)
                                       ->findAll();
    echo json_encode($datos_servicios);
  }

  public function guardar_convenio() {
    $this->validar_sesion();
    define("CREAR_CONVENIO", 1);
    define("MODIFICAR_CONVENIO", 2);
    define("PAGADO", 2);
    define("ASIGNA_MONTO_CONVENIO", 8);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_convenio    = $this->request->getPost("id_convenio");
    $id_socio       = $this->request->getPost("id_socio");
    $id_servicios   = $this->request->getPost("id_servicios");
    $detalles       = $this->request->getPost("detalles");
    $fecha_servicio = $this->request->getPost("fecha_servicio");
    $numero_cuotas  = $this->request->getPost("numero_cuotas");
    $fecha_pago     = $this->request->getPost("fecha_pago");
    $costo_servicio = $this->request->getPost("costo_servicio");

    $datosMetros = $this->metros->select("estado")
                                ->select("id as id_metros")
                                ->select("total_mes")
                                ->select("total_servicios")
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
          $valor_cuota           = $costo_servicio / $numero_cuotas;
          $nuevo_total_mes       = intval($datosMetros["total_mes"]) + intval(round($valor_cuota));
          $nuevo_total_servicios = intval($datosMetros["total_servicios"]) + intval(round($valor_cuota));

          $datosMetrosSave = [
           "total_mes"       => $nuevo_total_mes,
           "total_servicios" => $nuevo_total_servicios,
           "id"              => $datosMetros["id_metros"]
          ];

          $this->metros->save($datosMetrosSave);

          $datosMetrosTraza = [
           "id_metros"   => $datosMetros["id_metros"],
           "estado"      => ASIGNA_MONTO_CONVENIO,
           "observacion" => "Se suma el monto de la cuota del convenio asignado para este mes",
           "id_usuario"  => $id_usuario,
           "fecha"       => $fecha
          ];

          $this->metros_traza->save($datosMetrosTraza);
        }
      }
    }

    $datosConvenio = [
     "id_socio"         => $id_socio,
     "id_servicio"      => $id_servicios,
     "detalle_servicio" => $detalles,
     "fecha_servicio"   => date_format(date_create($fecha_servicio), 'Y-m-d'),
     "numero_cuotas"    => $numero_cuotas,
     "fecha_pago"       => date_format(date_create("01-" . $fecha_pago), 'Y-m-d'),
     "costo_servicio"   => $costo_servicio,
     "id_usuario"       => $id_usuario,
     "fecha"            => $fecha,
     "id_apr"           => $id_apr
    ];

    if ($id_convenio != "") {
      $estado_traza        = MODIFICAR_CONVENIO;
      $datosConvenio["id"] = $id_convenio;
    } else {
      $estado_traza            = CREAR_CONVENIO;
      $datosConvenio["estado"] = $estado;
    }

    $this->convenios->save($datosConvenio);

    if ($id_convenio == "") {
      $obtener_id  = $this->convenios->select("max(id) as id_convenio")
                                     ->first();
      $id_convenio = $obtener_id["id_convenio"];
    }

    $valor_cuota = $costo_servicio / $numero_cuotas;
    $fecha_pagos = date_format(date_create("01-" . $fecha_pago), 'Y-m-d');

    for ($i = 1; $i <= $numero_cuotas; $i ++) {
      $datosDetalle = [
       "id_convenio"  => $id_convenio,
       "fecha_pago"   => $fecha_pagos,
       "numero_cuota" => $i,
       "valor_cuota"  => intval(round($valor_cuota))
      ];

      $this->convenio_detalle->save($datosDetalle);
      $fecha_pagos = date("Y-m-d", strtotime($fecha_pagos . ' + 1 month'));
    }

    $this->guardar_traza($id_convenio, $estado_traza, "");
    $this->db->transComplete();

    if ($this->db->transStatus()) {
      echo OK;
    } else {
      echo "Error al guardar el convenio";
    }
  }

  public function eliminar_convenio() {
    define("ELIMINAR_CONVENIO", 3);
    define("RECICLAR_CONVENIO", 4);
    define("PENDIENTE", 0);
    define("ELIMINAR", 0);
    define("OK", 1);
    define("CONVENIO_ANULADO", 12);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $id_convenio = $this->request->getPost("id_convenio");
    $estado      = $this->request->getPost("estado");
    $observacion = $this->request->getPost("observacion");

    $this->db->transStart();

    $datosConvenio = [
     "id"         => $id_convenio,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha,
     "estado"     => $estado
    ];

    $this->convenios->save($datosConvenio);

    $datosConveniosTraza = [
     "id_convenio" => $id_convenio,
     "estado"      => ELIMINAR_CONVENIO,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    $this->convenio_traza->save($datosConveniosTraza);

    $datosConvenioDetalle = $this->convenio_detalle
     ->select("date_format(convenio_detalle.fecha_pago, '%m-%Y') as fecha_pago")
     ->select("convenio_detalle.valor_cuota")
     ->select("convenios.id_socio")
     ->join("convenios", "convenio_detalle.id_convenio=convenios.id")
     ->where("convenio_detalle.id_convenio", $id_convenio)
     ->where("convenio_detalle.pagado", PENDIENTE)
     ->findAll();

    foreach ($datosConvenioDetalle as $key) {
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
         "estado"      => CONVENIO_ANULADO,
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

  public function guardar_traza($id_convenio, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_convenio" => $id_convenio,
     "estado"      => $estado,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->convenio_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_convenio_traza() {
    $this->validar_sesion();
    echo view("Formularios/convenio_traza");
  }

  public function datatable_convenio_traza($id_convenio) {
    $this->validar_sesion();
    echo $this->convenio_traza->datatable_convenio_traza($this->db, $id_convenio);
  }

  public function v_convenios_reciclar() {
    $this->validar_sesion();
    echo view("Formularios/convenios_reciclar");
  }

  public function datatable_convenios_reciclar() {
    $this->validar_sesion();
    echo $this->convenios->datatable_convenios_reciclar($this->db, $this->sesión->id_apr_ses);
  }

  public function v_buscar_socio($origen) {
    $this->validar_sesion();
    $data = ['origen' => $origen];
    echo view("Formularios/buscar_socio", $data);
  }

  public function datatable_buscar_socio() {
    $this->validar_sesion();
    echo $this->convenios->datatable_buscar_socio($this->db, $this->sesión->id_apr_ses);
  }

  public function datatable_convenio_detalle($id_convenio) {
    $this->validar_sesion();
    echo $this->convenio_detalle->datatable_convenio_detalle($this->db, $id_convenio);
  }

  public function datatable_repactar_convenio($id_convenio) {
    $this->validar_sesion();
    echo $this->convenio_detalle->datatable_repactar_convenio($this->db, $id_convenio);
  }

  public function guardar_repactacion() {
    $this->validar_sesion();
    define("REPACTACION", 6);
    define("OK", 1);

    $id_convenio   = $this->request->getPost("id_convenio");
    $deuda_vigente = $this->request->getPost("deuda_vigente");
    $n_cuotas      = $this->request->getPost("n_cuotas");
    $fecha_pago    = $this->request->getPost("fecha_pago");

    $datosConvenioDetalle = $this->convenio_detalle->select("count(*) as filas")
                                                   ->where("pagado", 1)
                                                   ->where("id_convenio", $id_convenio)
                                                   ->where("date_format(fecha_pago, '%m-%Y')", $fecha_pago)
                                                   ->first();

    if ($datosConvenioDetalle["filas"] > 0) {
      echo "Seleccione el mes siguiente, ya tiene una cuota pagada en el mes seleccionado.";
      exit();
    }

    $this->db->transStart();

    $datosConvenioDetalle = $this->convenio_detalle->select("id")
                                                   ->where("pagado", 0)
                                                   ->where("id_convenio", $id_convenio)
                                                   ->findAll();

    foreach ($datosConvenioDetalle as $key) {
      $this->convenio_detalle->delete($key["id"]);
    }

    $datosConvenioDetalle = $this->convenio_detalle->select("max(numero_cuota) as max_n_cuota")
                                                   ->where("pagado", 1)
                                                   ->where("id_convenio", $id_convenio)
                                                   ->first();

    $max_n_cuota = 0;
    if ($datosConvenioDetalle != NULL) {
      $max_n_cuota = $datosConvenioDetalle["max_n_cuota"];
    }

    $valor_cuota = $deuda_vigente / $n_cuotas;
    $fecha_pagos = date_format(date_create("01-" . $fecha_pago), 'Y-m-d');

    for ($i = 0; $i < $n_cuotas; $i ++) {
      $max_n_cuota ++;

      $datosDetalle = [
       "id_convenio"  => $id_convenio,
       "fecha_pago"   => $fecha_pagos,
       "numero_cuota" => $max_n_cuota,
       "valor_cuota"  => intval(round($valor_cuota))
      ];

      $this->convenio_detalle->save($datosDetalle);
      $fecha_pagos = date("Y-m-d", strtotime($fecha_pagos . ' + 1 month'));
    }

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_convenio" => $id_convenio,
     "estado"      => REPACTACION,
     "observacion" => "Se repacta la deuda vigente a la fecha",
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    $this->convenio_traza->save($datosTraza);

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      echo OK;
    } else {
      echo "Error al guardar la repactación";
    }
  }
}

?>