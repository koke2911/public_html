<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_repactaciones_detalle extends Model {

  protected $table = 'repactaciones_detalle';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_repactacion',
   'fecha_pago',
   'numero_cuota',
   'valor_cuota',
   'pagado'
  ];
}

?>