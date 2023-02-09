<?php namespace App\Models\Finanzas;

use CodeIgniter\Model;

class Md_compras extends Model {

  protected $table      = 'compras';
  protected $primaryKey = 'id';

  protected $returnType = 'array';
  // protected $useSoftDeletes = true;

  protected $allowedFields = [
   'id',
   'id_tipo_documento',
   'n_documento',
   'fecha_documento',
   'neto',
   'iva',
   'total',
   'id_proveedor',
   'id_egreso'
  ];

  public function datos_compra($db, $id_egreso) {
    $consulta = "SELECT
							c.id as id_compra,
						    c.id_tipo_documento,
						    c.n_documento,
						    date_format(c.fecha_documento, '%d-%m-%Y') as fecha_documento,
						    c.neto,
						    c.iva,
						    c.total,
						    c.id_proveedor,
						    concat(p.rut, '-', p.dv) as rut_proveedor,
						    p.razon_social
						from 
							compras c
						    inner join proveedores p on c.id_proveedor = p.id
						where 
							c.id_egreso = ?";

    $query = $db->query($consulta, [$id_egreso]);
    $data  = $query->getResultArray();

    return json_encode($data);
  }

  public function datatable_informe_compras_basico($db, $id_apr, $datosBusqueda) {
    define("ACTIVO", 1);
    $estado        = ACTIVO;
    $datosBusqueda = json_decode($datosBusqueda, TRUE);

    $fecha_desde       = $datosBusqueda["fecha_desde"];
    $fecha_hasta       = $datosBusqueda["fecha_hasta"];
    $id_tipo_documento = $datosBusqueda["id_tipo_documento"];
    $id_proveedor      = $datosBusqueda["id_proveedor"];

    $consulta = "SELECT 
							e.id as id_egreso,
						    td.glosa as tipo_documento,
						    date_format(c.fecha_documento, '%d-%m-%Y') as fecha_documento,
						    c.neto,
						    c.iva,
						    c.total,
						    concat(p.rut, '-', p.dv) as rut_proveedor,
						    p.razon_social,
						    date_format(e.fecha, '%d-%m-%Y %H:%i:%s') as fecha_reg
						from 
							compras c
						    inner join egresos e on c.id_egreso = e.id
						    inner join tipo_documento td on c.id_tipo_documento = td.id
						    inner join proveedores p on c.id_proveedor = p.id
						where 
							e.id_apr = $id_apr and
						    e.estado = $estado";

    $bind = [
     $id_apr,
     $estado
    ];

    if ($fecha_desde != "" and $fecha_hasta != "") {
      $consulta .= " and date_format(e.fecha, '%d-%m-%Y') between '$fecha_desde' and '$fecha_hasta'";
      array_push($bind, $fecha_desde, $fecha_hasta);
    }

    if ($id_tipo_documento != "") {
      $consulta .= " and c.id_tipo_documento = $id_tipo_documento";
      array_push($bind, $id_tipo_documento);
    }

    if ($id_proveedor != "") {
      $consulta .= " and c.id_proveedor = $id_proveedor";
      array_push($bind, $id_proveedor);
    }

    $consulta .= " order by e.id asc";

    $query = $db->query($consulta, $bind);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }

  public function datatable_informe_compras_detallado($db, $id_apr, $datosBusqueda) {
    define("ACTIVO", 1);
    $estado        = ACTIVO;
    $datosBusqueda = json_decode($datosBusqueda, TRUE);

    $fecha_desde       = $datosBusqueda["fecha_desde"];
    $fecha_hasta       = $datosBusqueda["fecha_hasta"];
    $id_tipo_documento = $datosBusqueda["id_tipo_documento"];
    $id_proveedor      = $datosBusqueda["id_proveedor"];
    $id_producto       = $datosBusqueda["id_producto"];

    $consulta = "SELECT 
							e.id as id_egreso, 
						    td.glosa as tipo_documento, 
						    date_format(c.fecha_documento, '%d-%m-%Y') as 
						    fecha_documento, 
						    (cd.precio * cd.cantidad) as neto, 
						    round(((cd.precio * cd.cantidad) * 0.19)) as iva, 
						    ((cd.precio * cd.cantidad) + round(((cd.precio * cd.cantidad) * 0.19))) as total, 
						    concat(p.rut, '-', p.dv) as rut_proveedor, 
						    p.razon_social,
						    prod.nombre as producto,
						    cd.cantidad,
						    cd.precio,
						    date_format(e.fecha, '%d-%m-%Y %H:%i:%s') as fecha_reg 
						from 
							compras c 
						    inner join egresos e on c.id_egreso = e.id 
						    inner join tipo_documento td on c.id_tipo_documento = td.id 
						    inner join proveedores p on c.id_proveedor = p.id
						    inner join compras_detalle cd on cd.id_compra = c.id
						    inner join productos prod on cd.id_producto = prod.id
						where 
							e.id_apr = $id_apr and
						    e.estado = $estado";

    $bind = [
     $id_apr,
     $estado
    ];

    if ($fecha_desde != "" and $fecha_hasta != "") {
      $consulta .= " and date_format(e.fecha, '%d-%m-%Y') between '$fecha_desde' and '$fecha_hasta'";
      array_push($bind, $fecha_desde, $fecha_hasta);
    }

    if ($id_tipo_documento != "") {
      $consulta .= " and c.id_tipo_documento = $id_tipo_documento";
      array_push($bind, $id_tipo_documento);
    }

    if ($id_proveedor != "") {
      $consulta .= " and c.id_proveedor = $id_proveedor";
      array_push($bind, $id_proveedor);
    }

    if ($id_producto != "") {
      $consulta .= " and cd.id_producto = $id_producto";
      array_push($bind, $id_producto);
    }

    $consulta .= " order by e.id asc";

    $query = $db->query($consulta, $bind);
    $data  = $query->getResultArray();

    $salida = ["data" => $data];

    return json_encode($salida);
  }
}

?>