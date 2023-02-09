<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_comunas extends Model {

  protected $table = 'comunas';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'nombre',
   'id_provincia'
  ];
}

?>