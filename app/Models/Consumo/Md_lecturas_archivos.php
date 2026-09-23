<?php

namespace App\Models\Consumo;

use CodeIgniter\Model;

class Md_lecturas_archivos extends Model
{
    protected $table            = 'lecturas_archivos_procesados';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_apr',
        'nombre_archivo',
        'mes_consumo',
        'fecha_vencimiento',
        'filas_procesadas',
        'filas_totales',
        'fecha_proceso'
    ];
}
