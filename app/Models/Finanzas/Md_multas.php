<?php

namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_multas extends Model {

  protected $table      = 'fines';
  protected $primaryKey = 'id';

  protected $returnType = 'array';

  protected $allowedFields = [
   'id',
   'id_socio',
   'monto',
   'tipo',
   'glosa',
   'status',
  ];

}