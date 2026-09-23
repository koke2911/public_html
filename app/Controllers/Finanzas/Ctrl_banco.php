<?php

namespace App\Controllers\Finanzas;

use App\Models\Finanzas\Md_bancos;
use App\Models\Finanzas\Md_cuentas;
use App\Controllers\BaseController;
use App\Models\Finanzas\Md_cuentas_traza;
use App\Models\Finanzas\Md_banco_tipo_cuenta;
use App\Models\Finanzas\Md_transacciones;
use App\Models\Finanzas\Md_transacciones_traza;

class Ctrl_banco extends BaseController
{

    protected $cuentas;
    protected $cuentas_traza;
    protected $bancos;
    protected $banco_tipo_cuenta;
    protected $transacciones;
    protected $transacciones_traza;
    protected $sesión;
    protected $db;

    public function __construct()
    {
        $this->cuentas             = new Md_cuentas();
        $this->cuentas_traza       = new Md_cuentas_traza();
        $this->bancos              = new Md_bancos();
        $this->banco_tipo_cuenta   = new Md_banco_tipo_cuenta();
        $this->transacciones       = new Md_transacciones();
        $this->transacciones_traza = new Md_transacciones_traza();
        $this->sesión              = session();
        $this->db                  = \Config\Database::connect();
    }

    public function validar_sesion()
    {
        if (!$this->sesión->has("id_usuario_ses")) {
            echo "La sesión expiró, actualice el sitio web con F5";
            exit();
        }
    }

    public function llenar_cmb_cuentas()
    {
        $this->validar_sesion();

        $id_apr = $this->sesión->id_apr_ses;

        $datosCuentas = $this->db->table('cuentas c')
            ->select('c.id')
            ->select("CONCAT(t.glosa, ' N° ', c.n_cuenta, ' ', b.nombre_banco) as glosa_cuenta", false)
            ->join('bancos b', 'b.id = c.id_banco')
            ->join('banco_tipo_cuenta t', 't.id = c.id_tipo_cuenta')
            ->where('c.id_apr', $id_apr)
            ->where('c.estado', 1)
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($datosCuentas as $key) {
            $row = [
                "id_cuenta"          => $key["id"],
                "nombre_tipo_cuenta" => $key["glosa_cuenta"]
            ];

            $data[] = $row;
        }

        echo json_encode($data);
    }

    public function datatable_movimientos()
    {
        $this->validar_sesion();

        $id_apr    = $this->sesión->id_apr_ses;
        $id_cuenta = $this->request->getGet("id_cuenta");

        if (empty($id_cuenta)) {
            echo json_encode(["data" => []]);
            return;
        }

        $datosMovimientos = $this->db->table('transacciones t')
            ->select("t.id as id_movimiento, 
                DATE_FORMAT(t.fecha, '%d %b, %H:%i') as fecha_hora, 
                t.tipo_operacion, 
                t.concepto, 
                 FORMAT(t.monto, 0, 'de_DE') as monto, 
                      FORMAT(t.saldo_resultante, 0, 'de_DE') as saldo_resultante")
            ->where('t.id_cuenta', $id_cuenta)
            ->where('t.id_apr', $id_apr)
            ->where('t.estado', 1)
            ->orderBy('t.id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($datosMovimientos as $key) {
            $row = [
                "id_movimiento"    => $key["id_movimiento"],
                "fecha_hora"       => $key["fecha_hora"],
                "tipo_operacion"   => $key["tipo_operacion"],
                "concepto"         => $key["concepto"],
                "monto"            => $key["monto"],
                "saldo_resultante" => $key["saldo_resultante"]
            ];

            $data[] = $row;
        }

        echo json_encode(["data" => $data]);
    }

    public function guardar_transaccion()
    {
        $this->validar_sesion();

        define("OK", 1);
        define("ACTIVO", 1);

        $fecha      = date("Y-m-d H:i:s");
        $id_usuario = $this->sesión->id_usuario_ses;
        $id_apr     = $this->sesión->id_apr_ses;

        $id_cuenta      = $this->request->getPost("id_cuenta");
        $tipo_operacion = $this->request->getPost("tipo_operacion");
        $monto          = $this->request->getPost("monto");
        $concepto       = $this->request->getPost("concepto");

        if (empty($id_cuenta) || empty($tipo_operacion) || empty($monto) || empty($concepto)) {
            echo "Faltan datos obligatorios para procesar la transacción";
            exit();
        }

        // 1. Obtener el último saldo activo de la cuenta
        $ultimo_mov = $this->db->table('transacciones')
            ->select('saldo_resultante')
            ->where('id_cuenta', $id_cuenta)
            ->where('id_apr', $id_apr)
            ->where('estado', ACTIVO)
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getRowArray();

        $saldo_anterior = ($ultimo_mov && isset($ultimo_mov['saldo_resultante'])) ? (float)$ultimo_mov['saldo_resultante'] : 0.00;

        // 2. Calcular el saldo resultante
        if ($tipo_operacion == 'abono') {
            $nuevo_saldo = $saldo_anterior + (float)$monto;
        } else {
            $nuevo_saldo = $saldo_anterior - (float)$monto;
        }

        // 3. Preparar datos para inserción
        $datosTransaccion = [
            'id_apr'           => $id_apr,
            'id_cuenta'        => $id_cuenta,
            'tipo_operacion'   => $tipo_operacion,
            'monto'            => $monto,
            'concepto'         => $concepto,
            'saldo_resultante' => $nuevo_saldo,
            'estado'           => ACTIVO,
            'id_usuario'       => $id_usuario,
            'fecha'            => $fecha
        ];

        if ($this->transacciones->save($datosTransaccion)) {
            echo OK;

            $id_transaccion = $this->transacciones->insertID();

            if ($id_transaccion > 0) {
                $this->guardar_traza($id_transaccion, 'crear', 'Transacción registrada');
            }
        } else {
            echo "Error al procesar la transacción";
        }
    }

    public function guardar_traza($id_transaccion, $estado, $observacion)
    {
        $this->validar_sesion();

        $fecha      = date("Y-m-d H:i:s");
        $id_usuario = $this->sesión->id_usuario_ses;

        $datosTraza = [
            "id_transaccion" => $id_transaccion,
            "estado"         => $estado,
            "observacion"    => $observacion,
            "id_usuario"     => $id_usuario,
            "fecha"          => $fecha
        ];

        if (!$this->transacciones_traza->save($datosTraza)) {
            echo "Falló al guardar la traza";
        }
    }

    public function cambiar_estado_movimiento()
    {
        $this->validar_sesion();

        define("OK", 1);

        $id_movimiento = $this->request->getPost("id_movimiento");
        $estado        = $this->request->getPost("estado");
        $observacion   = $this->request->getPost("observacion");

        if (empty($id_movimiento)) {
            echo "Falta el identificador del movimiento";
            exit();
        }

        // 1. Obtener los datos del movimiento actual para conocer la cuenta
        $movimiento = $this->transacciones->find($id_movimiento);

        if (!$movimiento) {
            echo "No se encontró el movimiento seleccionado";
            exit();
        }

        $id_cuenta = $movimiento['id_cuenta'];
        $nuevo_estado = ($estado == "eliminar") ? 0 : 1;

        $fecha      = date("Y-m-d H:i:s");
        $id_usuario = $this->sesión->id_usuario_ses;

        $datosMovimiento = [
            "id"         => $id_movimiento,
            "estado"     => $nuevo_estado,
            "id_usuario" => $id_usuario,
            "fecha"      => $fecha
        ];

        if ($this->transacciones->save($datosMovimiento)) {

            $this->transacciones->recalcular_saldos_cuenta(
                $id_cuenta,
                $this->sesión->id_apr_ses
            );

            // 3. Registrar la traza
            $this->guardar_traza($id_movimiento, $estado, $observacion);

            echo OK;
        } else {
            echo "Error al cambiar el estado del movimiento";
        }
    }



    public function obtener_metricas()
    {
        $this->validar_sesion();

        $id_apr    = $this->sesión->id_apr_ses;
        $id_cuenta = $this->request->getGet("id_cuenta");

        if (empty($id_cuenta)) {
            echo json_encode([
                "saldo_total"       => "$0",
                "num_transacciones" => 0
            ]);
            return;
        }

        // 1. Obtener el último saldo resultante activo
        $ultimo_mov = $this->db->table('transacciones')
            ->select('saldo_resultante')
            ->where('id_cuenta', $id_cuenta)
            ->where('id_apr', $id_apr)
            ->where('estado', 1)
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getRowArray();

        // 2. Contar el número total de transacciones activas
        $num_transacciones = $this->db->table('transacciones')
            ->where('id_cuenta', $id_cuenta)
            ->where('id_apr', $id_apr)
            ->where('estado', 1)
            ->countAllResults();

        $saldo = ($ultimo_mov && isset($ultimo_mov['saldo_resultante']))
            ? '$' . number_format($ultimo_mov['saldo_resultante'], 0, '', '.')
            : '$0';

        $salida = [
            "saldo_total"       => $saldo,
            "num_transacciones" => $num_transacciones
        ];

        echo json_encode($salida);
    }
}
