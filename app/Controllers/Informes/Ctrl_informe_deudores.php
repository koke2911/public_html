<?php

namespace App\Controllers\Informes;

use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;

class Ctrl_informe_deudores extends BaseController {

  protected $metros;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->metros = new Md_metros();
    $this->sesión = session();
    $this->db     = \Config\Database::connect();
  }

  public function datatable_informe_deudores() {
    define("PENDIENTE", 1);
    $fecha = date("Y-m-d");

    $data_fecha = $this->metros
     ->select("distinct date_format(metros.fecha_ingreso, '%m-%Y') as mes_consumo")
     ->where("metros.id_apr", $this->sesión->id_apr_ses)
     ->where("metros.fecha_vencimiento <", $fecha)
     ->where("metros.estado", PENDIENTE)
     ->orderBy("metros.fecha_ingreso", "asc")
     ->findAll();

    $data_socios = $this->metros
     ->distinct("metros.id_socio")
     ->select("metros.id_socio")
     ->select("s.rol")
     ->select("ifnull(concat(s.rut, '-', s.dv), 'No Registrado') as rut")
     ->select("concat(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre")
     ->select("s.abono")
     ->join("socios s", "metros.id_socio = s.id")
     ->where("metros.id_apr", $this->sesión->id_apr_ses)
     ->where("metros.fecha_vencimiento <", $fecha)
     ->where("metros.estado", PENDIENTE)
     ->orderBy("metros.id_socio", "asc")
     ->findAll();

    $data = $this->metros
     ->select("metros.id_socio")
     ->select("metros.total_mes")
     ->select("date_format(metros.fecha_ingreso, '%m-%Y') as mes_consumo")
     ->where("metros.id_apr", $this->sesión->id_apr_ses)
     ->where("metros.fecha_vencimiento <", $fecha)
     ->where("metros.estado", PENDIENTE)
     ->orderBy("metros.id_socio", "asc")
     ->orderBy("metros.fecha_ingreso", "asc")
     ->findAll();

    $body = [];

    foreach ($data_socios as $socios) {
      $row = [
       "id_socio" => $socios["id_socio"],
       "rol"      => $socios["rol"],
       "rut"      => $socios["rut"],
       "nombre"   => $socios["nombre"]
      ];

      
      $total=0;

      foreach ($data_fecha as $fecha) {
        foreach ($data as $metros) {
          if ($metros["id_socio"] == $socios["id_socio"]) {
            if ($fecha["mes_consumo"] == $metros["mes_consumo"]) {
              $row[$fecha["mes_consumo"]] = $metros["total_mes"];
              $total += $metros["total_mes"];
              $row["total"] = $total;
            } else {
              if (empty($row[$fecha["mes_consumo"]])) {
                $row[$fecha["mes_consumo"]] = 0;
              }
            }
          }
        }
      }

      $row['abono']=$socios["abono"];
      array_push($body, $row);
    }

    $respuesta = [
     "data" => $body
    ];

    return json_encode($respuesta);
  }

  public function header_informe_deudores() {
    define("PENDIENTE", 1);
    $fecha = date("Y-m-d");

    $data_fecha = $this->metros
     ->select("distinct date_format(metros.fecha_ingreso, '%m-%Y') as mes_consumo")
     ->where("metros.id_apr", $this->sesión->id_apr_ses)
     ->where("metros.fecha_vencimiento <", $fecha)
     ->where("metros.estado", PENDIENTE)
     ->orderBy("metros.fecha_ingreso", "asc")
     ->findAll();

    $header = [
     "Id. Socio",
     "ROL",
     "RUT",
     "Nombre"
    ];
    $column = [
     "id_socio",
     "rol",
     "rut",
     "nombre"
    ];

    foreach ($data_fecha as $fecha) {
      array_push($header, $fecha["mes_consumo"]);
      array_push($column, $fecha["mes_consumo"]);
    }


    
    array_push($column, "total");
    array_push($header, "total");

     array_push($column, "abono");
    array_push($header, "abono");

    $respuesta = [
     "header" => $header,
     "column" => $column
    ];

    return json_encode($respuesta);
  }
}

?>