<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_funcionarios extends Model {

  protected $table      = 'funcionarios';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'rut',
   'dv',
   'nombres',
   'ape_pat',
   'ape_mat',
   'id_sexo',
   'calle',
   'numero',
   'resto_direccion',
   'id_comuna',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];

  public function datatable_funcionarios($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_funcionario,
							concat(s.rut, '-', s.dv) as rut,
							s.nombres,
							s.ape_pat,
							s.ape_mat,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_completo,
							s.id_sexo,
							p.id_region,
							c.id_provincia,
							s.id_comuna,
							c.nombre as comuna,
							s.calle,
							s.numero,
							s.resto_direccion,
							u.usuario,
							date_format(s.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							funcionarios s
							inner join usuarios u on u.id = s.id_usuario
							left join comunas c on c.id = s.id_comuna
							left join provincias p on p.id = c.id_provincia
						where
							s.id_apr = $id_apr and
							s.estado = 1";

    $query        = $db->query($consulta);
    $funcionarios = $query->getResultArray();

    foreach ($funcionarios as $key) {
      $row = [
       "id_funcionario"  => $key["id_funcionario"],
       "rut"             => $key["rut"],
       "nombres"         => $key["nombres"],
       "ape_pat"         => $key["ape_pat"],
       "ape_mat"         => $key["ape_mat"],
       "nombre_completo" => $key["nombre_completo"],
       "id_sexo"         => $key["id_sexo"],
       "id_region"       => $key["id_region"],
       "id_provincia"    => $key["id_provincia"],
       "id_comuna"       => $key["id_comuna"],
       "comuna"          => $key["comuna"],
       "calle"           => $key["calle"],
       "numero"          => $key["numero"],
       "resto_direccion" => $key["resto_direccion"],
       "usuario"         => $key["usuario"],
       "fecha"           => $key["fecha"]
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

  public function existe_funcionario($rut, $id_apr) {
    $this->select("count(*) as filas");
    $this->where("rut", $rut);
    $this->where("estado", 1);
    $this->where("id_apr", $id_apr);
    $datos = $this->first();

    if (intval($datos["filas"]) > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function datatable_informe_funcionarios($db, $id_apr) {
    $consulta = "SELECT 
							s.id as id_funcionario,
						    concat(s.rut, '-', s.dv) as rut,
						    concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_completo,
						    IFNULL(ELT(FIELD(s.id_sexo, 1, 2), 'Masculino','Femenino'), 'Sin registro') as sexo,
						    ifnull(s.calle, 'Sin registro') as calle,
						    ifnull(s.numero, 0) as numero,
						    ifnull(s.resto_direccion, 'Sin registro') as resto_direccion,
						    ifnull(c.nombre, 'Sin registro') as comuna,
						    IFNULL(ELT(FIELD(s.estado, 0, 1), 'Desactivado','Activado'), 'Sin registro') as estado
						FROM 
							funcionarios s
						    left join comunas c on s.id_comuna = c.id
						where
							s.id_apr = ? and
                            s.estado = 1";

    $query        = $db->query($consulta, [$id_apr]);
    $funcionarios = $query->getResultArray();

    foreach ($funcionarios as $key) {
      $row = [
       "id_funcionario"  => $key["id_funcionario"],
       "rut"             => $key["rut"],
       "nombre_completo" => $key["nombre_completo"],
       "sexo"            => $key["sexo"],
       "calle"           => $key["calle"],
       "numero"          => $key["numero"],
       "resto_direccion" => $key["resto_direccion"],
       "comuna"          => $key["comuna"],
       "estado"          => $key["estado"]
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

  public function datatable_funcionarios_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							s.id as id_funcionario,
							concat(s.rut, '-', s.dv) as rut_funcionario,
							concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_funcionario
						from 
							funcionarios s
						where
							s.id_apr = ? and
							s.estado = ?";

    $query        = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $funcionarios = $query->getResultArray();

    foreach ($funcionarios as $key) {
      $row = [
       "id_funcionario"     => $key["id_funcionario"],
       "rut_funcionario"    => $key["rut_funcionario"],
       "nombre_funcionario" => $key["nombre_funcionario"],
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