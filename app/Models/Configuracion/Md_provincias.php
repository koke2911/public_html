<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_provincias extends Model {

  protected $table = 'provincias';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'nombre',
   'id_region'
  ];
}

?>