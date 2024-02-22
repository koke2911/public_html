<?php namespace App\Models\RecursosH;

use CodeIgniter\Model;

class Md_inasistencia extends Model {

  protected $table      = 'inasistencias';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
        'id' ,
        'id_funcionario' ,
        'desde' ,
        'hasta' ,
        'cantidad' ,
        'fecha_reg' ,
        'usu_reg',
        'estado',
        'id_apr'
  ]; 
  


 public function datatable_inasistencia($db, $id_apr) {

    $consulta = "SELECT
                v.id,
                concat(f.rut,'-',f.dv) as rut,
                concat(f.nombres,' ',f.ape_pat,' ',f.ape_mat) as funcionario,   
                date_format(desde,'%d-%m-%Y') as desde,
                date_format(hasta,'%d-%m-%Y') as hasta,
                cantidad as Dias,
                date_format(fecha_reg,'%d-%m-%Y') as fecha_genera,
                concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usuario_registra
                from inasistencias v
                inner join funcionarios f on v.id_funcionario=f.id
                inner join usuarios u on u.id=v.usu_reg
                and v.id_apr=$id_apr and f.id=v.id_funcionario   and v.estado=1 
                order by v.id asc";

                // echo $consulta;

    $query        = $db->query($consulta);
    $funcionarios = $query->getResultArray();

    foreach ($funcionarios as $key) {
      $row = [
      "id"  => $key["id"],
      "rut"  => $key["rut"],
      "funcionario"  => $key["funcionario"],
      "desde"  => $key["desde"],
      "hasta"  => $key["hasta"],
      "Dias"  => $key["Dias"],
      "fecha_genera"  => $key["fecha_genera"],
      "usuario_registra"  => $key["usuario_registra"]      
      
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