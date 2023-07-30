<?php

namespace App\Controllers\Consumo;

use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_arranques;
use App\Models\Configuracion\Md_costo_metros;
use App\Models\Formularios\Md_convenio_detalle;
use App\Models\Formularios\Md_repactaciones_detalle;

class Ctrl_metros extends BaseController {

  protected $metros;
  protected $metros_traza;
  protected $costo_metros;
  protected $convenio_detalle;
  protected $repactaciones_detalle;
  protected $arranques;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->metros                = new Md_metros();
    $this->metros_traza          = new Md_metros_traza();
    $this->costo_metros          = new Md_costo_metros();
    $this->convenio_detalle      = new Md_convenio_detalle();
    $this->repactaciones_detalle = new Md_repactaciones_detalle();
    $this->arranques             = new Md_arranques();
    $this->sesión                = session();
    $this->db                    = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_metros() {
    $this->validar_sesion();
    define("ELIMINADO", 0);

    $datosMetros = $this->metros
     ->select("metros.id as id_metros")
     ->select("metros.id_socio")
     ->select("concat(soc.rut, '-', soc.dv) as rut_socio")
     ->select("soc.rol as rol_socio")
     ->select("concat(soc.nombres, ' ', soc.ape_pat, ' ', soc.ape_mat) as nombre_socio")
     ->select("a.id as id_arranque")
     ->select("ifnull(p.glosa, '0%') as subsidio")
     ->select("(select tope_subsidio from apr where id = metros.id_apr) as tope_subsidio")
     ->select("ifnull(metros.monto_subsidio, 0) as monto_subsidio")
     ->select("sec.nombre as sector")
     ->select("med.id_diametro")
     ->select("d.glosa as diametro")
     ->select("date_format(metros.fecha_ingreso, '%m-%Y') as fecha_ingreso")
     ->select("date_format(metros.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento")
     ->select("metros.consumo_anterior")
     ->select("metros.consumo_actual")
     ->select("metros.metros")
     ->select("ifnull(metros.subtotal, 0) as subtotal")
     ->select("ifnull(metros.multa, 0) as multa")
     ->select("ifnull(metros.total_servicios, 0) as total_servicios")
     ->select("ifnull(metros.cuota_repactacion, 0) as cuota_repactacion")
     ->select("ifnull(metros.total_mes, 0) as total_mes")
     ->select("ifnull(metros.cargo_fijo, 0) as cargo_fijo")
     ->select("ifnull(metros.monto_facturable, 0) as monto_facturable")
     ->select("ifnull(metros.alcantarillado, 0) as alcantarillado")
     ->select("ifnull(metros.cuota_socio, 0) as cuota_socio")
     ->select("ifnull(metros.otros, 0) as otros")
     ->select("ifnull(metros.iva, 0) as iva")
     ->select("u.usuario")
     ->select("date_format(metros.fecha, '%d-%m-%Y') as fecha")
     ->select("a.tarifa as tarifa")
     ->select("cf.sin_consumo as sin_consumo")
     ->join("socios soc", "metros.id_socio = soc.id")
     ->join("arranques a", "a.id_socio = soc.id")
     ->join("sectores sec", "a.id_sector = sec.id")
     ->join("subsidios sub", "sub.id_socio = soc.id", "left")
     ->join("porcentajes p", "sub.id_porcentaje = p.id", "left")
     ->join("usuarios u", "metros.id_usuario = u.id")
     ->join("medidores med", "a.id_medidor = med.id")
     ->join("diametro d", "med.id_diametro = d.id")
     ->join("apr_cargo_fijo cf", "a.tarifa = cf.tarifa and cf.id_diametro=med.id_diametro and cf.id_apr=metros.id_apr")
     ->where("metros.estado <>", ELIMINADO)
     ->where("metros.id_apr", $this->sesión->id_apr_ses)
     ->orderBy("metros.fecha_vencimiento", "asc")
     ->limit(10000)
     ->findAll();

    $salida = ['data' => $datosMetros];

    return json_encode($salida);
  }

  public function guardar_metros() {
    $this->validar_sesion();
    define("INGRESO_METROS", 1);
    define("MODIFICAR_METROS", 2);

    define("OK", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;

    $id_metros         = $this->request->getPost("id_metros");
    $id_socio          = $this->request->getPost("id_socio");
    $monto_subsidio    = $this->request->getPost("monto_subsidio");
    $fecha_ingreso     = "01-" . $this->request->getPost("fecha_ingreso");
    $fecha_vencimiento = $this->request->getPost("fecha_vencimiento");
    $consumo_anterior  = $this->request->getPost("consumo_anterior");
    $consumo_actual    = $this->request->getPost("consumo_actual");
    $metros            = $this->request->getPost("metros");
    $subtotal          = $this->request->getPost("subtotal");
    $multa             = $this->request->getPost("multa");
    $total_servicios   = $this->request->getPost("total_servicios");
    $cuota_repactacion = $this->request->getPost("cuota_repactacion");
    $total_mes         = $this->request->getPost("total_mes");
    $cargo_fijo        = $this->request->getPost("cargo_fijo");
    $monto_facturable  = $this->request->getPost("monto_facturable");
    $alcantarillado    = $this->request->getPost("alcantarillado");
    $cuota_socio       = $this->request->getPost("cuota_socio");
    $otros             = $this->request->getPost("otros");
    $iva               = $this->request->getPost("iva");

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
     "iva"               => $iva
    ];

    if ($id_metros != "") {
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
     "id_metros"   => $id_metros,
     "estado"      => $estado_traza,
     "observacion" => '',
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    $this->metros_traza->save($datosTraza);

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      echo OK;
    } else {
      $respuesta = [
       "estado"  => "Error",
       "mensaje" => "Error al ingresar la lectura"
      ];

      return json_encode($respuesta);
    }
  }

  public function eliminar_metros() {
    $this->validar_sesion();
    define("ELIMINAR_METROS", 3);
    define("ELIMINAR", 0);
    define("OK", 1);
    $estado       = ELIMINAR;
    $estado_traza = ELIMINAR_METROS;

    $id_metros   = $this->request->getPost("id_metros");
    $observacion = $this->request->getPost("observacion");

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosMetros = [
     "id"         => $id_metros,
     "estado"     => $estado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    $this->db->transStart();
    $this->metros->save($datosMetros);

    $datosTraza = [
     "id_metros"   => $id_metros,
     "estado"      => $estado_traza,
     "observacion" => $observacion,
     "id_usuario"  => $id_usuario,
     "fecha"       => $fecha
    ];

    $this->metros_traza->save($datosTraza);

    $this->db->transComplete();

    if ($this->db->transStatus()) {
      $respuesta = [
       "estado"  => OK,
       "mensaje" => "Lectura eliminada con éxito"
      ];

      return json_encode($respuesta);
    } else {
      $respuesta = [
       "estado"  => "Error",
       "mensaje" => "Error al eliminar la lectura"
      ];

      return json_encode($respuesta);
    }
  }

  public function v_metros_traza() {
    $this->validar_sesion();
    echo view("Consumo/metros_traza");
  }

  public function datatable_metros_traza($id_metros) {
    $this->validar_sesion();
    echo $this->metros_traza->datatable_metros_traza($this->db, $id_metros);
  }

  public function datatable_buscar_socio() {
    $this->validar_sesion();
    define("ACTIVO", 1);

    $datosSocios = $this->arranques
     ->select("s.id as id_socio")
     ->select("concat(s.rut, '-', s.dv) as rut")
     ->select("s.rol")
     ->select("concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre")
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
     ->select("arranques.tarifa")
     ->select("cf.sin_consumo")
     ->join("medidores m", "arranques.id_medidor = m.id")
     ->join("diametro d", "m.id_diametro = d.id")
     ->join("socios s", "arranques.id_socio = s.id")
     ->join("sectores sec", "arranques.id_sector = sec.id")
     ->join("subsidios sub", "sub.id_socio = s.id", "left")
     ->join("porcentajes p", "sub.id_porcentaje = p.id", "left")
     ->join("apr_cargo_fijo cf", "cf.id_apr = s.id_apr and cf.id_diametro = m.id_diametro and cf.tarifa=arranques.tarifa")
     ->where("s.id_apr", $this->sesión->id_apr_ses)
     ->where("s.estado", ACTIVO)
     ->findAll();

    $salida = ['data' => $datosSocios];

    return json_encode($salida);
  }

  public function datatable_costo_metros($consumo_actual, $id_diametro,$id_tarifa) {
    $this->validar_sesion();
    echo $this->costo_metros->datatable_costo_metros_consumo($this->db, $this->sesión->id_apr_ses, $id_diametro, $consumo_actual,$id_tarifa);
  }

  public function calcular_total_servicios() {
    $fecha_vencimiento = date_format(date_create($this->request->getPost("fecha_vencimiento")), 'm-Y');
    $id_socio          = $this->request->getPost("id_socio");
    define("ACTIVO", 1);

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

    return json_encode($datosServicios);
  }

  public function existe_consumo_mes() {
    $id_socio      = $this->request->getPost("id_socio");
    $fecha_ingreso = $this->request->getPost("fecha_ingreso");

    $existe_consumo_mes = $this->metros->select("count(*) as filas")
                                       ->where("id_socio", $id_socio)
                                       ->where("date_format(fecha_ingreso, '%m-%Y')", $fecha_ingreso)
                                       ->where("estado", 1)
                                       ->first();
    $filas              = $existe_consumo_mes["filas"];

    echo $filas;
  }

  public function v_importar_planilla() {
    $this->validar_sesion();
    // echo view("Consumo/importar_planilla");

    echo view("Consumo/importar_lectura");
  }
}

?>