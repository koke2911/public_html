<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_apr extends Model {

  protected $table      = 'apr';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'nombre',
   'id_comuna',
   'calle',
   'numero',
   'resto_direccion',
   'hash_sii',
   'codigo_comercio',
   'tope_subsidio',
   'rut',
   'dv',
   'id_usuario',
   'fecha',
   'fono',
   'email',
   'resolution_date',
   'resolution_number',
   'activity',
   'economic_activity_id',
   'website',
   'email_dte',
   'ultimo_folio',
   'clave_dete',
   'clave_appoct',
   'horas_extras'
  ];

  public function datatable_apr($db) {
    $consulta = "SELECT 
							apr.id as id_apr,
						    concat(apr.rut, '-', apr.dv) as rut_apr,
						    apr.nombre as nombre_apr,
						    apr.hash_sii,
						    apr.codigo_comercio,
						    apr.tope_subsidio,
						    p.id_region,
						    c.id_provincia,
						    apr.id_comuna,
          apr.website,
          apr.email,
       apr.email_dte,
						    c.nombre as comuna,
						    apr.calle,
						    apr.numero,
						    apr.resto_direccion,
						    u.usuario,
						    date_format(apr.fecha, '%d-%m-%Y %H:%i:%s') as fecha,
						    apr.fono,
                clave_dete,
                ultimo_folio,
                clave_appoct,
                horas_extras
						from 
							apr
							inner join usuarios u on u.id = apr.id_usuario
						    inner join comunas c on c.id = apr.id_comuna
						    inner join provincias p on p.id = c.id_provincia";

    $query = $db->query($consulta);
    $apr   = $query->getResultArray();

    foreach ($apr as $key) {
      $row = [
       "id_apr"          => $key["id_apr"],
       "rut_apr"         => $key["rut_apr"],
       "nombre_apr"      => $key["nombre_apr"],
       "hash_sii"        => $key["hash_sii"],
       "codigo_comercio" => $key["codigo_comercio"],
       "tope_subsidio"   => $key["tope_subsidio"],
       "id_region"       => $key["id_region"],
       "id_provincia"    => $key["id_provincia"],
       "id_comuna"       => $key["id_comuna"],
       "comuna"          => $key["comuna"],
       "calle"           => $key["calle"],
       "numero"          => $key["numero"],
       "resto_direccion" => $key["resto_direccion"],
       "usuario"         => $key["usuario"],
       "fecha"           => $key["fecha"],
       "fono"            => $key["fono"],
       "email"           => $key['email'],
       "email_dte"       => $key['email_dte'],
       'website'         => $key['website'],
       'clave_dete'      => $key['clave_dete'],
       'ultimo_folio'    => $key['ultimo_folio'],
       'clave_appoct'    => $key['clave_appoct'],
       'horas_extras'    => $key['horas_extras']
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      return "{ \"data\": [] }";
    }
  }

  public function getByRut($db, $rut) {
    $consulta = "SELECT id FROM apr WHERE rut = ?";
    $query    = $db->query($consulta, [$rut]);
    $data     = $query->getResultArray();

    return $data[0];
  }

  public function touchef_credentials($db, $id) {
    $consulta = "SELECT touchef_password, touchef_token, CONCAT(rut, dv) AS rut FROM apr WHERE id = ?";
    $query    = $db->query($consulta, [$id]);
    $data     = $query->getResultArray();

    return $data[0];
  }

  public function touchef_credentials_by_rut($db, $rut) {
    $consulta = "SELECT touchef_password, touchef_token FROM apr WHERE rut = ?";
    $query    = $db->query($consulta, [$rut]);
    $data     = $query->getResultArray();

    return $data[0];
  }
}