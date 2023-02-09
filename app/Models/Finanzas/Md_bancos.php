<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_bancos extends Model {

  protected $table = 'bancos';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'nombre_banco',
   'estado'
  ];
}

?>