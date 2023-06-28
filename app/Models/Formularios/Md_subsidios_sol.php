<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_subsidios_sol extends Model {

  protected $table = 'subsidios_sol';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
			'id' ,
			'id_socio',
			'id_apr' ,
			'estado',
			'fecha_sol',
			'usu_sol' 
		  
  ];

  public function datatable_socios($db,$id_apr){
  	$consulta = "SELECT s.id, s.rol,concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_socio,
					ifnull(sb.id,'Sin Subsidio') as subsidio,
					ifnull(ss.id,'Sin Solicitud') as solicitud,
					s.id as id_so
					 from socios s 
					left join subsidios sb  on s.id=sb.id_socio and sb.estado=1
					left join subsidios_sol ss on s.id=ss.id_socio
					where s.id_apr=$id_apr and s.estado=1";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>


