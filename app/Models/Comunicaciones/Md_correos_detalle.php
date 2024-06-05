<?php namespace App\Models\Comunicaciones;

use CodeIgniter\Model;

class Md_correos_detalle extends Model {

  protected $table      = 'correos_detalle';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
        'id',
        'id_correo',        
        'id_socio',
  ];
}