<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_observaciones_dte extends Model {

  protected $table = 'apr_observaciones';
  protected $primaryKey = 'id';

  protected $returnType = 'array';

  protected $allowedFields = [
   'id',
   'titulo',
   'observacion',
   'id_apr',
   'estado'
  ];
}

?>