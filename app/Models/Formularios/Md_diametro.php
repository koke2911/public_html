<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_diametro extends Model {

  protected $table = 'diametro';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'glosa',
   'estado'
  ];
}

?>