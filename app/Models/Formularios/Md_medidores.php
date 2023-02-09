<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_medidores extends Model {

  protected $table = 'medidores';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'numero',
   'id_diametro',
   'marca',
   'tipo',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];

  public function datatable_medidores($db, $id_apr) {
    $consulta = "SELECT 
							m.id as id_medidor,
						    m.numero,
						    m.id_diametro,
						    d.glosa as diametro,
						    m.marca,
						    m.tipo,
						    IFNULL(ELT(FIELD(m.estado, 0, 1), 'Eliminado', 'Activo'),'Sin registro') as estado,
						    u.usuario,
						    date_format(m.fecha, '%d-%m-%Y %H:%m') as fecha
						from 
							medidores m
						    inner join diametro d on m.id_diametro = d.id
						    inner join usuarios u on m.id_usuario = u.id
						where
							m.estado = 1 and
						    m.id_apr = $id_apr";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function datatable_medidor_reciclar($db, $id_apr) {
    $consulta = "SELECT 
							m.id as id_medidor,
						    m.numero,
						    m.id_diametro,
						    d.glosa as diametro,
						    IFNULL(ELT(FIELD(m.estado, 0, 1), 'Eliminado', 'Activo'),'Sin registro') as estado,
						    u.usuario,
						    date_format(m.fecha, '%d-%m-%Y %H:%m') as fecha
						from 
							medidores m
						    inner join diametro d on m.id_diametro = d.id
						    inner join usuarios u on m.id_usuario = u.id
						where
							m.estado = 0 and
						    m.id_apr = $id_apr";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function llenar_cmb_medidores($db, $id_apr, $opcion) {
    $consulta = "SELECT 
							id,
						    numero
						from 
							medidores
						where
							id_apr = $id_apr and
						    estado = 1";

    if ($opcion == "FILTRADO") {
      $consulta .= " and id NOT IN(select id_medidor from arranques where id_medidor is not null and id_apr = $id_apr and estado = 1)";
    }

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    return json_encode($data);
  }
}

?>