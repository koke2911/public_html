<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_tipo_documento extends Model {

  protected $table = 'tipo_documento';
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