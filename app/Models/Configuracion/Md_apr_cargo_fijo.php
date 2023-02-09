<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_apr_cargo_fijo extends Model {

  protected $table = 'apr_cargo_fijo';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'cantidad',
   'cargo_fijo',
   'id_apr',
   'id_diametro'
  ];
}

?>