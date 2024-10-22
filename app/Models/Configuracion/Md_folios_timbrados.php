<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_folios_timbrados extends Model {

  protected $table      = 'folios_timbrados';
  protected $primaryKey = 'id';

  protected $returnType = 'array';

  protected $allowedFields = [
   'id',
   'sw_ambiente',
   'estado',
   'rut_emisor',
   'razonsocial_emisor',
   'folio_timbraje',
   'tipo_documento',
   'folio_desde',
   'nombre_documento',
   'folio_hasta',
   'total_folios',
   'folios_disponibles',
   'fecha_autorizacion',
   'modulo_folios',
   'exponente',
   'indice',
   'firma_folios',
   'llave_privadafolios',
   'llave_publicafolios',
   'siguiente_folio',
   'id_apr'
];


}