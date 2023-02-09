<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_cuentas extends Model {

  protected $table      = 'cuentas';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_banco',
   'id_tipo_cuenta',
   'n_cuenta',
   'rut',
   'dv',
   'nombre_cuenta',
   'email',
   'id_usuario',
   'fecha',
   'estado',
   'id_apr'
  ];

  public function datatable_cuentas($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							c.id as id_cuenta,
                            c.id_banco,
                            b.nombre_banco,
                            c.id_tipo_cuenta,
                            tp.glosa as tipo_cuenta,
                            c.n_cuenta,
                            concat(c.rut, '-', c.dv) as rut_cuenta,
                            c.nombre_cuenta,
                            c.email,
                            u.usuario,
                            date_format(c.fecha, '%d-%m-%Y') as fecha
						from 
							cuentas c
							inner join usuarios u on u.id = c.id_usuario
                            inner join bancos b on b.id = c.id_banco
                            inner join banco_tipo_cuenta  tp on tp.id = c.id_tipo_cuenta
						where
							c.id_apr = ? and
							c.estado = ?";

    $query   = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $cuentas = $query->getResultArray();

    foreach ($cuentas as $key) {
      $row = [
       "id_cuenta"      => $key["id_cuenta"],
       "id_banco"       => $key["id_banco"],
       "banco"          => $key["nombre_banco"],
       "id_tipo_cuenta" => $key["id_tipo_cuenta"],
       "tipo_cuenta"    => $key["tipo_cuenta"],
       "n_cuenta"       => $key["n_cuenta"],
       "rut_cuenta"     => $key["rut_cuenta"],
       "nombre_cuenta"  => $key["nombre_cuenta"],
       "email_cuenta"   => $key["email"],
       "usuario"        => $key["usuario"],
       "fecha"          => $key["fecha"]
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

  public function existe_cuenta($rut, $id_banco, $id_tipo_cuenta, $n_cuenta, $id_apr) {
    $this->select("count(*) as filas");
    $this->where("rut", $rut);
    $this->where("id_banco", $id_banco);
    $this->where("id_tipo_cuenta", $id_tipo_cuenta);
    $this->where("n_cuenta", $n_cuenta);
    $this->where("estado", 1);
    $this->where("id_apr", $id_apr);
    $datos = $this->first();

    if (intval($datos["filas"]) > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function datatable_cuentas_reciclar($db, $id_apr) {
    define("DESACTIVADO", 0);
    $estado = DESACTIVADO;

    $consulta = "SELECT 
							c.id as id_cuenta,
                            b.nombre_banco,
                            tp.glosa as tipo_cuenta,
                            c.n_cuenta,
                            concat(c.rut, '-', c.dv) as rut_cuenta,
                            c.nombre_cuenta,
                            c.email
						from 
							cuentas c
                            inner join bancos b on b.id = c.id_banco
                            inner join banco_tipo_cuenta  tp on tp.id = c.id_tipo_cuenta
						where
							c.id_apr = $id_apr and
							c.estado = $estado";

    $query   = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $cuentas = $query->getResultArray();

    foreach ($cuentas as $key) {
      $row = [
       "id_cuenta"     => $key["id_cuenta"],
       "nombre_banco"  => $key["nombre_banco"],
       "tipo_cuenta"   => $key["tipo_cuenta"],
       "n_cuenta"      => $key["n_cuenta"],
       "rut_cuenta"    => $key["rut_cuenta"],
       "nombre_cuenta" => $key["nombre_cuenta"],
       "email"         => $key["email"],
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

  public function datatable_buscar_cuenta($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							c.id as id_cuenta,
                            b.nombre_banco,
                            tp.glosa as tipo_cuenta,
                            c.n_cuenta,
                            concat(c.rut, '-', c.dv) as rut_cuenta,
                            c.nombre_cuenta,
                            c.email
						from 
							cuentas c
                            inner join bancos b on b.id = c.id_banco
                            inner join banco_tipo_cuenta  tp on tp.id = c.id_tipo_cuenta
						where
							c.id_apr = ? and
							c.estado = ?";

    $query   = $db->query($consulta, [
     $id_apr,
     $estado
    ]);
    $cuentas = $query->getResultArray();

    $salida = ["data" => $cuentas];

    return json_encode($salida);
  }
}

?>