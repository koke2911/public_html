<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_control_presion;

class Ctrl_control_presion extends BaseController
{
    protected $presiones;
    protected $sesion;
    protected $db;

    public function __construct()
    {
        $this->sesion    = session();
        $this->presiones = new Md_control_presion();
        $this->db        = \Config\Database::connect();
    }

    public function validar_sesion()
    {
        if (!$this->sesion->has("id_usuario_ses")) {
            echo "La sesión expiró, actualice el sitio web con F5";
            exit();
        }
    }

    public function datatable_presiones()
    {
        $this->validar_sesion();
        $id_apr = $this->sesion->id_apr_ses;

        return $this->response->setJSON($this->presiones->datatable_presiones($this->db, $id_apr));
    }

    public function guardar_presion()
    {
        $this->validar_sesion();

        $id_presion     = $this->request->getPost("id_presion");
        $fecha_medicion = $this->request->getPost("fecha_medicion");
        $punto_medicion = $this->request->getPost("punto_medicion");
        $tipo_punto     = $this->request->getPost("tipo_punto");
        $valor_medido   = $this->request->getPost("valor_medido");
        $observacion    = $this->request->getPost("observacion");

        $id_apr     = $this->sesion->id_apr_ses;
        $id_usuario = $this->sesion->id_usuario_ses;

        $data = [
            'id_apr'         => $id_apr,
            'id_usuario'     => $id_usuario,
            'fecha_medicion' => $fecha_medicion,
            'punto_medicion' => $punto_medicion,
            'tipo_punto'     => $tipo_punto,
            'valor_medido'   => $valor_medido,
            'observacion'    => $observacion,
            'estado'         => 1
        ];

        if (!empty($id_presion)) {
            $data['id'] = $id_presion;
        } else {
            $data['fecha_reg'] = date("Y-m-d H:i:s");
        }

        if ($this->presiones->save($data)) {
            echo 1;
        } else {
            echo "Error al guardar la medición de presión";
        }
    }

    public function eliminar_presion()
    {
        $this->validar_sesion();
        $id = $this->request->getPost("id");

        $data = [
            'id'     => $id,
            'estado' => 0
        ];

        if ($this->presiones->save($data)) {
            echo 1;
        } else {
            echo "Error al eliminar la medición";
        }
    }
}
