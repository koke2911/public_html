<?php

namespace App\Controllers\Pagos;

use App\Models\Pagos\Md_caja;
use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Pagos\Md_caja_traza;
use App\Models\Pagos\Md_caja_detalle;
use App\Models\Formularios\Md_socios;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Formularios\Md_socios_traza;
use App\Models\Finanzas\Md_transacciones;
class Ctrl_historial_pagos extends BaseController {

  protected $metros;
  protected $metros_traza;
  protected $caja;
  protected $caja_detalle;
  protected $caja_traza;
  protected $socios;
  protected $sesión;
  protected $db;
  protected $socios_traza;
  protected $transacciones;
  
  public function __construct() {
    $this->metros       = new Md_metros();
    $this->metros_traza = new Md_metros_traza();
    $this->caja         = new Md_caja();
    $this->caja_detalle = new Md_caja_detalle();
    $this->caja_traza   = new Md_caja_traza();
    $this->socios       = new Md_socios();
    $this->sesión       = session();
    $this->db           = \Config\Database::connect();
    $this->socios_traza     = new Md_socios_traza();
    $this->transacciones       = new Md_transacciones();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_historial_pagos() {
    $this->validar_sesion();
    echo $this->caja->datatable_historial_pagos($this->db, $this->sesión->id_apr_ses, "", "", "");
  }

  public function datatable_historial_pagos_busqueda($datosBusqueda) {
    $this->validar_sesion();
    $datos = explode(",", $datosBusqueda);
    echo $this->caja->datatable_historial_pagos($this->db, $this->sesión->id_apr_ses, $datos[0], $datos[1], $datos[2]);
  }

  public function datatable_detalle_pago($id_caja) {
    $this->validar_sesion();
    echo $this->caja_detalle->datatable_detalle_pago($this->db, $id_caja);
  }

  public function v_pago_traza($id_caja) {
    $this->validar_sesion();

    $datos = ["id_caja" => $id_caja];
    echo view("Pagos/pago_traza", $datos);
  }

  public function datatable_pago_traza($id_caja) {
    $this->validar_sesion();
    echo $this->caja_traza->datatable_pago_traza($this->db, $id_caja);
  }

  public function anular_pago()
  {
    $this->validar_sesion();

    define("ANULADO", 0);
    define("ANULADO_TRAZA", 2);
    define("PENDIENTE", 1);
    define("PAGO_ANULADO", 6);
    define("OK", 1);

    $id_caja    = $this->request->getPost("id_caja");
    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;


    /*
     * ==========================================================
     * ANULAR PAGO
     * ==========================================================
     */

    $datosPago = [
      "id"         => $id_caja,
      "estado"     => ANULADO,
      "id_usuario" => $id_usuario,
      "fecha"      => $fecha
    ];


    if ($this->caja->save($datosPago)) {

      /*
         * ======================================================
         * TRAZA DEL PAGO
         * ======================================================
         */

      $datosPagoTraza = [
        "id_caja"    => $id_caja,
        "estado"     => ANULADO_TRAZA,
        "id_usuario" => $id_usuario,
        "fecha"      => $fecha
      ];

      if (!$this->caja_traza->save($datosPagoTraza)) {
        echo "Error al registrar la traza del pago";
        return;
      }


      /*
         * ======================================================
         * VOLVER MEDIDORES A PENDIENTE
         * ======================================================
         */

      $datosPagoDetalle = $this->caja_detalle
        ->select("*")
        ->where("id_caja", $id_caja)
        ->findAll();


      foreach ($datosPagoDetalle as $key) {

        $datosMetros = [
          "id"         => $key["id_metros"],
          "estado"     => PENDIENTE,
          "id_usuario" => $id_usuario,
          "fecha"      => $fecha
        ];


        if ($this->metros->save($datosMetros)) {

          $datosMetrosTraza = [
            "id_metros"  => $key["id_metros"],
            "estado"     => PAGO_ANULADO,
            "id_usuario" => $id_usuario,
            "fecha"      => $fecha
          ];

          if (!$this->metros_traza->save($datosMetrosTraza)) {

            echo "Error al registrar traza al registro de metros";
            return;
          }
        }
      }


      /*
         * ======================================================
         * RESTAURAR ABONO DEL SOCIO
         * ======================================================
         */

      $datosSocios = $this->socios
        ->select("socios.id,socios.abono,c.abono as abono_caja")
        ->join("caja c", "c.id_socio=socios.id")
        ->where("c.id", $id_caja)
        ->findAll();


      if ($datosSocios != null) {

        $id_socio   = $datosSocios[0]["id"];
        $abono_socio = $datosSocios[0]["abono"];
        $abono_caja  = $datosSocios[0]["abono_caja"];

        $anula_abono = $abono_socio + $abono_caja;


        $datosSocioSave = [
          "id"         => $id_socio,
          "abono"      => $anula_abono,
          "id_usuario" => $id_usuario,
          "fecha"      => $fecha
        ];

        $this->socios->save($datosSocioSave);


        $datosSociosTraza = [
          "id_socio"   => $id_socio,
          "estado"     => 8,
          "id_usuario" => $id_usuario,
          "fecha"      => $fecha
        ];

        $this->socios_traza->save(
          $datosSociosTraza
        );
      }


      /*
         * ======================================================
         * ANULAR TRANSACCIÓN ASOCIADA AL PAGO
         * ======================================================
         *
         * La glosa guardada es:
         *
         * Pago modulo Caja: Transferencia XXXXX Caja N° 123
         *
         * Buscamos específicamente la transacción cuyo concepto
         * termina con "Caja N° {id_caja}".
         * ======================================================
         */

      $movimientosTransferencia = $this->transacciones
        ->select("id, id_cuenta")
        ->where("id_apr", $id_apr)
        ->where("estado", 1)        
        ->like(
          "concepto",
          "Caja N° " . $id_caja,
          "before"
        )
        ->findAll();


      /*
         * Si el pago fue transferencia debería existir
         * un movimiento asociado.
         *
         * Si fue efectivo u otra forma de pago,
         * simplemente no encontrará ninguno.
         */
      if ($movimientosTransferencia != null) {

        $cuentasRecalcular = [];


        foreach ($movimientosTransferencia as $movimiento) {

          /*
                 * Marcar transacción como anulada.
                 */
          $datosMovimiento = [
            "id"         => $movimiento["id"],
            "estado"     => 0,
            "id_usuario" => $id_usuario,
            "fecha"      => $fecha
          ];


          if (!$this->transacciones->save($datosMovimiento)) {

            echo "Error al anular la transacción bancaria";
            return;
          }

          $cuentasRecalcular[$movimiento["id_cuenta"]] = $movimiento["id_cuenta"];
        }

        foreach ($cuentasRecalcular as $id_cuenta) {

          $this->transacciones->recalcular_saldos_cuenta(
            $id_cuenta,
            $this->sesión->id_apr_ses
          );
        }
      }


      echo OK;
    } else {

      echo "Error al anular el pago";
    }
  }

  public function datatable_buscar_socio() {
    $this->validar_sesion();
    $id_apr = $this->sesión->id_apr_ses;

    $datosSocios = $this->socios->select("id as id_socio")
                                ->select("concat(rut, '-', dv) as rut")
                                ->select("rol")
                                ->select("concat(nombres, ' ', ape_pat, ' ', ape_mat) as nombre")
                                ->select("date_format(fecha_entrada, '%d-%m-%Y') as fecha_entrada")
                                ->where("estado", 1)
                                ->where("id_apr", $id_apr)
                                ->findAll();

    foreach ($datosSocios as $key) {
      $row = [
       "id_socio"      => $key["id_socio"],
       "rut"           => $key["rut"],
       "rol"           => $key["rol"],
       "nombre"        => $key["nombre"],
       "fecha_entrada" => $key["fecha_entrada"],
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];
      echo json_encode($salida);
    } else {
      echo "{ \"data\": [] }";
    }
  }
}