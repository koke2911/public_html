<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_repactaciones extends Model {

  protected $table = 'repactaciones';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_socio',
   'deuda_vigente',
   'n_cuotas',
   'fecha_pago',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];
}

?>