<?php

namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_control_presion extends Model
{
    protected $table         = 'control_presiones';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id_apr',
        'id_usuario',
        'fecha_medicion',
        'punto_medicion',
        'tipo_punto',
        'valor_medido',
        'observacion',
        'estado',
        'fecha_reg'
    ];

    public function datatable_presiones($db, $id_apr)
    {
        $consulta = "SELECT 
                        id,
                        DATE_FORMAT(fecha_medicion, '%d/%m/%Y') as fecha_medicion,
                        punto_medicion,
                        tipo_punto,
                        valor_medido,
                        IFNULL(observacion, '') as observacion
                    FROM 
                        control_presiones
                    WHERE 
                        id_apr = ? AND 
                        estado = 1
                    ORDER BY id DESC";

        $query = $db->query($consulta, [$id_apr]);
        return $query->getResultArray();
    }
}
