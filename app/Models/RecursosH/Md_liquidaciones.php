<?php namespace App\Models\RecursosH;

use CodeIgniter\Model;

class Md_liquidaciones extends Model {

  protected $table      = 'liquidaciones';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
    'id',
    'rut',
    'id_funcionario',
    'mes',
    'valor_uf',
    'dias_trabajados',
    'sueldo_base',
    'h_extras_c',
    'h_extras_v',
    'gratif_por',
    'gratif_val',
    'bono_respon',
    'total_imponible',
    'bono_colacion',
    'asig_familiar',
    'bono_movil',
    'viatico',
    'feriado_proporcional',
    'total_otros_haberes',
    'afp',
    'obligatorio',
    'pactada',
    'diferencia_isa',
    'afc_tra_por',
    'afc_tra_v',
    'afc_empl_por',
    'afc_empl_v',
    'sis_por',
    'sis_v',
    'acc_trab_por',
    'acc_trab_v',
    'aporte_empl',
    'otros',
    'total_prevision',
    'base_tributable',
    'apagar',
    'id_apr',
    'fecha_genera',
    'usuario_genera'

  ];

  public function datatable_liquidaciones($db, $id_apr) {
    $consulta = "SELECT
                l.id,
                concat(l.rut,'-',f.dv) as rut,
                concat(f.nombres,' ',f.APE_PAT,' ',F.APE_MAT) as funcionario,
                date_format(mes,'%m-%Y') as mes,
                valor_uf,
                dias_trabajados,
                f.sueldo_bruto,
                l.total_imponible,
                l.total_prevision,
                l.total_otros_haberes,
                l.aporte_empl,                
                apagar,
                l.id_apr,
                fecha_genera,
                concat(u.nombres,' ',u.ape_paterno,' ',u.ape_materno) as usuario_registra
                from liquidaciones l
                inner join funcionarios f on l.rut=f.rut
                inner join usuarios u on u.id=l.usuario_genera
                and l.id_apr=40  and f.estado=1 order by l.id asc";

    $query        = $db->query($consulta);
    $funcionarios = $query->getResultArray();

    foreach ($funcionarios as $key) {
      $row = [
      "id"  => $key["id"],
      "rut"  => $key["rut"],
      "funcionario"  => $key["funcionario"],
      "mes"  => $key["mes"],
      "valor_uf"  => $key["valor_uf"],
      "dias_trabajados"  => $key["dias_trabajados"],
      "sueldo_bruto"  => $key["sueldo_bruto"],
      "total_imponible"  => $key["total_imponible"],
      "total_prevision"  => $key["total_prevision"],
      "total_otros_haberes"  => $key["total_otros_haberes"],
      "aporte_empl"  => $key["aporte_empl"],
      "apagar"  => $key["apagar"],
      "id_apr"  => $key["id_apr"],
      "fecha_genera"  => $key["fecha_genera"],
      "usuario_registra"  => $key["usuario_registra"],
      
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


  public function existe_liquidacion($rut,$mes, $id_apr) {
    $this->select("count(*) as filas");
    $this->where("rut", $rut);
    $this->where("date_format(mes,'%m-%Y')",$mes);
    $this->where("id_apr", $id_apr);
    $datos = $this->first();

    if (intval($datos["filas"]) > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
    
    
    
    

  
}

?>