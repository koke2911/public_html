<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_porcentajes extends Model {

  protected $table = 'porcentajes';
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