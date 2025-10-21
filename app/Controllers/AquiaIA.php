<?php

namespace App\Controllers;

use App\Models\Consumo\Md_metros;
use App\Models\Consumo\Md_metros_traza;
use App\Models\Pagos\Md_caja;
use App\Models\Pagos\Md_caja_detalle;
use App\Models\Pagos\Md_caja_traza;
use App\Models\Formularios\Md_socios;
use App\Models\Formularios\Md_arranques;
use App\Models\Formularios\Md_medidores;
use App\Models\Pagos\Md_webpay;
use App\Models\Pagos\Md_caja_webpay;
use App\Models\Configuracion\Md_apr;
use App\Models\Configuracion\Md_usuarios;



class AquiaIA extends Auth
{
    protected $metros;
    protected $metros_traza;
    protected $caja;
    protected $caja_detalle;
    protected $caja_traza;
    protected $socios;
    protected $webpay;
    protected $caja_webpay;
    protected $apr;
    protected $db;
    protected $arranques;
    protected $medidores;
    protected $usuarios;

    public function __construct()
    {
        $this->metros = new Md_metros();
        $this->metros_traza = new Md_metros_traza();
        $this->caja = new Md_caja();
        $this->caja_detalle = new Md_caja_detalle();
        $this->caja_traza = new Md_caja_traza();
        $this->socios = new Md_socios();
        $this->arranques = new Md_arranques();
        $this->medidores = new Md_medidores();
        $this->webpay = new Md_webpay();
        $this->caja_webpay = new Md_caja_webpay();
        $this->apr = new Md_apr();
        $this->usuarios      = new Md_usuarios();
        $this->db = \Config\Database::connect();
    }



    public function consulta_historico_consumo()
    {
        $token = ($this->request->getHeader("Authorization") != null) ? $this->request->getHeader("Authorization")->getValue() : "";
        if ($this->validateToken($token) == true) {
            if ($this->request->getMethod() == "post") {
                $id_apr = $this->request->getPost('id_apr');
                $id_socio = $this->request->getPost('id_socio');
                $from = $this->request->getPost('from');
                $to = $this->request->getPost('to');


                define("ACTIVO", 1);

                $builder = $this->socios
                    ->select("DATE_FORMAT(m.fecha_ingreso, '%Y-%m-01') as ds")
                    ->select("m.metros as y")
                    ->select("m.id_apr")
                    ->select("socios.id as id_socio")
                    ->select("CONCAT(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre")
                    ->select("m.total_mes as total")
                    ->select("apr.nombre as apr_nombre")
                    ->select("IFNULL(m.id_tipo_documento, '1') as id_tipo_dte")
                    ->select("IFNULL(tp.glosa, 'BOLETA EXENTA') as tipo_dte")
                    ->select("tf.tipo as tarifa")
                    ->select("metros_estados.glosa as estado")
                    ->join("metros m", "m.id_socio = socios.id")
                    ->join("apr", "m.id_apr = apr.id")
                    ->join("arranques a", "a.id_socio = socios.id")
                    ->join("tarifas tf", "tf.id_tarifa = a.tarifa")
                    ->join("tipo_documento tp", "tp.id = m.id_tipo_documento", "left")
                    ->join("metros_estados", "m.estado = metros_estados.id")
                    ->where("m.id_apr", $id_apr)
                    ->where("socios.estado", 1);

                // si viene id_socio, filtra también por él
                if (!empty($id_socio)) {
                    $builder->where("socios.id", $id_socio);
                }

                if (!empty($from) && !empty($to)) {
                    $builder->where("m.fecha_ingreso BETWEEN '$from' AND '$to'");
                }

                $datosSocios = $builder->findAll();


                $salida =  $datosSocios;
                return $this->respond($salida, 200);
            } else {
                $respuesta = [
                    "message" => "No hay datos enviados por post",
                    "estado" => "error",
                    "folio" => ""
                ];

                return $this->respond($respuesta, 401);
            }
        } else {
            $respuesta = [
                "message" => "Token Inválido",
                "estado" => "error",
                "folio" => ""
            ];

            return $this->respond($respuesta, 401);
        }
    }

    public function consulta_login()
    {
        $token = ($this->request->getHeader("Authorization") != null) ? $this->request->getHeader("Authorization")->getValue() : "";
        if ($this->validateToken($token) == true) {
            if ($this->request->getMethod() == "post") {
                $usuario = $this->request->getPost('usuario');
                $password = $this->request->getPost('password');

                $datosUsuario = $this->usuarios->where("usuario", $usuario)
                    ->first();

                if ($datosUsuario != NULL) {
                        if (password_verify($password, $datosUsuario["clave"])) {

                            if($datosUsuario['estado']==1){
                            
                            $datosApr = $this->apr->select("*")
                                ->where("id", $datosUsuario["id_apr"])
                                ->first();

                            $salida =  array("id_usuario"=>$datosUsuario['id'],
                                            "rut_usuario"=> $datosUsuario['usuario'],
                                            "nombre_usuario"=> $datosUsuario['nombres'].' '. $datosUsuario['ape_paterno'].' '. $datosUsuario['ape_materno'],
                                            "id_apr"=> $datosApr['id'],
                                            "nombre_apr" => $datosApr['nombre'],
                                            ); 

                            return $this->respond($salida, 200);
                        }else{
                            $respuesta = [
                                "message" => "Usuario bloqueado",
                                "estado" => "error",
                                "folio" => ""
                            ];
                            return $this->respond($respuesta, 401);
                        }

                    }else{
                        $respuesta = [
                            "message" => "Contraseña Invalida",
                            "estado" => "error",
                            "folio" => ""
                        ];
                        return $this->respond($respuesta, 401);
                    }



                }else{
                    $respuesta = [
                        "message" => "Usuario no existe",
                        "estado" => "error",
                        "folio" => ""
                    ];
                    return $this->respond($respuesta, 401);
                }
            } else {
                $respuesta = [
                    "message" => "No hay datos enviados por post",
                    "estado" => "error",
                    "folio" => ""
                ];

                return $this->respond($respuesta, 401);
            }
        } else {
            $respuesta = [
                "message" => "Token Inválido",
                "estado" => "error",
                "folio" => ""
            ];

            return $this->respond($respuesta, 401);
        }
    }

   
}
