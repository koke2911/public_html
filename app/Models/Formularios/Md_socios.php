<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_socios extends Model {

  protected $table      = 'socios';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'rut',
   'dv',
   'rol',
   'nombres',
   'ape_pat',
   'ape_mat',
   'fecha_entrada',
   'fecha_nacimiento',
   'id_sexo',
   'calle',
   'numero',
   'resto_direccion',
   'id_comuna',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr',
   'ruta',
   'abono',
   'email'
  ];

  public function datatable_socios($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_socio,
							concat(s.rut, '-', s.dv) as rut,
						    s.rol,
							s.nombres,
							s.ape_pat,
							s.ape_mat,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_completo,
						    date_format(s.fecha_entrada, '%d-%m-%Y') as fecha_entrada,
						    date_format(s.fecha_nacimiento, '%d-%m-%Y') as fecha_nacimiento,
							s.id_sexo,
							p.id_region,
							c.id_provincia,
							s.id_comuna,
							c.nombre as comuna,
							s.calle,
							s.numero,
							s.resto_direccion,
							s.ruta,
							u.usuario,
							date_format(s.fecha, '%d-%m-%Y %H:%i:%s') as fecha,
       s.email
						from 
							socios s
							inner join usuarios u on u.id = s.id_usuario
							left join comunas c on c.id = s.id_comuna
							left join provincias p on p.id = c.id_provincia
						where
							s.id_apr = $id_apr and
							s.estado = 1";

    $query  = $db->query($consulta);
    $socios = $query->getResultArray();

    foreach ($socios as $key) {
      $row = [
       "id_socio"         => $key["id_socio"],
       "rut"              => $key["rut"],
       "rol"              => $key["rol"],
       "nombres"          => $key["nombres"],
       "ape_pat"          => $key["ape_pat"],
       "ape_mat"          => $key["ape_mat"],
       "nombre_completo"  => $key["nombre_completo"],
       "fecha_entrada"    => $key["fecha_entrada"],
       "fecha_nacimiento" => $key["fecha_nacimiento"],
       "id_sexo"          => $key["id_sexo"],
       "id_region"        => $key["id_region"],
       "id_provincia"     => $key["id_provincia"],
       "id_comuna"        => $key["id_comuna"],
       "comuna"           => $key["comuna"],
       "calle"            => $key["calle"],
       "numero"           => $key["numero"],
       "resto_direccion"  => $key["resto_direccion"],
       "ruta"             => $key["ruta"],
       "usuario"          => $key["usuario"],
       "fecha"            => $key["fecha"],
       "email"            => $key["email"]
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

  public function existe_socio($rut, $rol, $id_apr) {
    $this->select("count(*) as filas");
    $this->where("rut", $rut);
    $this->where("rol", $rol);
    $this->where("estado", 1);
    $this->where("id_apr", $id_apr);
    $datos = $this->first();
    if (intval($datos["filas"]) > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function datatable_informe_socios($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_socio,
						    concat(s.rut, '-', s.dv) as rut,
						    s.rol,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_completo,
						    date_format(s.fecha_entrada, '%d-%m-%Y') as fecha_entrada,
						    date_format(s.fecha_nacimiento, '%d-%m-%Y') as fecha_nacimiento,
						    IFNULL(ELT(FIELD(s.id_sexo, 1, 2), 'Masculino','Femenino'), 'Sin registro') as sexo,
						    ifnull(s.calle, 'Sin registro') as calle,
						    ifnull(s.numero, 0) as numero,
						    ifnull(s.resto_direccion, 'Sin registro') as resto_direccion,
						    ifnull(c.nombre, 'Sin registro') as comuna,
						    ifnull(s.ruta, 'Sin registro') as ruta,
						    IFNULL(ELT(FIELD(s.estado, 0, 1), 'Desactivado','Activado'), 'Sin registro') as estado
						FROM 
							socios s
						    left join comunas c on s.id_comuna = c.id
						where
							s.id_apr = ? and
                            s.estado = 1";

    $query  = $db->query($consulta, [$id_apr]);
    $socios = $query->getResultArray();

    foreach ($socios as $key) {
      $row = [
       "id_socio"         => $key["id_socio"],
       "rut"              => $key["rut"],
       "rol"              => $key["rol"],
       "nombre_completo"  => $key["nombre_completo"],
       "fecha_entrada"    => $key["fecha_entrada"],
       "fecha_nacimiento" => $key["fecha_nacimiento"],
       "sexo"             => $key["sexo"],
       "calle"            => $key["calle"],
       "numero"           => $key["numero"],
       "resto_direccion"  => $key["resto_direccion"],
       "comuna"           => $key["comuna"],
       "ruta"             => $key["ruta"],
       "estado"           => $key["estado"]
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

  public function llenar_grafico_socios($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							IFNULL(ELT(FIELD(id_sexo, 1, 2), 'Hombres','Mujeres'),'No Registrado') as sexo,
						    count(id_sexo) as cantidad
						from 
							socios 
						where 
							id_apr = ? and 
						    estado = ?
						group by
							id_sexo";

    $query  = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $socios = $query->getResultArray();

    foreach ($socios as $key) {
      $row = [
       "sexo"     => $key["sexo"],
       "cantidad" => $key["cantidad"]
      ];

      $data[] = $row;
    }

    if (isset($data)) {
      return json_encode($data);
    } else {
      $data = [];

      return json_encode($data);
    }
  }

  public function datatable_socios_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							s.id as id_socio,
							concat(s.rut, '-', s.dv) as rut_socio,
						    s.rol as rol_socio,
							concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio
						from 
							socios s
						where
							s.id_apr = ? and
							s.estado = ?";

    $query  = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $socios = $query->getResultArray();

    foreach ($socios as $key) {
      $row = [
       "id_socio"     => $key["id_socio"],
       "rut_socio"    => $key["rut_socio"],
       "rol_socio"    => $key["rol_socio"],
       "nombre_socio" => $key["nombre_socio"],
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

  public function get_by_rut($db, $rut, $dv) {
    $consulta = "SELECT * FROM socios WHERE rut = ? AND dv = ?";
    $query    = $db->query($consulta, [
     $rut,
     $dv
    ]);
    $data     = $query->getResultArray();

    return $data[0];
  }
}