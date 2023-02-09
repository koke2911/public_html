<?php namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_caja_webpay extends Model {

  protected $table = 'caja_webpay';
  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id_caja',
   'id_webpay'
  ];
}

?>