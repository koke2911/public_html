<?php

namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_transacciones_traza extends Model
{
    protected $table            = 'transacciones_traza';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_transaccion',
        'estado',
        'observacion',
        'id_usuario',
        'fecha'
    ];

    protected $useTimestamps = false;

    // Obtener la traza detallada de una transacción por su ID
    public function datatable_transacciones_traza($id_transaccion)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transacciones_traza tt')
            ->select("tt.id, 
                      tt.estado, 
                      tt.observacion, 
                      u.usuario, 
                      DATE_FORMAT(tt.fecha, '%d-%m-%Y %H:%i:%s') as fecha")
            ->join('usuarios u', 'u.id = tt.id_usuario')
            ->where('tt.id_transaccion', $id_transaccion)
            ->orderBy('tt.id', 'DESC');

        return $builder->get()->getResultArray();
    }

    // Obtener transacciones eliminadas para el modal de reciclaje
    public function datatable_transacciones_reciclar($id_apr)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transacciones t')
            ->select("t.id as id_movimiento, 
                      DATE_FORMAT(t.fecha, '%d-%m-%Y %H:%i') as fecha, 
                      t.tipo_operacion, 
                      t.concepto, 
                      FORMAT(t.monto, 2) as monto, 
                      u.usuario")
            ->join('usuarios u', 'u.id = t.id_usuario')
            ->where('t.id_apr', $id_apr)
            ->where('t.estado', 0)
            ->orderBy('t.id', 'DESC');

        return $builder->get()->getResultArray();
    }
}
