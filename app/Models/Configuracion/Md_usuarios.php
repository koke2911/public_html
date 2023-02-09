<?php namespace App\Models\Configuracion;

use CodeIgniter\Model;

class Md_usuarios extends Model {

  protected $table = 'usuarios';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'usuario',
   'clave',
   'id_apr',
   'nombres',
   'ape_paterno',
   'ape_materno',
   'calle',
   'numero',
   'resto_direccion',
   'punto_blue',
   'id_comuna',
   'estado',
   'id_usuario',
   'fecha'
  ];

  public function existe_usuario($usu_cod) {
    $this->select("count(*) as filas");
    $this->where("usu_cod", $usu_cod);
    $datos = $this->findAll();

    return $datos;
  }

    public function datatable_usuarios($db,$apr,$es_admin) {


    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							u.id as id_usuario,
						    u.usuario,
						    u.id_apr,
						    apr.nombre as apr,
						    u.nombres,
						    u.ape_paterno,
						    u.ape_materno,
						    concat(u.nombres, ' ', u.ape_paterno, ' ', u.ape_materno) as nombre_usuario,
						    p.id_region,
						    c.id_provincia,
						    u.id_comuna,
						    c.nombre as comuna,
						    u.calle,
						    u.numero,
						    u.resto_direccion,
						    u.punto_blue,
						    u.estado as id_estado,
						    IFNULL(ELT(FIELD(u.estado, 0, 1, 2), 'Pendiente','Activo', 'Bloqueado'),'Sin registro') as estado,
						    usu.usuario as usuario_reg,
						    date_format(u.fecha, '%d-%m-%Y') as fecha
						from 
							usuarios u
						    inner join apr on apr.id = u.id_apr
						    left join comunas c on c.id = u.id_comuna
						    left join provincias p on p.id = c.id_provincia
						    inner join usuarios usu on usu.id = u.id_usuario";

                if($es_admin!=1){
                  $consulta.=" where u.id_apr=$apr";
                }
               // echo $consulta;

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

}

?>