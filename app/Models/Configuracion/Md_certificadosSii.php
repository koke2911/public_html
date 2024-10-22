<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_certificadosSii extends Model {

  protected $table      = 'certificadosii';
  protected $primaryKey = 'id';

  protected $returnType = 'array';

  protected $allowedFields = [
   'id',
   'rut_autorizado',
   'x509',
   'modulo',
   'llave_privada',
   'llave_sin_clave',
   'id_apr',
   'estado',
   'exponente',
   'fecha_caducidad'
];



}