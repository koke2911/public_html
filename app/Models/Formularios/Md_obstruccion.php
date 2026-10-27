<?php

namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_obstruccion extends Model
{
    protected $table         = 'obstrucciones';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id_apr',
        'id_usuario',
        'fecha_inicio',
        'hora_inicio',
        'fecha_termino',
        'hora_termino',
        'duracion',
        'motivo_obstruccion',
        'cant_clientes_afectados',
        'ubicacion_falla',
        'afloramiento',
        'tipo_infraestructura',
        'curso_agua',
        'diametro_colector',
        'material_colector',
        'estado',
        'fecha_reg'
    ];

    public function datatable_obstrucciones($db, $id_apr)
    {
        $consulta = "SELECT 
                        id,
                        DATE_FORMAT(fecha_inicio, '%d/%m/%Y') as fecha_inicio,
                        DATE_FORMAT(hora_inicio, '%H:%i') as hora_inicio,
                        IFNULL(DATE_FORMAT(fecha_termino, '%d/%m/%Y'), '') as fecha_termino,
                        IFNULL(DATE_FORMAT(hora_termino, '%H:%i'), '') as hora_termino,
                        IFNULL(duracion, '') as duracion,
                        motivo_obstruccion,
                        cant_clientes_afectados,
                        ubicacion_falla,
                        afloramiento,
                        tipo_infraestructura,
                        curso_agua,
                        IFNULL(diametro_colector, '') as diametro_colector,
                        IFNULL(material_colector, '') as material_colector
                    FROM 
                        obstrucciones
                    WHERE 
                        id_apr = $id_apr AND 
                        estado = 1
                    ORDER BY id DESC";

        $query = $db->query($consulta);
        return $query->getResultArray();
    }
}
