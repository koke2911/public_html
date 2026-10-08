<?php

namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_interrupcion_servicios extends Model
{
    protected $table            = 'interrupciones_servicios';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_apr',
        'id_usuario',
        'tipo_interrupcion',
        'nivel_afectacion',
        'fecha_inicio',
        'hora_inicio',
        'fecha_termino',
        'hora_termino',
        'motivo_interrupcion',
        'cant_usuarios_afectados',
        'ubicacion_falla',
        'diametro_matriz',
        'material_matriz',
        'estado',
        'fecha_reg'
    ];

    public function datatable_interrupciones($db, $id_apr)
    {
        $consulta = "SELECT 
                    id,
                    tipo_interrupcion,
                    nivel_afectacion,
                    DATE_FORMAT(fecha_inicio, '%d/%m/%Y') as fecha_inicio,
                    DATE_FORMAT(hora_inicio, '%H:%i') as hora_inicio,
                    IFNULL(DATE_FORMAT(fecha_termino, '%d/%m/%Y'), '') as fecha_termino,
                    IFNULL(DATE_FORMAT(hora_termino, '%H:%i'), '') as hora_termino,
                    motivo_interrupcion,
                    cant_usuarios_afectados,
                    ubicacion_falla,
                    IFNULL(diametro_matriz, '') as diametro_matriz,
                    IFNULL(material_matriz, '') as material_matriz
                FROM 
                    interrupciones_servicios
                WHERE 
                    id_apr = $id_apr AND 
                    estado = 1
                ORDER BY id DESC";

        $query = $db->query($consulta);
        return $query->getResultArray(); // Devolver el Array directamente para que setJSON lo procese correctamente
    }
}
