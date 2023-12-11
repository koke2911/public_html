<?php namespace App\Models\RecursosH;

use CodeIgniter\Model;

class Md_vacaciones extends Model {

  protected $table      = 'vacaciones';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
        'id' ,
        'id_funcionario' ,
        'desde' ,
        'hasta' ,
        'cantidad' ,
        'fecha_reg' ,
        'usu_reg'
  ]; 
  
}

?>