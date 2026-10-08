<?php

namespace App\Controllers\Formularios;

use App\Controllers\BaseController;
use App\Models\Formularios\Md_interrupcion_servicios;

class Ctrl_interrupcion_servicios extends BaseController
{
    protected $interrupciones;
    protected $sesion;
    protected $db;

    public function __construct()
    {
        $this->sesion         = session();
        $this->interrupciones = new Md_interrupcion_servicios();
        $this->db             = \Config\Database::connect();
    }

    public function validar_sesion()
    {
        if (!$this->sesion->has("id_usuario_ses")) {
            echo "La sesión expiró, actualice el sitio web con F5";
            exit();
        }
    }

    public function datatable_interrupciones()
    {
        $this->validar_sesion();
        $id_apr = $this->sesion->id_apr_ses;

      

        // Aseguramos la cabecera JSON
        return $this->response->setJSON($this->interrupciones->datatable_interrupciones($this->db, $id_apr));
    }

    public function guardar_interrupcion()
    {
        $this->validar_sesion();

        $id_interrupcion         = $this->request->getPost("id_interrupcion");
        $tipo_interrupcion       = $this->request->getPost("tipo_interrupcion");
        $nivel_afectacion        = $this->request->getPost("nivel_afectacion");
        $fecha_inicio            = $this->request->getPost("fecha_inicio");
        $hora_inicio             = $this->request->getPost("hora_inicio");
        $fecha_termino           = $this->request->getPost("fecha_termino");
        $hora_termino            = $this->request->getPost("hora_termino");
        $motivo_interrupcion     = $this->request->getPost("motivo_interrupcion");
        $cant_usuarios_afectados = $this->request->getPost("cant_usuarios_afectados");
        $ubicacion_falla         = $this->request->getPost("ubicacion_falla");
        $diametro_matriz         = $this->request->getPost("diametro_matriz");
        $material_matriz         = $this->request->getPost("material_matriz");

        $id_apr     = $this->sesion->id_apr_ses;
        $id_usuario = $this->sesion->id_usuario_ses;

        $data = [
            'id_apr'                  => $id_apr,
            'id_usuario'              => $id_usuario,
            'tipo_interrupcion'       => $tipo_interrupcion,
            'nivel_afectacion'        => $nivel_afectacion,
            'fecha_inicio'            => $fecha_inicio,
            'hora_inicio'             => $hora_inicio,
            'fecha_termino'           => !empty($fecha_termino) ? $fecha_termino : null,
            'hora_termino'            => !empty($hora_termino) ? $hora_termino : null,
            'motivo_interrupcion'     => $motivo_interrupcion,
            'cant_usuarios_afectados' => $cant_usuarios_afectados,
            'ubicacion_falla'         => $ubicacion_falla,
            'diametro_matriz'         => $diametro_matriz,
            'material_matriz'         => $material_matriz,
            'estado'                  => 1
        ];

        if (!empty($id_interrupcion)) {
            $data['id'] = $id_interrupcion;
        } else {
            $data['fecha_reg'] = date("Y-m-d H:i:s");
        }

        if ($this->interrupciones->save($data)) {
            echo 1;
        } else {
            echo "Error al guardar la interrupción de servicio";
        }
    }

    public function eliminar_interrupcion()
    {
        $this->validar_sesion();
        $id = $this->request->getPost("id");

        // Borrado lógico actualizando el estado a 0
        $data = [
            'id'     => $id,
            'estado' => 0
        ];

        if ($this->interrupciones->save($data)) {
            echo 1;
        } else {
            echo "Error al eliminar el registro";
        }
    }
}
