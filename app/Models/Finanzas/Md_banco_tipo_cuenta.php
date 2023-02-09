<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_banco_tipo_cuenta extends Model {

  protected $table = 'banco_tipo_cuenta';
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