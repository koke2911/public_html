<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_facturas_municipalidad extends Model {

  protected $table = 'facturas_municipalidad';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'mes_facturado',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr',
   'folio_factura'
  ];
}

?>