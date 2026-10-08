<?php

namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_atenciones extends Model
{
    protected $table          = 'atenciones';
    protected $primaryKey     = 'id';
    protected $allowedFields  = [
        'numero_atencion',
        'fecha_atencion',
        'id_socio',
        'nombre_usuario',
        'numero_contrato',
        'atencion_requerida',
        'fecha_respuesta',
        'respuesta_ssr',
        'id_usuario',
        'id_apr',
        'fecha_reg'
    ];

    public function datatable_atenciones($db, $id_apr)
    {
        $consulta = "SELECT 
                        id,
                        numero_atencion,
                        DATE_FORMAT(fecha_atencion, '%d/%m/%Y') AS fecha_atencion,
                        IFNULL(id_socio, '') AS id_socio,
                        nombre_usuario,
                        numero_contrato,
                        atencion_requerida,
                        IFNULL(DATE_FORMAT(fecha_respuesta, '%d/%m/%Y'), '') AS fecha_respuesta,
                        IFNULL(respuesta_ssr, '') AS respuesta_ssr
                    FROM atenciones
                    WHERE id_apr = ?
                    ORDER BY id DESC";

        $query = $db->query($consulta, [$id_apr]);
        return json_encode($query->getResultArray());
    }

    public function obtener_siguiente_numero($db, $id_apr)
    {
        $consulta = "SELECT IFNULL(MAX(CAST(numero_atencion AS UNSIGNED)), 0) + 1 AS siguiente 
                     FROM atenciones 
                     WHERE id_apr = ?";
        $query = $db->query($consulta, [$id_apr]);
        $row = $query->getRowArray();
        return $row['siguiente'];
    }
}
