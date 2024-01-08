<?php namespace App\Models\Informes;

use CodeIgniter\Model;

class Md_arqueo_caja extends Model {

  protected $table      = 'arqueo_caja';
  protected $primaryKey = 'id';

  protected $returnType = 'array';

  protected $allowedFields = [
        'id',
        'mes',
        'ingreos',
        'egresos',
        'saldo_periodo',
        'saldo_anterior',
        'saldo_siguiente',
        'saldo_banco',
        'saldo_deposito',
        'total_fondos',
        'fecha_reg',
        'usu_reg',
        'id_apr',
        'estado'
      ];

  public function datatable_arqueo($db, $id_apr) {
      
      $consulta = "SELECT id,date_format(mes,'%Y-%m') as mes,ingreos,egresos,saldo_periodo,saldo_anterior,saldo_siguiente,saldo_banco,saldo_deposito,total_fondos,date_format(fecha_reg,'%d-%m-%Y') as fecha_reg from arqueo_caja where id_apr=$id_apr and estado=1 order by mes desc";

      // echo $consulta;
      $query        = $db->query($consulta);
      $funcionarios = $query->getResultArray();

      foreach ($funcionarios as $key) {
        $row = [      
          "id"=>$key['id'],
          "mes"=>$key['mes'],
          "ingreos"=>$key['ingreos'],
          "egresos"=>$key['egresos'],
          "saldo_periodo"=>$key['saldo_periodo'],
          "saldo_anterior"=>$key['saldo_anterior'],
          "saldo_siguiente"=>$key['saldo_siguiente'],
          "saldo_banco"=>$key['saldo_banco'],
          "saldo_deposito"=>$key['saldo_deposito'],
          "total_fondos"=>$key['total_fondos'],
          "fecha_reg"=>$key['fecha_reg']
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

  public function existe_arqueo($mes, $id_apr) {
    $this->select("count(*) as filas");    
    $this->where("date_format(mes,'%m-%Y')",$mes);
    $this->where("id_apr", $id_apr);
    $this->where("estado=1");
    $datos = $this->first();

    if (intval($datos["filas"]) > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
    

}

?>