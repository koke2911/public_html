<?php namespace App\Models\Formularios;

use CodeIgniter\Model;

class Md_desinfeccion extends Model {

  protected $table = 'desinfecciones';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
		  'id',
		  'id_apr',
		  'dia',
		  'hora_ap',
		  'cloro_ap',
		  'id_socio1',
		  'hora_socio1',
		  'cloro_socio1',
		  'id_socio2',
		  'hora_socio2',
		  'cloro_socio2',
		  'frecuencia',
		  'desp',
		  'medidor_caudal',
		  'electricidad',
		  'horometro',
		  'id_usuario',
		  'registrado',
		  'estado',
  ];

  public function datatable_desinfecciones($db,$id_apr){
  	$consulta = "SELECT 		
				d.id,
				d.id_apr,
				date_format(d.dia,'%d-%m-%Y') as dia,
				d.hora_ap,
				d.cloro_ap,
				d.id_socio1,
				d.hora_socio1,
				d.cloro_socio1,
				d.id_socio2,
				d.hora_socio2,
				d.cloro_socio2,
				d.frecuencia,
				d.desp,
				d.medidor_caudal,
				d.electricidad,
				d.horometro,
				concat(s1.nombres, ' ', s1.ape_pat, ' ', s1.ape_mat) as nombre_socio1,
				concat(s2.nombres, ' ', s2.ape_pat, ' ', s2.ape_mat) as nombre_socio2,
				concat(s1.rut, '-', s1.dv) as rut_socio1,
				concat(s2.rut, '-', s2.dv) as rut_socio2,
				s1.rol as rol_socio1,
				s2.rol as rol_socio2            
				from desinfecciones d
				inner join socios s1 on s1.id=d.id_socio1
				inner join socios s2 on s2.id=d.id_socio2 and d.id_apr=$id_apr and d.estado=1
				 order by d.dia desc";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>


