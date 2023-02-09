<?php namespace App\Models\Inventario;

use CodeIgniter\Model;

class Md_productos extends Model {

  protected $table      = 'productos';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'nombre',
   'marca',
   'modelo',
   'estado',
   'id_usuario',
   'fecha',
   'id_apr'
  ];

  public function datatable_productos($db, $id_apr) {
    define("ACTIVO", 1);
    $estado = ACTIVO;

    $consulta = "SELECT 
							p.id as id_producto,
						    p.nombre,
						    p.marca,
						    p.modelo,
						    (select count(*) from productos_detalles pd where pd.id_producto = p.id and IFNULL(pd.id_estado, -1) != 0) as cantidad,
						    case when p.estado = 1 then 'Activado' else 'Desactivado' end as estado,
						    u.usuario,
						    date_format(p.fecha, '%d-%m-%Y') as fecha
						from 
							productos p
						    inner join usuarios u on p.id_usuario = u.id
						where 
						    p.id_apr = ?";

    $query     = $db->query($consulta, [$id_apr]);
    $productos = $query->getResultArray();

    foreach ($productos as $key) {
      $row = [
       "id_producto" => $key["id_producto"],
       "nombre"      => $key["nombre"],
       "marca"       => $key["marca"],
       "modelo"      => $key["modelo"],
       "cantidad"    => $key["cantidad"],
       "estado"      => $key["estado"],
       "usuario"     => $key["usuario"],
       "fecha"       => $key["fecha"]
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

  public function datatable_informe_inventario($db, $id_apr, $datosBusqueda) {
    define("ACTIVO", 1);
    $estado        = ACTIVO;
    $datosBusqueda = json_decode($datosBusqueda, TRUE);

    $productos     = $datosBusqueda["productos"];
    $estado        = $datosBusqueda["estado"];
    $ubicacion     = $datosBusqueda["ubicacion"];
    $codigo_barras = $datosBusqueda["codigo_barras"];

    $consulta = "SELECT 
							pd.id as id_producto,
						    p.nombre as producto,
						    p.marca,
						    p.modelo,
						    pd.codigo as codigo_barras,
						    ifnull(ep.estado_producto, 'No Registrado') as estado,
						    ifnull(up.ubicacion, 'No Registrado') as ubicacion
						from 
							productos p
						    inner join productos_detalles pd on pd.id_producto = p.id
						    left join estados_producto ep on pd.id_estado = ep.id
						    left join ubicaciones_producto up on pd.id_ubicacion = up.id
						where
							IFNULL(pd.id_estado, -1) != 0 and
    						p.id_apr = $id_apr";

    if ($productos != "") {
      $consulta .= " and p.id = $productos";
    }

    if ($estado != "") {
      $consulta .= " and ep.id = $estado";
    }

    if ($ubicacion != "") {
      $consulta .= " and up.id = $ubicacion";
    }

    if ($codigo_barras != "") {
      $consulta .= " and pd.codigo = $codigo_barras";
    }

    $consulta .= " order by p.nombre asc";

    $query = $db->query($consulta);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>