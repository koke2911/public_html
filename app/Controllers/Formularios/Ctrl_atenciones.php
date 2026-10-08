<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_atenciones;
use App\Models\Formularios\Md_socios;

class Ctrl_atenciones extends BaseController
{
    protected $atenciones;
    protected $socios;
    protected $sesion;
    protected $db;

    public function __construct()
    {
        $this->sesion     = session();
        $this->atenciones = new Md_atenciones();
        $this->socios     = new Md_socios();
        $this->db         = \Config\Database::connect();
    }

    public function validar_sesion()
    {
        if (!$this->sesion->has("id_usuario_ses")) {
            echo "La sesión expiró, actualice el sitio web con F5";
            exit();
        }
    }

    public function datatable_atenciones()
    {
        $this->validar_sesion();
        $id_apr = $this->sesion->id_apr_ses;
        echo $this->atenciones->datatable_atenciones($this->db, $id_apr);
    }

    public function datatable_socios()
    {
        $this->validar_sesion();
        $id_apr = $this->sesion->id_apr_ses; // <--- Clave correcta según la sesión

        $consulta = "SELECT 
                        s.id as id_socio,
                        CONCAT(s.rut, '-', s.dv) as rut,
                        s.rol,                          
                        CONCAT(s.nombres, ' ', s.ape_pat, ' ', s.ape_mat) as nombre_completo
                    FROM 
                        socios s                        
                    WHERE
                        s.id_apr = ? AND
                        s.estado = 1";

        $query  = $this->db->query($consulta, [$id_apr]);
        $socios = $query->getResultArray();

        $data = [];
        foreach ($socios as $key) {
            $data[] = [
                "id_socio"        => $key["id_socio"],
                "rut"             => $key["rut"],
                "rol"             => $key["rol"],
                "nombre_completo" => $key["nombre_completo"]
            ];
        }

        return $this->response->setJSON(["data" => $data]);
    }

    public function obtener_siguiente_atencion()
    {
        $this->validar_sesion();
        $id_apr = $this->sesion->id_apr_ses;
        echo $this->atenciones->obtener_siguiente_numero($this->db, $id_apr);
    }

    public function guardar_atencion()
    {
        $this->validar_sesion();

        $id_atencion        = $this->request->getPost("id_atencion");
        $numero_atencion    = $this->request->getPost("numero_atencion");
        $fecha_atencion     = $this->request->getPost("fecha_atencion");
        $nombre_usuario     = $this->request->getPost("nombre_usuario");
        $numero_contrato    = $this->request->getPost("numero_contrato");
        $atencion_requerida = $this->request->getPost("atencion_requerida");
        $fecha_respuesta    = $this->request->getPost("fecha_respuesta");
        $respuesta_ssr      = $this->request->getPost("respuesta_ssr");
        $id_socio      = $this->request->getPost("id_socio");

        $id_apr = $this->sesion->id_apr_ses;

        if ($id_atencion == "") {
            $numero_atencion = $this->atenciones->obtener_siguiente_numero($this->db, $id_apr);
        }

        $data = [
            'numero_atencion'    => $numero_atencion,
            'fecha_atencion'     => $fecha_atencion,
            'nombre_usuario'     => $nombre_usuario,
            'numero_contrato'    => $numero_contrato,
            'atencion_requerida' => $atencion_requerida,
            'fecha_respuesta'    => $fecha_respuesta != "" ? $fecha_respuesta : null,
            'respuesta_ssr'      => $respuesta_ssr,
            'id_usuario'         => $this->sesion->id_usuario_ses,
            'id_apr'             => $id_apr,
            'id_socio'             => $id_socio,
            'fecha_reg'          => date("Y-m-d H:i:s")
        ];

        if ($id_atencion != "") {
            $data['id'] = $id_atencion;
        }

        if ($this->atenciones->save($data)) {
            echo 1;
        } else {
            echo "Error al guardar la atención";
        }
    }

    public function eliminar_atencion()
    {
        $this->validar_sesion();
        $id = $this->request->getPost("id");

        if ($this->atenciones->delete($id)) {
            echo 1;
        } else {
            echo "Error al eliminar";
        }
    }
}
