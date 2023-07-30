<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_tarifas extends Model {

  protected $table      = 'tarifas';
  protected $primaryKey = 'id_tarifa';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id_tarifa',
   'tipo'
  ];


}

?>