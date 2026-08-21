<?php

namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_cola_emails extends Model
{
    protected $table            = 'cola_emails';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_metro',
        'id_apr',
        'formato',
        'estado',
        'programado_para',
        'intentos',
        'error_msg'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
