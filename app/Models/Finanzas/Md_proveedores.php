<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_proveedores extends Model {

  protected $table      = 'proveedores';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'rut',
   'dv',
   'razon_social',
   'giro',
   'id_comuna',
   'direccion',
   'fono',
   'email',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_proveedores($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							p.id as id_proveedor,
							concat(p.rut, '-', p.dv) as rut_proveedor,
						    p.razon_social,
							p.giro,
							prov.id_region,
							c.id_provincia,
							p.id_comuna,
							c.nombre as comuna,
							p.direccion,
							p.fono,
							p.email,
							u.usuario,
							date_format(p.fecha, '%d-%m-%Y %H:%i:%s') as fecha
						from 
							proveedores p
							inner join usuarios u on u.id = p.id_usuario
							left join comunas c on c.id = p.id_comuna
							left join provincias prov on prov.id = c.id_provincia
						where
							p.id_apr = $id_apr and
							p.estado = 1";

    $query       = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $proveedores = $query->getResultArray();

    foreach ($proveedores as $key) {
      $row = [
       "id_proveedor"  => $key["id_proveedor"],
       "rut_proveedor" => $key["rut_proveedor"],
       "razon_social"  => $key["razon_social"],
       "giro"          => $key["giro"],
       "id_region"     => $key["id_region"],
       "id_provincia"  => $key["id_provincia"],
       "id_comuna"     => $key["id_comuna"],
       "comuna"        => $key["comuna"],
       "direccion"     => $key["direccion"],
       "fono"          => $key["fono"],
       "email"         => $key["email"],
       "usuario"       => $key["usuario"],
       "fecha"         => $key["fecha"]
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

  public function existe_proveedor($rut, $id_apr) {
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

  public function datatable_proveedores_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							p.id as id_proveedor,
							concat(p.rut, '-', p.dv) as rut_proveedor,
							p.razon_social
						from 
							proveedores p
						where
							p.id_apr = ? and
							p.estado = ?";

    $query       = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $proveedores = $query->getResultArray();

    foreach ($proveedores as $key) {
      $row = [
       "id_proveedor"  => $key["id_proveedor"],
       "rut_proveedor" => $key["rut_proveedor"],
       "razon_social"  => $key["razon_social"],
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

  public function datatable_buscar_proveedor($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							p.id as id_proveedor,
							concat(p.rut, '-', p.dv) as rut_proveedor,
						    p.razon_social,
							p.giro
						from 
							proveedores p
						where
							p.id_apr = ? and
							p.estado = ?";

    $query       = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $proveedores = $query->getResultArray();

    foreach ($proveedores as $key) {
      $row = [
       "id_proveedor"  => $key["id_proveedor"],
       "rut_proveedor" => $key["rut_proveedor"],
       "razon_social"  => $key["razon_social"],
       "giro"          => $key["giro"],
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