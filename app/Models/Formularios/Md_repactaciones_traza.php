<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_repactaciones_traza extends Model {

  protected $table = 'repactaciones_traza';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_repactacion',
   'estado',
   'observacion',
   'id_usuario',
   'fecha'
  ];
}

?>