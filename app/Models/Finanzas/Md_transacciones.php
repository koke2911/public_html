<?php

namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_transacciones extends Model
{
    protected $table            = 'transacciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_apr',
        'id_cuenta',
        'tipo_operacion',
        'monto',
        'concepto',
        'saldo_resultante',
        'estado',
        'id_usuario',
        'fecha'
    ];

    protected $useTimestamps = false;

    public function recalcular_saldos_cuenta($id_cuenta, $id_apr)
    {
        $db = \Config\Database::connect();

        $movimientos = $db->table("transacciones")
            ->where("id_cuenta", $id_cuenta)
            ->where("id_apr", $id_apr)
            ->where("estado", 1)
            ->orderBy("id", "ASC")
            ->get()
            ->getResultArray();

        $saldo_acumulado = 0;

        foreach ($movimientos as $mov) {

            $monto = (float)$mov["monto"];

            if ($mov["tipo_operacion"] == "abono") {
                $saldo_acumulado += $monto;
            } else {
                $saldo_acumulado -= $monto;
            }

            if ((float)$mov["saldo_resultante"] !== $saldo_acumulado) {

                $db->table("transacciones")
                    ->where("id", $mov["id"])
                    ->update([
                        "saldo_resultante" => $saldo_acumulado
                    ]);
            }
        }
    }
   
}
