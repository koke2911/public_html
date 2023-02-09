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
}

?>