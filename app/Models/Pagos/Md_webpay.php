<?php namespace App\Models\Pagos;

use CodeIgniter\Model;

class Md_webpay extends Model {

  protected $table = 'webpay';
  protected $primaryKey = 'id_webpay';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id_webpay',
   'vci',
   'amount',
   'status',
   'buy_order',
   'session_id',
   'card_detail',
   'card_number',
   'accounting_date',
   'transaction_date',
   'autorization_code',
   'payment_type_code',
   'installments_amount',
   'installments_number',
   'response_code',
   'balance'
  ];
}

?>