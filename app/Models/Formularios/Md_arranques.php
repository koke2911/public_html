<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_arranques extends Model {

  protected $table      = 'arranques';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_socio',
   'estado',
   'id_medidor',
   'id_comuna',
   'calle',
   'numero',
   'resto_direccion',
   'id_sector',
   'alcantarillado',
   'cuota_socio',
   'id_tipo_documento',
   'descuento',
   'id_usuario',
   'fecha',
   'id_apr',
   'razon_social',
   'giro',
   'otros',
   'monto_alcantarillado',
   'monto_cuota_socio',
   'monto_otros'
  ];

  public function datatable_arranques($db, $id_apr) {
    $consulta = "SELECT 
							a.id as id_arranque,
						    a.id_socio,
						    s.rut as rut_socio,
						    s.rol as rol_socio,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
                            m.id as id_medidor,
                            m.numero as n_medidor,
                            m.id_diametro,
						    d.glosa as diametro,
						    a.id_sector,
						    sec.nombre as sector,
						    IFNULL(ELT(FIELD(a.alcantarillado, 0, 1), 'NO','SI'),'Sin registro') as alcantarillado,
						    IFNULL(ELT(FIELD(a.cuota_socio, 0, 1), 'NO','SI'),'Sin registro') as cuota_socio,
							IFNULL(ELT(FIELD(a.otros, 0, 1), 'NO','SI'),'Sin registro') as otros,
						    p.id_region,
						    c.id_provincia,
						    a.id_comuna,
						    a.calle,
						    a.numero,
						    a.resto_direccion,
						    a.id_tipo_documento,
						    a.descuento,
						    u.usuario,
						    date_format(a.fecha, '%d-%m-%Y %H:%i') as fecha,
						    a.razon_social,
						    a.giro,
							a.monto_alcantarillado,
							a.monto_cuota_socio,
							a.monto_otros
						from 
							arranques a
						    left join socios s on a.id_socio = s.id
						    inner join sectores sec on a.id_sector = sec.id
						    left join comunas c on a.id_comuna = c.id
						    left join provincias p on c.id_provincia = p.id
						    inner join tipo_documento td on a.id_tipo_documento = td.id
						    inner join apr on a.id_apr = apr.id
                            left join medidores m on a.id_medidor = m.id
						    left join diametro d on m.id_diametro = d.id
						    inner join usuarios u on a.id_usuario = u.id
						where 
							a.id_apr = $id_apr and
						    a.estado = 1";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function datatable_arranque_reciclar($db, $id_apr) {
    $consulta = "SELECT 
							 a.id as id_arranque,
						     concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
							 m.numero as n_medidor,
							 d.glosa as diametro,
							 sec.nombre as sector,
							 u.usuario,
							 date_format(a.fecha, '%d-%m-%Y %H:%i') as fecha
						from 
							arranques a
							left join socios s on a.id_socio = s.id
							inner join sectores sec on a.id_sector = sec.id
							left join comunas c on a.id_comuna = c.id
							left join provincias p on c.id_provincia = p.id
							inner join tipo_documento td on a.id_tipo_documento = td.id
							inner join apr on a.id_apr = apr.id
							inner join medidores m on a.id_medidor = m.id
							inner join diametro d on m.id_diametro = d.id
							inner join usuarios u on a.id_usuario = u.id
						where 
							a.id_apr = $id_apr and
							a.estado = 0";

    $query     = $db->query($consulta);
    $arranques = $query->getResultArray();

    foreach ($arranques as $key) {
      $row = [
       "id_arranque"  => $key["id_arranque"],
       "nombre_socio" => $key["nombre_socio"],
       "n_medidor"    => $key["n_medidor"],
       "diametro"     => $key["diametro"],
       "sector"       => $key["sector"],
       "usuario"      => $key["usuario"],
       "fecha"        => $key["fecha"]
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

  public function datatable_buscar_socio($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_socio,
						    concat(s.rut, '-', s.dv) as rut,
						    s.rol,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre,
						    date_format(s.fecha_entrada, '%d-%m-%Y') as fecha_entrada
						from 
							arranques a
						    right join socios s on a.id_socio = s.id
						where 
							(a.id_socio is null or a.estado = 0) and
    						s.id_apr = $id_apr and
    						s.estado = 1";

    $query     = $db->query($consulta);
    $arranques = $query->getResultArray();

    foreach ($arranques as $key) {
      $row = [
       "id_socio"      => $key["id_socio"],
       "rut"           => $key["rut"],
       "rol"           => $key["rol"],
       "nombre"        => $key["nombre"],
       "fecha_entrada" => $key["fecha_entrada"]
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

  public function datatable_informe_arranques($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							a.id as id_arranque,
							s.rol as rol_socio,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
						    m.numero as numero_medidor,
						    ifnull(a.calle, 'No Registrado') as calle,
						    case when a.numero = 0 then 'No Registrado' else a.numero end as numero, 
						    ifnull(a.resto_direccion, 'No Registrado') as resto_direccion,
						    case when a.alcantarillado = 1 then 'SI' else 'NO' end as alcantarillado,
						    case when a.cuota_socio = 1 then 'SI' else 'NO' end as cuota_socio,
						    td.glosa as tipo_documento,
						    a.descuento
						from 
							arranques a
						    inner join socios s on a.id_socio = s.id
						    inner join medidores m on a.id_medidor = m.id
						    inner join tipo_documento td on a.id_tipo_documento = td.id
						where
							a.id_apr = ? and
    						a.estado = ?";

    $query     = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $arranques = $query->getResultArray();

    foreach ($arranques as $key) {
      $row = [
       "id_arranque"     => $key["id_arranque"],
       "rol_socio"       => $key["rol_socio"],
       "nombre_socio"    => $key["nombre_socio"],
       "numero_medidor"  => $key["numero_medidor"],
       "calle"           => $key["calle"],
       "numero"          => $key["numero"],
       "resto_direccion" => $key["resto_direccion"],
       "alcantarillado"  => $key["alcantarillado"],
       "cuota_socio"     => $key["cuota_socio"],
       "tipo_documento"  => $key["tipo_documento"],
       "descuento"       => $key["descuento"]
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
}

?>